<?php
/**
 * @file
 * Process and preprocess functions for theme_container().
 */

/**
 * Implements hook_preprocess_HOOK().
 */
function bootstrap_subtheme_preprocess_container__alert(&$variables) {
  $variables['element']['#attributes']['class'][] = 'alert';
}

/**
 * Implements hook_preprocess_HOOK().
 */
function bootstrap_subtheme_preprocess_container__alert__danger(&$variables) {
  $variables['element']['#attributes']['class'][] = 'alert';
  $variables['element']['#attributes']['class'][] = 'alert-danger';
}

/**
 * Implements hook_preprocess_HOOK().
 */
function bootstrap_subtheme_preprocess_container__alert__info(&$variables) {
  $variables['element']['#attributes']['class'][] = 'alert';
  $variables['element']['#attributes']['class'][] = 'alert-info';
}

/**
 * Implements hook_preprocess_HOOK().
 */
function bootstrap_subtheme_preprocess_container__alert__success(&$variables) {
  $variables['element']['#attributes']['class'][] = 'alert';
  $variables['element']['#attributes']['class'][] = 'alert-success';
}

/**
 * Implements hook_preprocess_HOOK().
 */
function bootstrap_subtheme_preprocess_container__alert__warning(&$variables) {
  $variables['element']['#attributes']['class'][] = 'alert';
  $variables['element']['#attributes']['class'][] = 'alert-warning';
}
