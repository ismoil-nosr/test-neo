# Установка

### Зависимости
* Docker & docker-compose
* PHP 8.2
* Composer

Запускаем команды:
```bash
git clone

cd test-neo

composer i

php artisan key:generate
php artisan migrate --seed
```

### Запуск в докере
```bash
make init
```

### Ссылки
- [Коллекция Postman](https://documenter.getpostman.com/view/23952511/2sA2rFQevc)
