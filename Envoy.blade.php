@servers(['remote' => ['unitiweb2'], 'local' => '127.0.0.1'])

@task('deploy', ['on' => 'remote'])

    @if ($stage)
        @if ($stage === 'beta')
            cd /srv/beta.unitibudget.com/app
            git fetch --all
            git checkout {{ $branch ?? 'master' }}
            git pull origin {{ $branch ?? 'master' }}
            composer install
            npm install
            php artisan migrate --force
        @endif
    @else
        echo 'No valid stage was entered'
    @endif

@endtask
