# Setup Application
1. composer install
2. php artisan migrate --seed --seeder=DatabaseSeeder
3. php artisan passport:install
4. php artisan storage:link
5. php artisan serve