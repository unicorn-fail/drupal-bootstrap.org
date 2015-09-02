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
  $css_options = array(
    'group' => CSS_THEME,
    'scope' => 'footer',
  );
  $js_options = array(
    'group' => JS_THEME,
    'scope' => 'footer',
  );
  drupal_add_css("$theme_path/libraries/prismjs/prism.css", $css_options);
  drupal_add_css("$theme_path/libraries/bootstrap-anchor/css/bootstrap-anchor.css", $css_options);
  drupal_add_css("$theme_path/css/style.css", $css_options);
  drupal_add_js("$theme_path/libraries/jquery.matchHeight/jquery.matchHeight.min.js", $js_options);
  drupal_add_js("$theme_path/libraries/bootstrap-anchor/js/bootstrap-anchor.js", $js_options);
  drupal_add_js("$theme_path/js/global.js", $js_options);
  $variables['prismjs'] = url("$theme_path/libraries/prismjs/prism.js");
}
