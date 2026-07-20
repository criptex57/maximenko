# Ольга Максименко — WordPress-портфолио

Кастомная WordPress-тема для дизайнера интерьеров: тёмная премиальная визуальная система, адаптивная журнальная сетка, проекты, услуги, отзывы, интерактивное сравнение, анимации GSAP, плавный скролл Lenis и слайдер Swiper.

## Быстрый запуск

Требования: Docker Desktop и Docker Compose v2.

    docker compose up -d

При первом запуске проект автоматически создаёт MySQL-базу, устанавливает WordPress, активирует тему, настраивает постоянные ссылки и импортирует 6 проектов, 7 услуг, 5 отзывов, страницы, меню и 15 изображений.

Сайт: [http://localhost:8080](http://localhost:8080)  
Админ-панель: [http://localhost:8080/wp-admin/](http://localhost:8080/wp-admin/)

Учётные данные по умолчанию:

- логин: admin;
- пароль: change-me-now.

Перед использованием вне локальной среды обязательно измените пароль. Все значения можно переопределить в файле .env:

    cp .env.example .env

После изменения WP_URL порт в нём должен совпадать с WORDPRESS_PORT.

## Управление контейнерами

Остановить:

    docker compose stop

Запустить снова:

    docker compose start

Остановить и удалить контейнеры, сохранив данные:

    docker compose down

Полностью пересоздать чистую демо-установку:

    docker compose down -v
    docker compose up -d

Команда с флагом -v удаляет локальную базу и загруженные в WordPress файлы.

## Frontend-разработка

Исходники находятся в wp-content/themes/olga-maksimenko/assets/src.

    cd wp-content/themes/olga-maksimenko
    npm install
    npm run build

Режим разработки:

    npm run dev

Готовые файлы записываются в assets/dist/app.css и assets/dist/app.js. Они уже включены в проект, поэтому для обычного запуска Docker Node.js не требуется.

## Где редактируется контент

В WordPress:

- **Проекты** — название, описание, обложка, категория, город, площадь, год, задача, решения и визуальная галерея с сортировкой фотографий;
- **Проекты → Проекты на главной** — выбор и порядок до шести проектов для горизонтального блока главной страницы;
- **Услуги** — описание, состав и срок;
- **Отзывы** — клиент, текст, тип объекта и город;
- **Страницы** — «Обо мне», «Контакты» и политика конфиденциальности;
- **Настройки → Настройки портфолио** — имя, специализация, hero-текст, блок «Обо мне», показатели, контакты, социальные сети, этапы, SEO description и email для заявок;
- **Внешний вид → Меню** — навигация сайта.

Изображения проектов редактируются через стандартную медиатеку и изображение записи. Фотографии галереи добавляются и сортируются визуально в блоке «Данные для сайта».

## Демо-контент

Демо импортируется автоматически только один раз. Исходные изображения лежат в wp-content/themes/olga-maksimenko/demo/. Для полного повторного импорта используйте чистую установку через docker compose down -v.

## Форма заявки

Форма отправляется в собственный WordPress REST endpoint /wp-json/olga/v1/contact. Реализованы клиентская и серверная валидация, очистка данных, honeypot, ограничение частоты, согласие с политикой, доступный modal и focus trap.

В локальном окружении WordPress не подключён к SMTP: endpoint возвращает успешный результат для тестирования интерфейса. Для продакшена подключите SMTP-плагин или почтовый транспорт сервера.

## phpMyAdmin

Запускается только по отдельному профилю:

    docker compose --profile tools up -d phpmyadmin

Адрес: [http://localhost:8081](http://localhost:8081).

## Структура

    .
    ├── docker-compose.yml
    ├── docker/init-wordpress.sh
    ├── .env.example
    └── wp-content/themes/olga-maksimenko/
        ├── assets/src/
        ├── assets/dist/
        ├── demo/
        ├── inc/
        ├── front-page.php
        ├── archive-project.php
        ├── single-project.php
        ├── single-service.php
        ├── header.php
        ├── footer.php
        ├── functions.php
        ├── package.json
        └── vite.config.js

## Проверки

    docker compose config --quiet
    cd wp-content/themes/olga-maksimenko
    npm audit
    npm run build
    docker compose exec wordpress sh -lc "find wp-content/themes/olga-maksimenko -name '*.php' -print0 | xargs -0 -n1 php -l"

Тема учитывает клавиатурное управление, prefers-reduced-motion, lazy loading изображений, responsive WordPress images и адаптивные состояния от 375 px.
