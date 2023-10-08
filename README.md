1. composer install --ignore-platform-reqs
2  copy .env.example to .env and  change .env database config to point yout database
3. php artisan key:generate
4. php artisan migrate
5. run "php artisan queue:work" to simulate the queue working