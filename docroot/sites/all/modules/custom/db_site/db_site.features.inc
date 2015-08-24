<?php
/**
 * @file
 * db_site.features.inc
 */

/**
 * Implements hook_default_git_clone().
 */
function db_site_default_git_clone() {
  $items = array();
  $items['bootstrap_7.x-3.0'] = entity_import('git_clone', '{
    "name" : "bootstrap_7.x-3.0",
    "title" : "Bootstrap: 7.x-3.0",
    "url" : "http:\\/\\/git.drupal.org\\/project\\/bootstrap.git",
    "ref" : "7.x-3.0",
    "refType" : "tag",
    "settings" : { "default" : "7.x-3.x" }
  }');
  $items['bootstrap_7.x-3.x'] = entity_import('git_clone', '{
    "name" : "bootstrap_7.x-3.x",
    "title" : "Bootstrap: 7.x-3.x",
    "url" : "http:\\/\\/git.drupal.org\\/project\\/bootstrap.git",
    "ref" : "7.x-3.x",
    "refType" : "branch",
    "settings" : { "default" : "7.x-3.x" }
  }');
  return $items;
}
