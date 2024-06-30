# Installation Guide

## Packages installation
You can install Krait via Composer by running:
```sh
composer require mtrdesign/krait
```

Then, you should finish the installation by running:
```sh
php artisan krait:install
```

???+ success "Successful installation"
    After running the above two commands, you should have all dependencies installed.
    If you experience any issues, please check if you can see them in the following files:
    
    ```json title="package.json"
        {
            ...
            "dependencies": {
                ...
                "@mtrdesign/krait-ui": "^1.0.0"
            }
        }
    ```

    ```json title="composer.json"
        {
            ...
            "require": {
                ...
                "mtrdesign/krait": "^1.0.0"
            }
        }
    ```

    **Important Note**: Both package versions should exactly match each other.

## Front-end installation
