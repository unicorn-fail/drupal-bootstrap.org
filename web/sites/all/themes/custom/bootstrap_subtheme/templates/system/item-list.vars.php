<?php
/**
 * @file
 * Process and preprocess functions for theme_item_list().
 */

/**
 * Implements hook_preprocess_HOOK().
 */
function bootstrap_subtheme_preprocess_item_list__alert(&$variables) {
  if (!is_array($variables['title'])) {
    $variables['title'] = array('text' => $variables['title']);
  }
  $variables['title']['level'] = 'h4';
}
