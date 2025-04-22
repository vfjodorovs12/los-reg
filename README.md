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
