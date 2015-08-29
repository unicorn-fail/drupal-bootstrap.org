<?php
/**
 * @file
 * Process and preprocess functions for theme_breadcrumb().
 */

/**
 * Implements hook_preprocess_HOOK().
 */
function bootstrap_subtheme_preprocess_breadcrumb(&$variables) {
  $breadcrumbs = &$variables['breadcrumb'];
  foreach ($breadcrumbs as &$breadcrumb) {
    if (is_array($breadcrumb)) {
      $data = &$breadcrumb['data'];
    }
    else {
      $data = &$breadcrumb;
    }
    $text = preg_quote(t('API reference'));
    $data = preg_replace("/$text/", t('Documentation'), $data);
  }
}
