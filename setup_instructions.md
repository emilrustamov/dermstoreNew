# Инструкции по установке

1. **Клонируйте репозиторий:**
    ```sh
    cd /var/www/
    git clone https://github.com/emilrustamov/dermstoreNew.git
    ```

2. **Перейдите в каталог проекта:**
    ```sh
    cd dermstoreNew
    ```

3. **Установите Composer, если он не установлен:**
    ```sh
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    mv composer.phar /usr/local/bin/composer
    ```

4. **Установите npm, если он не установлен:**
    ```sh
    curl -L https://www.npmjs.com/install.sh | sh
    ```

5. **Установите необходимые зависимости:**
    ```sh
    npm install
    composer install
    ```

6. **Настройте переменные окружения:**
    ```sh
    cp .env.example .env
    ```
    - Добавьте необходимые переменные окружения, как указано в файле `.env.example`.

7. **Сгенерируйте ключ приложения:**
    ```sh
    php artisan key:generate
    ```

8. **Запустите необходимые миграции или сидеры:**
    ```sh
    php artisan migrate
    php artisan db:seed
    ```

9. **Соберите приложение:**
    ```sh
    npm run build
    ```