<?php
/**
 * @file
 * Local settings.
 */

$base_url = 'https://bootstrap.apps.sysops.tag1.io';

// Database.
$databases['default']['default'] = [
  'database' => getenv("DB_NAME"),
  'username' => getenv("DB_USERNAME"),
  'password' => getenv("DB_PASSWORD"),
  'host' => getenv("DB_HOST"),
  'port' => '3306',
  'driver' => 'mysql',
  'charset' => 'utf8mb4',
  'collation' => 'utf8mb4_general_ci',
  'prefix' => '',
];

$conf['advagg_skip_far_future_check'] = TRUE;

$conf['jquery_update_jquery_version'] = '3.1';

// Composer.
$conf['composer_manager_autobuild_file'] = 0;
$conf['composer_manager_autobuild_packages'] = 0;
$conf['composer_manager_file_dir'] = '../';
$conf['composer_manager_vendor_dir'] = '../vendor';

// Binary paths.
$conf['drush_binary'] = '/usr/local/bin/drush';
$conf['git_binary'] = '/usr/bin/git';

// Git Clone module.
$conf['file_git_clone_path'] = getenv("GIT_CLONE_PATH");
