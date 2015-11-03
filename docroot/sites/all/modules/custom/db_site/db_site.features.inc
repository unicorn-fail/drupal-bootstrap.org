<?php
/**
 * @file
 * db_site.features.inc
 */

/**
 * Implements hook_views_default_views_alter().
 */
function db_site_views_default_views_alter(&$data) {
  if (isset($data['api_listings'])) {
    $data['api_listings']->display['block_files']->display_options['style_options']['columns']['deprecated'] = 'object_name'; /* WAS: 'title' */
    $data['api_listings']->display['block_files']->display_options['style_options']['default'] = -1; /* WAS: 'title' */
    unset($data['api_listings']->display['block_class']->display_options['fields']['file_name']);
    unset($data['api_listings']->display['block_files']->display_options['fields']['title']);
    unset($data['api_listings']->display['block_files']->display_options['style_options']['columns']['title']);
    unset($data['api_listings']->display['block_files']->display_options['style_options']['info']['title']);
    unset($data['api_listings']->display['default']->display_options['fields']['file_name']);
    unset($data['api_listings']->display['default']->display_options['use_ajax']);
  }
  if (isset($data['api_references'])) {
    unset($data['api_references']->display['default']->display_options['fields']['file_name']);
    unset($data['api_references']->display['default']->display_options['use_ajax']);
  }
}

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
