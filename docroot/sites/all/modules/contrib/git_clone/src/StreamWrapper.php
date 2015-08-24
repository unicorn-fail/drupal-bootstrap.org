<?php
/**
 * @file
 * Contains Drupal\GitClone\StreamWrapper.
 */

namespace Drupal\GitClone;

/**
 * Drupal system stream wrapper abstract class.
 */
class StreamWrapper extends \DrupalLocalStreamWrapper {

  /**
   * {@inheritdoc}
   */
  public function getDirectoryPath() {
    return _git_clone_path();
  }

  /**
   * {@inheritdoc}
   */
  public function getExternalUrl() {
    return FALSE;
  }

}
