@servers(['vigil' => 'u180353749@45.84.204.18 -p 65002'])


@task('deploy', ['on' => 'vigil'])
cd /home/u180353749/domains/venture-vigil.com
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
