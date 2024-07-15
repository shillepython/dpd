@servers(['scum' => 'u786890756@77.37.37.155 -p 65002'])


@task('deploy', ['on' => 'scum'])
cd /home/u786890756/domains/obtaining-dl.online
set -e
echo "Deploying..."
git pull origin main
php artisan down
php composer.phar install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
php artisan up
echo "Done!"
@endtask
