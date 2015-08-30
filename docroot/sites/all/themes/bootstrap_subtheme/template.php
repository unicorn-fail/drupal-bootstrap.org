<?php
/**
 * @file
 * template.php
 */

/**
 * Implements hook_form_FORM_ID_alter().
 */
function bootstrap_subtheme_form_api_search_form_alter(&$form, &$form_state) {
  _bootstrap_add_class('navbar-form', $form);
  _bootstrap_add_class('navbar-right', $form);
  _bootstrap_add_class('col-sm-3', $form);
  $form['search']['#attributes']['placeholder'] = $form['search']['#title'];
  $form['search']['#input_group_button'] = TRUE;
  $form['search']['#maxlength'] = NULL;
  $form['search']['#size'] = 40;
  $form['search']['#title_display'] = 'invisible';
}

/**
 * Implements hook_block_view_MODULE_DELTA_alter().
 */
function bootstrap_subtheme_block_view_api_navigation_alter(&$data, $block) {
  $branch = api_get_active_branch();
  if (user_access('access API reference') && !empty($branch)) {
    // Figure out if this is the default branch for this project, the same
    // way the menu system decides.
    $projects = _api_make_menu_projects();
    $is_default = ($branch->branch_name === $projects[$branch->project]['use branch']);
    $suffix = ($is_default) ? '' : '/' . $branch->branch_name;

    $types = array(
      '' => $branch->title,
      'groups' => t('Topics'),
      'classes' => t('Classes'),
      'functions' => t('Functions'),
      'files' => t('Files'),
      'namespaces' => t('Namespaces'),
      'services' => t('Services'),
      'constants' => t('Constants'),
      'globals' => t('Globals'),
      'deprecated' => t('Deprecated'),
    );

    $links = array(
      '#theme_wrappers' => array('container__api__navigation'),
      '#attributes' => array(
        'class' => array('list-group'),
      ),
    );

    $current_path = current_path();
    $counts = api_listing_counts($branch);
    foreach ($types as $type => $title) {
      if ($type === '' || $counts[$type] > 0) {
        $path = 'api/' . $branch->project;
        if ($type) {
          $path .= "/$type";
          $title = '<span class="badge">' . $counts[$type] . '</span>' . $title;
        }
        $path .= $suffix;

        $class = array('list-group-item');
        if ($path == $current_path) {
          $class[] = 'active';
        }
        $links[] = array(
          '#theme' => 'link__api__navigation_link',
          '#text' => $title,
          '#path' => $path,
          '#options' => array(
            'html' => TRUE,
            'attributes' => array(
              'class' => $class,
            ),
          ),
        );
      }
    }

    $data = array(
      'subject' => t('API Navigation'),
      'content' => $links,
    );
  }
}
