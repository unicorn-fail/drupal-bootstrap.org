<?php
/**
 * @file
 * Process and preprocess functions for theme_api_defined().
 */

/**
 * Overrides api_preprocess_api_defined().
 */
function bootstrap_subtheme_preprocess_api_defined(&$variables) {
  $object = $variables['object'];
  $branch = $variables['branch'];
  $variables['file_summary'] = '';

  $options = array(
    'attributes' => array(
      'data-anchor-ignore' => 'true',
    ),
  );

  if ($object->start_line) {
    $options['fragment'] = 'source.' . $object->start_line;
  }

  if ($file_info = api_object_load((int) $object->file_did, $branch, 'file')) {
    $options['attributes']['title'] = $file_info->summary;
  }

  $variables['file_link'] = str_replace('/', '/<wbr>', dirname($object->file_name)) . '/<wbr>' . l(basename($object->file_name), api_url($object, TRUE), $options);

  if ($object->start_line) {
    $variables['file_link'] = t('!file (line @start_line)', array(
      '!file' => $variables['file_link'],
      '@start_line' => $object->start_line,
    ));
  }
}
