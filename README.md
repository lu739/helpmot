## Запускаем в докере
sail up -d

## Накатываем миграции и заполняем бд
sail php artisan migrate:fresh --seed  
sail php artisan migrate:refresh --seed  
