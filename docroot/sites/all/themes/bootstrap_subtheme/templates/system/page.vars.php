<?php
/**
 * @file
 * Process and preprocess functions for theme_page().
 */

/**
 * Implements hook_preprocess_HOOK().
 */
function bootstrap_subtheme_process_page(&$variables) {
  if ($file = menu_get_object('api_filename', 2)) {
    $variables['title'] = '<span class="label label-primary">' . t('file') . '</span> <small>' . $file->title . '</small>';
  }
  elseif ($item = menu_get_object('api_item', 4)) {
    switch ($item->object_type) {
      case 'function': $class = 'success'; break;
      case 'constant': $class = 'info'; break;
      case 'class': $class = 'warning'; break;
      default: $class = 'default';
    }
    $variables['title'] = '<span class="label label-' . $class . '">' . $item->object_type . '</span> <small>' . $item->title . '</small>';
  }
}
