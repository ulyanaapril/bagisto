<?php
namespace Deployer;

require 'recipe/laravel.php';

// Configuration

set('repository', 'git@github.com:PHPtoday-ru/laravel-deployer-demo.git');
set('git_tty', true); // [Optional] Allocate tty for git on first deployment
add('shared_files', [
    '.env'
]);
add('shared_dirs', [
    'storage'
]);
add('writable_dirs', [
    'bootstrap/cache',
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
]);

// Hosts

host('192.168.64.77')
    ->stage('production')
    ->user('deployer')
    ->set('deploy_path', '/var/www/laravel-deployer-demo');

// Tasks

desc('Restart PHP-FPM service');
task('php-fpm:restart', function () {
    // The user must have rights for restart service
    // /etc/sudoers: username ALL=NOPASSWD:/bin/systemctl restart php-fpm.service
    run('sudo systemctl restart php7.2-fpm.service');
});
after('deploy:symlink', 'php-fpm:restart');

task('upload:env', function () {
    upload('.env.production', '{{deploy_path}}/shared/.env');
})->desc('Environment setup');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'upload:env',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'artisan:storage:link',
    'artisan:view:clear',
    'artisan:cache:clear',
    'artisan:config:cache',
//    'artisan:migrate',
    'deploy:symlink',
    'php-fpm:restart',
    'deploy:unlock',
    'cleanup',
]);