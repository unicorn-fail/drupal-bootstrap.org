<?php

/**
 * @file
 * db_site.features.features_overrides.inc
 */

/**
 * Implements hook_features_override_default_overrides().
 */
function db_site_features_override_default_overrides() {
  // This code is only used for UI in features. Exported alters hooks do the magic.
  $overrides = array();

  // Exported overrides for: views_view
  $overrides["views_view.api_listings.display|block_class|display_options|fields|deprecated|alter|text"] = '<span class="deprecated label label-warning" title="Deprecated" data-toggle="tooltip"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span></span>';
  $overrides["views_view.api_listings.display|block_class|display_options|fields|file_name"]["DELETED"] = TRUE;
  $overrides["views_view.api_listings.display|block_deprecated|display_options|fields|file_name"]["DELETED"] = TRUE;
  $overrides["views_view.api_listings.display|block_deprecated|display_options|filters|object_type"]["DELETED"] = TRUE;
  $overrides["views_view.api_listings.display|block_files|display_options|fields|deprecated|alter|text"] = '<span class="deprecated label label-warning" title="Deprecated" data-toggle="tooltip"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span></span>';
  $overrides["views_view.api_listings.display|block_files|display_options|fields|title"]["DELETED"] = TRUE;
  $overrides["views_view.api_listings.display|block_files|display_options|style_options|columns|deprecated"] = 'object_name';
  $overrides["views_view.api_listings.display|block_files|display_options|style_options|columns|title"]["DELETED"] = TRUE;
  $overrides["views_view.api_listings.display|block_files|display_options|style_options|default"] = -1;
  $overrides["views_view.api_listings.display|block_files|display_options|style_options|info|title"]["DELETED"] = TRUE;
  $overrides["views_view.api_listings.display|block_items_file|display_options|fields|deprecated|alter|text"] = '<span class="deprecated label label-warning" title="Deprecated" data-toggle="tooltip"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span></span>';
  $overrides["views_view.api_listings.display|default|display_options|fields|deprecated|alter|text"] = '<span class="deprecated label label-warning" title="Deprecated" data-toggle="tooltip"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span></span>';
  $overrides["views_view.api_listings.display|default|display_options|fields|file_name"]["DELETED"] = TRUE;
  $overrides["views_view.api_listings.display|default|display_options|style_options|columns|file_name"]["DELETED"] = TRUE;
  $overrides["views_view.api_listings.display|default|display_options|style_options|info|file_name"]["DELETED"] = TRUE;
  $overrides["views_view.api_listings.display|default|display_options|use_ajax"]["DELETED"] = TRUE;
  $overrides["views_view.api_references.display|default|display_options|fields|file_name"]["DELETED"] = TRUE;
  $overrides["views_view.api_references.display|default|display_options|use_ajax"]["DELETED"] = TRUE;

 return $overrides;
}
