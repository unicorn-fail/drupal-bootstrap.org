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
  $form['search']['#size'] = 25;
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
    $branches = api_get_branches();
    $projects = _api_make_menu_projects();
    $is_default = ($branch->branch_name === $projects[$branch->project]['use branch']);
    $suffix = ($is_default) ? '' : '/' . $branch->branch_name;

    $types = array(
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
    $item = _db_api_active_item();
    foreach ($types as $type => $title) {
      if ($type === '' || $counts[$type] > 0) {
        $branch_path = 'api/' . $branch->project;
        $path = $branch_path;
        if ($type) {
          $path .= "/$type";
          $title = '<span class="badge">' . $counts[$type] . '</span>' . $title;
        }
        $path .= $suffix;

        $class = array('list-group-item');
        if ($type || ($type === '' && !$counts['groups'])) {
          if ($type === 'groups') {
            $path = $branch_path . $suffix;
          }
          if ($path === $current_path || ($item && preg_match('/^' . $item->object_type . '/', $type))) {
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
        else {
          $links[] = array(
            '#theme' => 'html_tag__api__navigation_link',
            '#tag' => 'div',
            '#value' => $title,
            '#attributes' => array(
              'class' => $class,
            ),
          );
        }
      }
    }

    $items = array();
    foreach ($branches as $obj) {
      $is_default = ($obj->branch_name === $projects[$obj->project]['use branch']);
      $suffix = ($is_default) ? '' : '/' . $obj->branch_name;
      $items[] = array(
        '#theme' => 'link',
        '#text' => $obj->title,
        '#path' => 'api/' . $obj->project . $suffix,
        '#options' => array(
          'html' => FALSE,
          'attributes' => array(),
        ),
        '#active' => $branch->branch_name === $obj->branch_name,
      );
    }

    $data = array(
      'subject' => t('API Navigation'),
      'content' => array(
        'links' => $links,
        'branches' => array(
          '#theme' => 'bootstrap_dropdown',
          '#toggle' => array(
            '#theme' => 'button',
            '#button_type' => 'button',
            '#value' => t('Projects') . ' <span class="caret"></span>',
            '#attributes' => array(
              'class' => array('btn-default', 'btn-block'),
            ),
          ),
          '#items' => $items,
        ),
      ),
    );
  }
}
