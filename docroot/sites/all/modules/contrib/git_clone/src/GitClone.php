<?php
/**
 * @file
 * Contains Drupal\GitClone\GitClone.
 */

namespace Drupal\GitClone;

use Gitonomy\Git\Admin;
use Gitonomy\Git\Repository;

/**
 * Class GitClone.
 *
 * @package Drupal\GitClone
 *
 * @todo Most (if not all) the public properties should really be protected.
 * The way Entity API uses them (creation/saving) seems to be causing issues
 * with privatizing them (even with magic getter/setter methods). Research
 * more on this subject and change them later, if possible.
 */
class GitClone extends \Entity {

  /**************************************************************************
   * Public properties.
   **************************************************************************/

  /**
   * The repository machine name identifier.
   *
   * @var string
   */
  public $name;

  /**
   * The display title of this entity.
   *
   * @var string
   */
  public $title;

  /**
   * The public remote repository URL this git entity will use.
   *
   * @var string
   */
  public $url;

  /**
   * The set reference of the git clone.
   *
   * @var array
   */
  public $ref;

  /**
   * The set reference type of the git clone.
   *
   * @var string
   */
  public $refType;

  /**
   * All supported references for the repository.
   *
   * @var array
   */
  public $refs;

  /**
   * The repository object.
   *
   * @var \Gitonomy\Git\Repository
   */
  public $repository;

  /**
   * Settings for the entity.
   *
   * @var array
   */
  public $settings;

  /**
   * The exportable status of the entity.
   *
   * @var int
   */
  public $status = ENTITY_CUSTOM;

  /**
   * The name of the providing module if the entity has been defined in code.
   *
   * @var string
   */
  public $module;

  /**************************************************************************
   * Private properties.
   **************************************************************************/

  /**
   * Allowed reference types.
   *
   * @var array
   */
  private static $allowedRefs = array('branch', 'tag');

  /**************************************************************************
   * Sub-classed methods.
   **************************************************************************/

  /**
   * {@inheritdoc}
   */
  public function __sleep() {
    $vars = parent::__sleep();
    unset($vars['rdf_mapping'], $vars['refs'], $vars['repository']);
    return $vars;
  }

  /**
   * {@inheritdoc}
   */
  public function save($queue = TRUE) {
    $this->init();
    if ($queue) {
      $this->queue();
    }
    /** @var EntityController $controller */
    $controller = entity_get_controller($this->entityType);
    return $controller->save($this);
  }

  /**
   * Set up the object instance on construction or unserializiation.
   */
  protected function setUp() {
    parent::setUp();
    $this->init();
  }

  /**************************************************************************
   * Public methods.
   **************************************************************************/

  /**
   * Processes a git clone entity from the queue.
   *
   * This method should not be used directly, use the queue method instead.
   *
   * @return bool
   *   TRUE if GitClone successfully dequeued, FALSE on error.
   *
   * @see GitClone::queue()
   *
   * @throws \Exception
   *   Thrown when a git command has failed.
   */
  public function dequeue() {
    if (!$this->reset()) {
      throw new \Exception(t('Unable to reset GitClone working tree.'));
    };
    if (!$this->fetch()) {
      throw new \Exception(t('Unable to fetch GitClone references.'));
    };
    if (!$this->checkout()) {
      throw new \Exception(t('Unable to checkout set GitClone reference.'));
    };
    if (!$this->merge()) {
      throw new \Exception(t('Unable to merge GitClone remote reference into local working tree.'));
    };
    return TRUE;
  }

  /**
   * Returns the git clone file system directory path.
   *
   * @param bool $create
   *   Toggle determining whether or not to create the directory if it does not
   *   exist.
   * @param bool $absolute
   *   Toggle determining whether or not to return the entire system path. If
   *   FALSE, it will be prefixed with the gitclone:// stream wrapper.
   *
   * @return string|FALSE
   *   The git clone path or FALSE on error.
   */
  public function getPath($create = TRUE, $absolute = TRUE) {
    if (empty($this->refType) || empty($this->ref) || empty($this->name)) {
      return FALSE;
    }
    $path = "gitclone://$this->refType/$this->name";
    if ($create) {
      if (!is_dir($path) && !drupal_mkdir($path, NULL, TRUE)) {
        drupal_set_message(t('The directory %directory does not exist and could not be created.', array('%directory' => $path)), 'error');
        return FALSE;
      }
      if (is_dir($path) && !is_writable($path) && !drupal_chmod($path)) {
        drupal_set_message(t('The directory %directory exists but is not writable and could not be made writable.', array('%directory' => $path)), 'error');
        return FALSE;
      }
    }
    else {
      if (!is_dir($path)) {
        return FALSE;
      }
      if (is_dir($path) && !is_writable($path) && !drupal_chmod($path)) {
        drupal_set_message(t('The directory %directory exists but is not writable and could not be made writable.', array('%directory' => $path)), 'error');
      }
    }
    if ($absolute) {
      return drupal_realpath($path);
    }
    return $path;
  }

