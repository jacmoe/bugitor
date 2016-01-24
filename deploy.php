<?php
require_once __DIR__ . '/deployer/recipe/yii2-app-advanced.php';
require_once __DIR__ . '/deployer/recipe/yii-configure.php';
require_once __DIR__ . '/deployer/recipe/in-place.php';

if (!file_exists (__DIR__ . '/deployer/stage/servers.yml')) {
  die('Please create "' . __DIR__ . '/deployer/stage/servers.yml" before continuing.' . "\n");
}
serverList(__DIR__ . '/deployer/stage/servers.yml');
set('repository', '{{repository}}');

set('default_stage', 'production');

set('keep_releases', 2);

set('writable_use_sudo', false); // Using sudo in writable commands?

task('deploy:configure_composer', function () {
  $stage = env('app.stage');
  if($stage == 'dev') {
    env('composer_options', 'install --verbose --no-progress --no-interaction');
  }
})->desc('Configure composer');

task('deploy:build_assets', function () {
   runLocally('gulp build');
   upload(__DIR__ . '/frontend/web/css', '{{release_path}}/frontend/web/css');
   upload(__DIR__ . '/backend/web/css', '{{release_path}}/backend/web/css');
   upload(__DIR__ . '/frontend/web/js', '{{release_path}}/frontend/web/js');
   upload(__DIR__ . '/backend/web/js', '{{release_path}}/backend/web/js');
   upload(__DIR__ . '/frontend/web/fonts', '{{release_path}}/frontend/web/fonts');
   upload(__DIR__ . '/backend/web/fonts', '{{release_path}}/backend/web/fonts');
})->desc('Build assets');

// uncomment the next two lines to run migrations
after('deploy:symlink', 'deploy:run_migrations');
after('inplace:configure', 'inplace:run_migrations');

before('deploy:vendors', 'deploy:configure_composer');
before('inplace:vendors', 'deploy:configure_composer');
before('deploy:symlink', 'deploy:configure');

after('deploy:run_migrations', 'deploy:build_assets');
