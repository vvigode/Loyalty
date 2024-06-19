# User Permissions API

API для управления правами пользователей и группами. Проект включает тесты на PHPUnit.

## Требования

- PHP 8.3 и выше
- MySQL

## Установка

1. Клонируйте репозиторий:

```sh
    git clone https://github.com/vvigode/Loyalty.git
    cd Loyalty
```

2. Установите зависимости через Composer:

```sh
    php composer.phar install
```

3. Измените файл `db_config.php` для подключения к базе данных:

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

4. Полный список необходимых таблиц и записей.
Убедитесь, что все таблицы и записи существуют, как в приведенном ниже примере:

```sql
    -- Таблица пользователей
    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL
    );

    -- Таблица групп
    CREATE TABLE groups (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL
    );

    -- Таблица прав
    CREATE TABLE permissions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL
    );

    -- Таблица прав групп
    CREATE TABLE group_permissions (
        group_id INT,
        permission_id INT,
        FOREIGN KEY (group_id) REFERENCES groups(id),
        FOREIGN KEY (permission_id) REFERENCES permissions(id)
    );

    -- Таблица групп пользователей
    CREATE TABLE user_groups (
        user_id INT,
        group_id INT,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (group_id) REFERENCES groups(id)
    );

    -- Таблица временно заблокированных прав
    CREATE TABLE blocked_users (
        user_id INT,
        permission_id INT,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (permission_id) REFERENCES permissions(id)
    );

    -- Пример записи в таблице пользователей
    INSERT INTO users (id, username) VALUES (1, 'testuser');

    -- Пример записи в таблице групп
    INSERT INTO groups (id, name) VALUES (1, 'testgroup');

    -- Пример записи в таблице прав
    INSERT INTO permissions (id, name) VALUES (1, 'send_messages');

    -- Пример записи в таблице group_permissions
    INSERT INTO group_permissions (group_id, permission_id) VALUES (1, 1);
```

5. Запустите сервер:

```sh
    php -S localhost:8000
```

## Использование API

### Добавить пользователя в группу

```sh
    curl -X POST -H "Content-Type: application/json" -d '{"action":"addUserToGroup", "userId":1, "groupId":1}' http://localhost:8000/api.php
```

### Удалить пользователя из группы

```sh
    curl -X POST -H "Content-Type: application/json" -d '{"action":"removeUserFromGroup", "userId":1, "groupId":1}' http://localhost:8000/api.php
```

### Получить права пользователя

```sh
    curl -X GET http://localhost:8000/api.php?userId=1
```

## Тестирование

### Запустите тесты:

```sh
    php8.3 vendor/bin/phpunit --bootstrap vendor/autoload.php tests/UserTest.php
```

## Структура проекта

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