  /**
   * Initializes the git clone repository directory.
   *
   * @return GitClone
   *   The current GitClone entity instance.
   *
   * @chainable
   */
  public function init() {
    if (!$this->repository) {
      $options = _git_clone_gitonomy_options();
      if (($path = $this->getPath()) && !empty($this->url)) {
        $git_exists = file_exists("$path/.git");
        if (!$git_exists && in_array($this->refType, self::$allowedRefs)) {
          try {
            $this->repository = Admin::init($path, FALSE, $options);
            $this->run('remote', array('add', 'origin', $this->url));
          }
          catch (\Exception $e) {
            drupal_set_message($e->getMessage(), 'error');
          }
        }
        elseif ($git_exists && in_array($this->refType, self::$allowedRefs)) {
          $this->repository = new Repository($path, $options);
          $this->reset();
        }
      }
      else {
        $temp_dir = 'temporary://git_clone-' . drupal_hash_base64(REQUEST_TIME);
        if (file_prepare_directory($temp_dir, FILE_CREATE_DIRECTORY | FILE_MODIFY_PERMISSIONS)) {
          $temp_dir = drupal_realpath($temp_dir);
          drupal_register_shutdown_function(function () use ($temp_dir) {
            file_unmanaged_delete_recursive($temp_dir);
          });
          try {
            $this->repository = Admin::init($temp_dir, FALSE, $options);
          }
          catch (\Exception $e) {
            watchdog('git_clone', $e->getMessage(), WATCHDOG_ERROR);
          }
        }
        if (!$this->repository) {
          drupal_set_message(t('Unable to create temporary git clone repository: @directory. Please verify your system temporary directory is writable.', array(
            '@directory' => $temp_dir,
          )), 'error');
        }
      }
    }
    return $this;
  }

  /**
   * Queues a git clone entity.
   *
   * @return GitClone
   *   The current GitClone entity instance.
   *
   * @chainable
   */
  public function queue() {
    /** @var \DrupalQueueInterface $q */
    $q = \DrupalQueue::get(GIT_CLONE_QUEUE, TRUE);
    $q->createQueue();
    $q->createItem($this);
    return $this;
  }

  /**
   * Retrieves and parses a repository's remote references.
   *
   * @return bool
   *   TRUE if the remote references were parsed, FALSE on failure.
   */
  public function lsRemote() {
    // Immediately fail if there is no URL.
    if (!$this->url) {
      return FALSE;
    }

    $output = $this->run('ls-remote', array($this->url), TRUE);

    // Immediately fail if the command failed execution, (e.g. invalid URL).
    if ($output === FALSE) {
      return FALSE;
    }

    $default = NULL;
    $refs = array(
      'branch' => array(),
      'tag' => array(),
    );
    $settings = array(
      'default' => NULL,
    );

    // Extract the references from the output.
    foreach (array_filter(explode("\n", $output)) as $lines) {
      list($hash, $ref) = explode("\t", $lines);
      if ($ref === 'HEAD') {
        $default = $hash;
        continue;
      }
      $parts = explode('/', $ref);
      if (isset($parts[1]) && isset($parts[2])) {
        $type = $parts[1];
        if ($type === 'heads') {
          $type = 'branch';
        }
        elseif ($type === 'tags') {
          $type = 'tag';
        }
        $name = $parts[2];
        if ($default === $hash) {
          $settings['default'] = $name;
        }
        $refs[$type][$name] = array(
          'name' => $name,
          'sha' => $hash,
          'type' => $type,
        );
      }
    }

    $current_settings = $this->settings;
    $this->refs = $refs;
    $this->settings = $settings;

    // Save (without re-queueing) if settings have changed.
    if (!isset($this->is_new) && drupal_array_diff_assoc_recursive($settings, $current_settings)) {
      $this->refs = $refs;
      $this->settings = $settings;
      $this->save(FALSE);
    }

    return TRUE;
  }

