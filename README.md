# los-reg
Модуль los-reg добавляет административное меню для отображения списка членов корпорации SovNarKom, которые еще не зарегистрировались в SEAT. Модуль:

Использует API EVE Online (ESI) для получения списка членов корпорации.
Сравнивает этот список с зарегистрированными пользователями SEAT.
Отображает незарегистрированных пользователей в административной панели.
Структура модуля
Code
modules/
└── los-reg/
    ├── composer.json
    ├── ServiceProvider.php
    ├── routes/
    │   └── web.php
    ├── Http/
    │   ├── Controllers/
    │   │   └── LosRegController.php
    ├── resources/
    │   └── views/
    │       └── unregistered.blade.php
