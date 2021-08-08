<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'deployer.sakawawa.me');

// Project repository
set('repository', 'git@github.com:STUTuna/laravel-corp.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);


// Hosts

host('sakawawa.me')

    ->user('root')
    ->port(22)
    ->configFile('C://Users/s15113114/.ssh/config')
//    ->configFile('~/.ssh/config')
    ->identityFile('C://Users/s15113114/.ssh/id_rsa')
    ->set('deploy_path', '/www/wwwroot');
//host('Sakawawa')
//    ->set('deploy_path', '/www/wwwroot');

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});
task('test', function () {
    writeln('Hello world');
});
task('deploy:done', function () {
    write('Deploy done!');
});
before('deploy:symlink', 'artisan:migrate');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
after('deploy', 'deploy:done');
