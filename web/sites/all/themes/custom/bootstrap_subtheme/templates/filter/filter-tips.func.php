<?php
/**
 * @file
 * Overrides theme_filter_tips().
 *
 * @todo Move upstream.
 */

/**
 * Overrides theme_filter_tips().
 */
function bootstrap_subtheme_filter_tips($variables) {
  global $user;

  // Create a place holder for the tabs.
  $build['tabs'] = array(
    '#theme' => 'item_list',
    '#items' => array(),
    '#attributes' => array(
      'class' => array(
        'nav',
        'nav-tabs',
      ),
      'role' => 'tablist',
    ),
  );

  // Create a placeholder for the panes.
  $build['panes'] = array(
    '#theme_wrappers' => array('container'),
    '#attributes' => array(
      'class' => array(
        'tab-content',
      ),
    ),
  );

  $format_id = arg(2);
  $current_path = current_path();

  $formats = filter_formats($user);
  $filter_info = filter_get_filters();
  foreach ($formats as $format) {
    $tab = array(
      'data' => array(
        '#type' => 'link',
        '#title' => check_plain($format->name),
        '#href' => $current_path,
        '#attributes' => array(
          'role' => 'tab',
          'data-toggle' => 'tab',
        ),
        '#options' => array(
          'fragment' => $format->format,
        ),
      ),
    );
    if (!$format_id || $format_id === $format->format) {
      $tab['class'][] = 'active';
      $format_id = $format->format;
    }
    $build['tabs']['#items'][] = $tab;

    // Retrieve each filter in the format.
    $tips = array();
    $filters = filter_list_format($format->format);
    foreach ($filters as $name => $filter) {
      if ($filter->status && isset($filter_info[$name]['tips callback']) && is_callable($filter_info[$name]['tips callback'])) {
        $tip = $filter_info[$name]['tips callback']($filter, $format, TRUE);
        if (isset($tip)) {
          $tips[] = $tip;
        }
      }
    }

    // Construct the pane.
    $pane = array(
      '#theme_wrappers' => array('container'),
      '#attributes' => array(
        'class' => array(
          'tab-pane',
          'fade',
        ),
        'id' => $format->format,
      ),
    );
    if (count($tips) === 1) {
      $pane['list'] = array(
        '#markup' => $tips[0],
      );
    }
    else {
      $pane['list'] = array(
        '#theme' => 'item_list',
        '#items' => $tips,
      );
    }
    if ($format_id === $format->format) {
      $pane['#attributes']['class'][] = 'active';
      $pane['#attributes']['class'][] = 'in';
      $format_id = $format->format;
    }
    $build['panes'][] = $pane;
  }

  return drupal_render($build);
}
