<?php
/**
 * @file
 * Overrides theme_ctools_collapsible().
 */

/**
 * Overrides theme_ctools_collapsible().
 */
function bootstrap_subtheme_ctools_collapsible($vars) {
  $build = array(
    '#type' => 'fieldset',
    '#title' => $vars['handle'],
    '#collapsible' => TRUE,
    '#collapsed' => $vars['collapsed'],
    '#children' => $vars['content'],
  );
  return drupal_render($build);
}
