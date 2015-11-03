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
    "ref" : "7.x-3.0",
    "refType" : "tag",
    "settings" : { "fetch_threshold" : "2419200" },
    "title" : "Bootstrap: 7.x-3.0",
    "url" : "http:\\/\\/git.drupal.org\\/project\\/bootstrap.git"
  }');
  $items['bootstrap_7.x-3.x'] = entity_import('git_clone', '{
    "name" : "bootstrap_7.x-3.x",
    "ref" : "7.x-3.x",
    "refType" : "branch",
    "settings" : { "fetch_threshold" : "3600" },
    "title" : "Bootstrap: 7.x-3.x",
    "url" : "http:\\/\\/git.drupal.org\\/project\\/bootstrap.git"
  }');
  $items['bootstrap_8.x-3.x'] = entity_import('git_clone', '{
    "name" : "bootstrap_8.x-3.x",
    "ref" : "8.x-3.x",
    "refType" : "branch",
    "settings" : { "fetch_threshold" : "3600" },
    "title" : "Bootstrap: 8.x-3.x",
    "url" : "http:\\/\\/git.drupal.org\\/project\\/bootstrap.git"
  }');
  $items['icon_7.x-1.x'] = entity_import('git_clone', '{
    "name" : "icon_7.x-1.x",
    "ref" : "7.x-1.x",
    "refType" : "branch",
    "settings" : { "fetch_threshold" : "604800" },
    "title" : "Icon: 7.x-1.x",
    "url" : "http:\\/\\/git.drupal.org\\/project\\/icon.git"
  }');
  return $items;
}
