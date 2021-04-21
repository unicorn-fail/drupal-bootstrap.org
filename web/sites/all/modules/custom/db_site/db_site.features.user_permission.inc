<?php
/**
 * @file
 * db_site.features.user_permission.inc
 */

/**
 * Implements hook_user_default_permissions().
 */
function db_site_user_default_permissions() {
  $permissions = array();

  // Exported permission: 'administer git clones'.
  $permissions['administer git clones'] = array(
    'name' => 'administer git clones',
    'roles' => array(
      'administrator' => 'administrator',
    ),
    'module' => 'git_clone',
  );

  // Exported permission: 'create git clones'.
  $permissions['create git clones'] = array(
    'name' => 'create git clones',
    'roles' => array(
      'administrator' => 'administrator',
    ),
    'module' => 'git_clone',
  );

  // Exported permission: 'delete git clones'.
  $permissions['delete git clones'] = array(
    'name' => 'delete git clones',
    'roles' => array(
      'administrator' => 'administrator',
    ),
    'module' => 'git_clone',
  );

  // Exported permission: 'update git clones'.
  $permissions['update git clones'] = array(
    'name' => 'update git clones',
    'roles' => array(
      'administrator' => 'administrator',
    ),
    'module' => 'git_clone',
  );

  // Exported permission: 'view git clones'.
  $permissions['view git clones'] = array(
    'name' => 'view git clones',
    'roles' => array(
      'administrator' => 'administrator',
    ),
    'module' => 'git_clone',
  );

  return $permissions;
}
