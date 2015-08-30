<?php
/**
 * @file
 * Process and preprocess functions for theme_block().
 */

/**
 * Implements hook_preprocess_block().
 */
function bootstrap_subtheme_preprocess_block(&$variables) {
  $block = $variables['block'];

  // Set block module as a modifier as well as the block delta.
  $block_class_module = drupal_html_class($block->module);
  $block_class_delta = drupal_html_class($block->delta);
  if ($block->module === 'dw' && $block->delta === 'embed_view' && isset($block->view_name) && isset($block->view_display_id)) {
    if ($key = array_search('block-' . $block_class_module, $variables['classes_array'])) {
      unset($variables['classes_array'][$key]);
    }
    $variables['classes_array'][] = 'block--' . drupal_html_class($block->view_name);
    $variables['classes_array'][] = 'block--' . drupal_html_class($block->view_name) . '--' . drupal_html_class($block->view_display_id);
    // Reset block name and delta as if it were a view.
    $block->module = 'views';
    $block->delta = "{$block->view_name}-{$block->view_display_id}";
  }
  else {
    $variables['classes_array'] = preg_replace("/^block-($block_class_module)$/", 'block--${1}', $variables['classes_array']);
    $variables['classes_array'][] = "block--$block_class_delta";
  }

  // Bare templates.
  $bare = isset($block->context) && $block->context === 'global';
  if (!$bare) {
    $bare_blocks = array(
      'api' => array(NULL),
      'views' => array(NULL),
    );
    foreach ($bare_blocks as $module => $deltas) {
      if ($block->module === $module) {
        foreach ($deltas as $delta) {
          if (isset($delta) && $block->delta !== $delta) {
            continue;
          }
          $bare = TRUE;
          break;
        }
      }
    }
  }
  if ($bare) {
    $variables['theme_hook_suggestions'][] = 'block__bare';
  }
}
