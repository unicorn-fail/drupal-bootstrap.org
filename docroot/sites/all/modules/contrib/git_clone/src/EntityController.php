<?php
/**
 * @file
 * Contains Drupal\GitClone\EntityController.
 */

namespace Drupal\GitClone;

/**
 * Class Entity.
 *
 * @package Drupal\GitClone
 */
class EntityController extends \EntityAPIControllerExportable {

  /**
   * {@inheritdoc}
   */
  public function export($entity, $prefix = '') {
    $vars = get_object_vars($entity);
    unset($vars[$this->statusKey], $vars[$this->moduleKey], $vars['is_new'], $vars['refs'], $vars['rdf_mapping'], $vars['repository']);
    if ($this->nameKey != $this->idKey) {
      unset($vars[$this->idKey]);
    }
    return entity_var_json_export($vars, $prefix);
  }

}
