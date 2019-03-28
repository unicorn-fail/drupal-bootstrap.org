<?php
/**
 * @file
 * Contains GitCloneStreamWrapper.
 */

/**
 * Drupal system stream wrapper abstract class.
 */
class GitCloneStreamWrapper extends DrupalLocalStreamWrapper {

  /**
   * List of already determined local path URIs.
   *
   * @var array
   */
  protected static $localPaths = array();

  /**
   * Flag indicating whether in development mode.
   *
   * @var bool
   */
  protected $devMode = FALSE;

  /**
   * An array of development paths to use instead.
   *
   * @var bool
   */
  protected $devPaths = array();

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    $this->devMode = variable_get('git_clone_dev', FALSE);
    $this->devPaths = variable_get('git_clone_dev_paths', array());
  }

  /**
   * {@inheritdoc}
   */
  public function getDirectoryPath() {
    return _git_clone_path();
  }

  /**
   * {@inheritdoc}
   */
  protected function getLocalPath($uri = NULL) {
    if (!isset($uri)) {
      $uri = $this->uri;
    }

    $target = $this->getTarget($uri);
    $parts = explode(DIRECTORY_SEPARATOR, $target);
    $refType = array_shift($parts);
    $name = array_shift($parts);

    // Immediately return if already processed.
    if (!isset(static::$localPaths["$refType:$name"])) {
      if ($this->devMode && isset($this->devPaths[$refType][$name])) {
        $path = $this->devPaths[$refType][$name];
        $dir = dirname($path);
      }
      else {
        $dir = $this->getDirectoryPath();
        $path = "$dir/$refType/$name";
      }

      // Add support for symlinks.
      if (is_link($path)) {
        $path = readlink($path);
        $dir = dirname($path);
      }

      $realpath = realpath($path);
      if (!$realpath) {
        // This file does not yet exist.
        $realpath = realpath(dirname($path)) . '/' . drupal_basename($path);
      }

      $directory = realpath($dir);
      if (!$realpath || !$directory || strpos($realpath, $directory) !== 0) {
        static::$localPaths["$refType:$name"] = FALSE;
      }
      else {
        static::$localPaths["$refType:$name"] = "$realpath/";
      }
    }

    return static::$localPaths["$refType:$name"] ? rtrim(static::$localPaths["$refType:$name"] . implode('/', $parts), '/') : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getExternalUrl() {
    return FALSE;
  }

}
