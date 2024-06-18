# User Permissions API

API для управления правами пользователей и группами. Проект включает тесты на PHPUnit.

## Требования

- PHP 8.3 и выше
- MySQL

## Установка

1. Клонируйте репозиторий:

    ```sh
    git clone https://github.com/yourusername/your-repo.git
    cd your-repo
    ```

2. Установите зависимости через Composer:

    ```sh
    php composer.phar install
    ```

3. Создайте файл `db_config.php` с настройками подключения к базе данных:

    ```php
    <?php
    define('DB_HOST', 'your_db_host');
    define('DB_NAME', 'your_db_name');
    define('DB_USER', 'your_db_user');
    define('DB_PASS', 'your_db_password');

    function getDB(): PDO
    {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    }
    ?>
    ```

4. Запустите сервер:

    ```sh
    php -S localhost:8000
    ```

## Использование API

### Добавить пользователя в группу

```sh
curl -X POST -H "Content-Type: application/json" -d '{"action":"addUserToGroup", "userId":1, "groupId":1}' http://localhost:8000/api.php
```
Удалить пользователя из группы
```sh
curl -X POST -H "Content-Type: application/json" -d '{"action":"removeUserFromGroup", "userId":1, "groupId":1}' http://localhost:8000/api.php
```
Получить права пользователя
```sh
curl -X GET http://localhost:8000/api.php?userId=1
```
Тестирование
Запустите тесты:

```sh
php8.3 vendor/bin/phpunit --bootstrap vendor/autoload.php tests/UserTest.php
```
Структура проекта
```txt
project/
│
├── api.php
├── User.php
├── db_config.php
├── composer.json
└── tests/
    └── UserTest.php
```