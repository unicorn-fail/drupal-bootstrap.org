<?php
/**
 * @file
 * Overrides theme_status_report().
 */

/**
 * Returns HTML for the status report.
 *
 * @param array $variables
 *   An associative array containing:
 *   - requirements: An array of requirements.
 *
 * @ingroup themeable
 */
function bootstrap_admin_status_report(array $variables) {
  $requirements = $variables['requirements'];
  $severities = array(
    REQUIREMENT_INFO => array(
      'title' => t('Info'),
      'class' => 'info',
      'icon' => array(
        '#theme' => 'icon',
        '#bundle' => 'bootstrap',
        '#icon' => 'glyphicon-info-sign',
        '#attributes' => array(
          'class' => array('text-info'),
        ),
      ),
    ),
    REQUIREMENT_OK => array(
      'title' => t('OK'),
      'class' => 'success',
      'icon' => array(
        '#theme' => 'icon',
        '#bundle' => 'bootstrap',
        '#icon' => 'glyphicon-ok',
        '#attributes' => array(
          'class' => array('text-success'),
        ),
      ),
    ),
    REQUIREMENT_WARNING => array(
      'title' => t('Warning'),
      'class' => 'warning',
      'icon' => array(
        '#theme' => 'icon',
        '#bundle' => 'bootstrap',
        '#icon' => 'glyphicon-warning-sign',
        '#attributes' => array(
          'class' => array('text-warning'),
        ),
      ),
    ),
    REQUIREMENT_ERROR => array(
      'title' => t('Error'),
      'class' => 'danger',
      'icon' => array(
        '#theme' => 'icon',
        '#bundle' => 'bootstrap',
        '#icon' => 'glyphicon-remove',
        '#attributes' => array(
          'class' => array('text-danger'),
        ),
      ),
    ),
  );
  $build = array(
    '#theme' => 'table',
    '#attributes' => array(
      'class' => 'system-status-report',
    ),
  );

  $rows = array();
  foreach ($requirements as $requirement) {
    if (empty($requirement['#type'])) {
      $severity = $severities[isset($requirement['severity']) ? (int) $requirement['severity'] : REQUIREMENT_OK];

      $row = array();
      $row_class = array($severity['class'], 'text-' . $severity['class']);

      $icon = array(
        'class' => array('status-icon'),
        'data' => array(
          '#markup' => '<span title="' . $severity['title'] . '"><span class="element-invisible">' . $severity['title'] . '</span>' . render($severity['icon']) . '</span>',
        ),
      );

      $title = array(
        'class' => array('status-title'),
        'data' => array(
          '#markup' => $requirement['title'],
        ),
      );

      $value = array(
        'class' => array('status-value'),
        'data' => array(
          '#markup' => $requirement['value'],
        ),
      );

      $description = FALSE;
      if (!empty($requirement['description'])) {
        $description = array(
          array(
            'class' => array('status-description'),
            'colspan' => 3,
            'data' => $requirement['description'],
          ),
        );
      }

      // Construct the row.
      $row[] = $icon;
      $row[] = $title;
      $row[] = $value;
      if ($description) {
        $row_class[] = 'merge-down';
      }
      $rows[] = array(
        'class' => $row_class,
        'data' => $row,
      );

      // Append an extra row for the description, if any.
      if ($description) {
        $rows[] = array(
          'class' => array(
            $severity['class'],
            'text-' . $severity['class'],
            'merge-up',
          ),
          'data' => $description,
        );
      }
    }
  }
  $build['#rows'] = $rows;
  $build['#context']['hover'] = FALSE;
  return drupal_render($build);
}