  /**************************************************************************
   * Protected methods.
   **************************************************************************/

  /**
   * Checks out the reference currently assigned to the GitClone entity.
   *
   * @return GitClone
   *   The current GitClone entity instance.
   *
   * @chainable
   */
  protected function checkout() {
    $args = array('-B', $this->ref);
    if ($this->refType === 'branch') {
      $args[] = '--track';
      $args[] = "origin/$this->ref";
    }
    elseif ($this->refType === 'tag') {
      $args[] = "tags/$this->ref";
    }
    return $this->run('checkout', $args);
  }

  /**
   * Cleans the repository of any un-tracked files.
   *
   * Forcibly removes all un-tracked empty directories and ignored files.
   *
   * @return GitClone
   *   The current GitClone entity instance.
   *
   * @chainable
   */
  protected function clean() {
    return $this->run('clean', array('-dfx'));
  }

  /**
   * Fetches all remote references.
   *
   * @return GitClone
   *   The current GitClone entity instance.
   *
   * @chainable
   */
  protected function fetch() {
    $args = array('--all', '--force', '--prune');
    if ($this->refType === 'tag') {
      $args[] = '--tags';
    }
    return $this->run('fetch', $args);
  }

  /**
   * Merges the remote origin reference into the current working tree.
   *
   * @return GitClone
   *   The current GitClone entity instance.
   *
   * @chainable
   */
  protected function merge() {
    // Only pull down references that can change (e.g. branches).
    if ($this->refType === 'branch') {
      return $this->run('merge', array("origin/$this->ref"));
    }
    return $this;
  }

  /**
   * Resets the current working tree.
   *
   * @param bool $hard
   *   Toggle determining whether or not to pass the --hard option to git. This
   *   will reset HEAD, index and working tree.
   * @param bool $clean
   *   Toggle determining whether or not to also clean the repository of any
   *   un-tracked files.
   *
   * @return GitClone
   *   The current GitClone entity instance.
   *
   * @chainable
   */
  protected function reset($hard = TRUE, $clean = TRUE) {
    $this->run('reset', array('--hard'));
    if ($clean) {
      $this->clean();
    }
    return $this;
  }

  /**
   * Runs git commands on the repository.
   *
   * This is a wrapper around \Gitonomy\Git\Repository::run(). The GitClone
   * entity must catch whether or not the repository has actually been
   * initialized and any errors produced from the command executed.
   *
   * @param string $command
   *   Git command to run (checkout, branch, tag).
   * @param array $args
   *   Arguments of git command.
   * @param bool $return_output
   *   Toggle determining whether or not to return the output from $command.
   *
   * @return FALSE|string|GitClone
   *   Anytime there is an error, the return value will always be FALSE.
   *   If $return_output is set, the output of $command is returned.
   *   Otherwise, the current GitClone instance is returned for chainability.
   *
   * @see \Gitonomy\Git\Repository::run()
   *
   * @chainable
   */
  protected function run($command, array $args = array(), $return_output = FALSE) {
    $output = $return_output ? '' : $this;
    if ($this->repository) {
      try {
        $command_hook = _git_clone_hook_name($command);
        $context = array(
          'output' => $return_output,
        );
        drupal_alter('git_clone_pre_' . $command_hook, $args, $this, $context);
        $ret = $this->repository->run($command, $args);
        watchdog('git_clone', '!command !args: <pre><code>!output</code></pre>', array(
          '!command' => $command,
          '!args' => implode(' ', $args),
          '!output' => $ret,
        ), WATCHDOG_DEBUG);
        drupal_alter('git_clone_post_' . $command_hook, $ret, $this, $context);
        if ($return_output) {
          $output = $ret;
        }
      }
      catch (\Exception $e) {
        drupal_set_message($e->getMessage(), 'error');
        $output = FALSE;
      }
    }
    else {
      $output = FALSE;
    }
    return $output;
  }

}
