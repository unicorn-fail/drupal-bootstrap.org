<?php
/**
 * @file
 * Process and preprocess functions for theme_html().
 */

/**
 * Implements hook_preprocess_HOOK().
 */
function bootstrap_subtheme_preprocess_html(&$variables) {
  $theme_path = drupal_get_path('theme', 'bootstrap_subtheme');
  drupal_add_css("$theme_path/libraries/prismjs/prism.css", CSS_THEME);
  drupal_add_js("$theme_path/js/global.js", array(
    'group' => JS_THEME,
    'scope' => 'footer',
  ));
  $variables['prismjs'] = url("$theme_path/libraries/prismjs/prism.js");
}
