@servers(['remote' => ['unitiweb2'], 'local' => '127.0.0.1'])

@task('deploy', ['on' => 'remote'])

    @if ($beta || $stage || $prod)
        @if ($beta)
            cd /srv/beta.unitibudget.com/app
            git fetch --all
            git checkout {{ $branch ?? 'master' }}
            git reset --hard
            git pull origin {{ $branch ?? 'master' }}
            composer install
            npm install
            php artisan migrate --force
        @endif
    @else
        echo 'No valid stage was entered'
    @endif

@endtask
