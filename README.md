# LosReg Module for SEAT

The `LosReg` module is an extension for SEAT that allows administrators to view members of the EVE Online corporation **SovNarKom** who have not yet registered in SEAT. It fetches corporation member data from the EVE Online ESI API and compares it with registered SEAT users to display any discrepancies.

---

## Features
- Fetches corporation members using the official EVE Online ESI API.
- Retrieves registered users from the SEAT database.
- Displays a list of unregistered corporation members in the administrative dashboard.
- Designed to integrate seamlessly with SEAT using Laravel's modular architecture.

---

## Folder Structure
```plaintext
modules/
└── los-reg/
    ├── composer.json               # Module metadata and autoload configuration
    ├── ServiceProvider.php         # Registers routes and views for the module
    ├── routes/
    │   └── web.php                 # Defines module routes
    ├── Http/
    │   ├── Controllers/
    │   │   └── LosRegController.php # Contains logic for fetching and comparing data
    ├── resources/
    │   └── views/
    │       └── unregistered.blade.php # Blade template for displaying unregistered users
```

---

## Installation

1. Place the `los-reg` folder into your SEAT `modules` directory.
2. Add the following to the module's `composer.json` file:
    ```json
    {
        "extra": {
            "laravel": {
                "providers": [
                    "Modules\\LosReg\\ServiceProvider"
                ]
            }
        }
    }
    ```
3. Run the following command to update the autoloader:
    ```bash
    composer dump-autoload
    ```
4. Add your ESI token to the `.env` file:
    ```env
    EVE_ESI_TOKEN=Your_ESI_Token
    ```
5. Access the module via the route:
    ```
    /los-reg/unregistered
    ```

---

## Technical Details

### ServiceProvider
The `ServiceProvider` is responsible for registering the module's routes and views:

```php
<?php

namespace Modules\LosReg;

use Illuminate\Support\ServiceProvider;

class ServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'LosReg');
    }

    public function register()
    {
        //
    }
}
```

### Routes
The module defines a single route for viewing unregistered members:
```php
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'can:administrator'])
    ->namespace('Modules\LosReg\Http\Controllers')
    ->prefix('los-reg')
    ->group(function () {
        Route::get('/unregistered', 'LosRegController@showUnregistered')->name('los-reg.unregistered');
    });
```

### Controller
The `LosRegController` handles fetching data from the EVE Online API and SEAT:
```php
namespace Modules\LosReg\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Seat\Web\Models\User;

class LosRegController extends Controller
{
    public function showUnregistered()
    {
        $corporationMembers = $this->fetchCorporationMembers();
        $registeredUsers = $this->fetchRegisteredUsers();
        $unregisteredMembers = array_diff($corporationMembers, $registeredUsers);

        return view('LosReg::unregistered', compact('unregisteredMembers'));
    }

    private function fetchCorporationMembers()
    {
        $client = new Client();
        $url = "https://esi.evetech.net/latest/corporations/123456789/members/";
        $response = $client->get($url, [
            'headers' => [
                'Authorization' => "Bearer " . env('EVE_ESI_TOKEN'),
                'User-Agent' => 'LosRegModule/1.0 (contact@example.com)'
            ],
        ]);
        return json_decode($response->getBody(), true);
    }

    private function fetchRegisteredUsers()
    {
        return User::all()->pluck('name')->toArray();
    }
}
```

### Blade Template
The `unregistered.blade.php` displays the list of unregistered corporation members:
```blade
@extends('web::layouts.grids.12')

@section('title', 'Без регистрации')

@section('page_header', 'Без регистрации')

@section('full')
    <div class="card">
        <div class="card-header">
            <h3>Пользователи без регистрации</h3>
        </div>
        <div class="card-body">
            @if (count($unregisteredMembers) > 0)
                <ul>
                    @foreach ($unregisteredMembers as $member)
                        <li>{{ $member }}</li>
                    @endforeach
                </ul>
            @else
                <p>Все пользователи зарегистрированы!</p>
            @endif
        </div>
    </div>
@endsection
```

---

## Usage
Once installed, navigate to `/los-reg/unregistered` to view the list of unregistered corporation members. Ensure your ESI token is valid and has the necessary scopes to fetch member data.

---

## Requirements
- SEAT version supporting Laravel modules
- PHP 7.4+
- EVE Online ESI Token with appropriate scopes
- `guzzlehttp/guzzle` package installed

---

For any issues or enhancements, feel free to contribute or report via the repository.
