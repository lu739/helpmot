## PHP >= 8.2
## NODE >= 20

## Запускаем в докере
`sail up -d`

## Накатываем миграции и заполняем бд
`sail php artisan migrate:fresh --seed`  
`sail php artisan migrate:refresh --seed`  

## Для от ображения шаблонов нужно собрать статику 
запускаем vite server
`npm run dev`
или если это prod то запускаем
`npm run build`


## Запуск без докера локально

1. создаём файл .env  в корне проекта и копируем содержимое из файла .env.example 
2. заполняем знечениями в файле .env
3. устанавливаем зависимости для сервера `composer install`
4. устанавливаем зависимости для статики `npm install`
5. или накатываем миграции просто `php artisan migrate`, или сразу наполняем данными `sail php artisan migrate:fresh --seed`
6.  (необязательно) пересобрать миграции заново и заполнить данными `sail php artisan migrate:refresh --seed`
7. сгенерировать ключ `php artisan key:generate`
8. запустить сервер `php srtisan serve`
9. если нужно также запустить статику `npm run dev`
10. если отслеживать статику не нужно то `npm run build`


## Redis

1. Установка на сервер на линух sudo apt install redis-server
2. Открыть конфигурационный файл sudo nano /etc/redis/redis.conf и заменить supervised systemd (на локали этого не нужно)
3. Перезапуск службы sudo systemctl restart redis.service
4. Проверить работу sudo systemctl status redis. Если она выключена - включить sudo systemctl enable redis
5. Запустить консоль redis-cli. Написать ping - в ответ вернется PONG. Вы великолепны
6. Прописать в .env 
    REDIS_MAX_ORDER_LOCATION= Это количество записей локации, которые будут сохраняться в редис прежде чем будут отправлены в бд (на старте это 5)
    CACHE_STORE=redis
    CACHE_PREFIX=cache:
    REDIS_PREFIX=redishelpmot:

    REDIS_CLIENT=predis
    REDIS_HOST=redis
    REDIS_PASSWORD=null
    REDIS_PORT=6379



# Генерация сваггер документации
sail artisan l5-swagger:generate - локаль
php artisan l5-swagger:generate - сервер
