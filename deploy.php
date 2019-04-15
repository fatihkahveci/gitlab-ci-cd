<?php
namespace Deployer;

require 'recipe/symfony4.php';


// Project name
set('application', 'my_project');

// Project repository
set('repository', 'git@gitlab.com:REPO_URL.git');

// Hosts

host('192.168.1.1')
    ->user("root")
    ->port(22)
    ->set('http_user', 'nginx')
    ->set('deploy_path', '/home/ci-cd');


// Tasks

task('copy:env', function () {
    run('cd {{release_path}} && cp .env.prod .env');
});
task('update:database', function() {
    run('cd {{release_path}} && php bin/console doctrine:schema:update --force');
});

after('deploy:update_code', 'copy:env');
before('deploy:symlink', 'update:database');


// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

