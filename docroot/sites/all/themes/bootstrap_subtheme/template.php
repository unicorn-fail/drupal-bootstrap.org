<?php
/**
 * @file
 * template.php
 */

function bootstrap_subtheme_form_api_search_form_alter(&$form, &$form_state) {
  $form['search']['#input_group_button'] = TRUE;
}
