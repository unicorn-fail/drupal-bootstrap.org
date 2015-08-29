<?php
/**
 * @file
 * Process and preprocess functions for theme_menu_tree().
 */

/**
 * Implements hook_preprocess_HOOK().
 */
function bootstrap_subtheme_preprocess_menu_tree__primary(&$variables) {
  // Temporary hack to get "active-trail" items to show up in nav.
  // @todo fix this properly and move upstream.
  $variables['tree'] = preg_replace_callback('/<li[^>]+>/i', function ($matches) {
    return str_replace('active-trail', 'active', $matches[0]);
  }, $variables['tree']);
}
