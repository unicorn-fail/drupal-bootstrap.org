<?php
/**
 * @file
 * Overrides theme_container().
 *
 * @todo these shouldn't be necessary at all, something is broken in the registry.
 */

/**
 * Overrides theme_container().
 */
function bootstrap_subtheme_container($variables) {
  return bootstrap_container($variables);
}

/**
 * Overrides theme_container().
 */
function bootstrap_subtheme_container__alert($variables) {
  return bootstrap_container($variables);
}

/**
 * Overrides theme_container().
 */
function bootstrap_subtheme_container__alert__danger($variables) {
  return bootstrap_container($variables);
}

/**
 * Overrides theme_container().
 */
function bootstrap_subtheme_container__alert__info($variables) {
  return bootstrap_container($variables);
}

/**
 * Overrides theme_container().
 */
function bootstrap_subtheme_container__alert__success($variables) {
  return bootstrap_container($variables);
}

/**
 * Overrides theme_container().
 */
function bootstrap_subtheme_container__alert__warning($variables) {
  return bootstrap_container($variables);
}
