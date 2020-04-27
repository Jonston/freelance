#### Клонирование репозитория
    git clone https://github.com/Jonston/freelance.git
#### Установка пакетов
    composer install
#### Запуск миграций
    php phpmig migrate
#### Сохранение данных из api в базу (база в корне репозитория)
    php console.php projects:parse
#### Роут для списка проектов '<base_url>/projects/'
#### Роут для чарта '<base_url>/projects/info'
