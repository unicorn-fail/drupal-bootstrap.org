<?php
/**
 * @file
 * Process and preprocess functions for theme_region().
 */

/**
 * Implements hook_preprocess_region().
 */
function bootstrap_subtheme_preprocess_region(&$variables) {
  $region = $variables['region'];
  $classes = &$variables['classes_array'];

  // Content region.
  if ($region === 'footer') {
    $classes[] = 'row';
  }

}
