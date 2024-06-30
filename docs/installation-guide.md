# Installation Guide

## Dependencies installation
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

## Front-end Configuration
After we install all required packages, we should attach the `Krait` plugin to our Vue application.

Take a look at the example `app.js` file. And implement the code in your project. 

```js title="resources/js/app.js"
// Importing the Krait Vue plugin
import Krait from "@mtrdesign/krait-ui";

// Importing the autogenerataed tables module
import tables from './components/tables'

/**
 * ... Here you initialise your VueApp ...
 * 
 *     const app = Vue.createApp({});
 */

app.use(Krait, {
    tables: tables
});
```

The `php artisan krait:install` command from the previous section creates the `resources/js/components/tables`
directory and its `index.js` file for listing all available tables.

???+ Warning "Automatically generated resource in `/resource/js/components/tables`"
    You should not update the `resources/js/components/tables/index.js` file manually.
    All updates there are handled by the back-end functionality and the CLI commands.
    
    If you have updated it for some reason, please run the following command to fix the module state:
    
    ```sh
    php artisan krait:refresh
    ```
