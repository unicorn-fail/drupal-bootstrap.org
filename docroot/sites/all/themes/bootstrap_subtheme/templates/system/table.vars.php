<?php
/**
 * @file
 * Process and preprocess functions for theme_table().
 */

/**
 * Implements hook_preprocess_table().
 */
function bootstrap_subtheme_preprocess_table__commonmark_tip(&$variables) {
  _bootstrap_remove_class('table-hover', $variables);
//  _bootstrap_remove_class('table-striped', $variables);
}
