<?php
/**
 * @file
 * Overrides theme_more_link().
 */

/**
 * Overrides theme_more_link().
 *
 * @param array $variables
 *   An associative array containing:
 *   - url: The URL of the main page.
 *   - title: A descriptive verb for the link, like 'Read more'.
 */
function bootstrap_subtheme_more_link(array $variables) {
  $output = '<div class="more-link">';
  $output .= l(t('More'), $variables['url'], array(
    'attributes' => array(
      'class' => array('btn', 'btn-xs', 'btn-default', 'pull-right'),
    ),
  ));
  $output .= '</div>';
  return $output;
}
