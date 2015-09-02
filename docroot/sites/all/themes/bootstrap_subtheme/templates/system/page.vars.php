<?php
/**
 * @file
 * Process and preprocess functions for theme_page().
 */

/**
 * Implements hook_preprocess_HOOK().
 */
function bootstrap_subtheme_process_page(&$variables) {
  if ($item = _db_api_active_item()) {
    $class = 'default';
    $type = $item->object_type;
    $title = preg_replace('/^' . $type . '\s?/i', '', $variables['title']);
    switch ($type) {
      case 'file':
        $class = 'primary';
        break;

      case 'function':
        $class = 'success';
        break;

      case 'constant':
        $class = 'info';
        break;

      case 'class':
        $class = 'warning';
        break;

      case 'group':
        $class = 'info';
        $type = $item->subgroup ? 'sub-topic' : 'topic';
        break;

      case 'mainpage':
        $type = FALSE;
        $title = check_plain($item->title);
        break;
    }
    if ($type) {
      $title = '<span class="label label-' . $class . '">' . $type . '</span> ' . $title;
    }
    $variables['title'] = $title;
  }
}
