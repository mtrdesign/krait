<p align="center">
    <img src="https://mtrdesign.github.io/krait/assets/krait-full-logo.svg"  alt="image" width="400" height="auto">
</p>

<p align="center">
<a href="https://packagist.org/packages/mtrdesign/krait"><img src="https://img.shields.io/packagist/dt/mtrdesign/krait" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/mtrdesign/krait"><img src="https://img.shields.io/packagist/v/mtrdesign/krait" alt="Latest Stable Version"></a>
</p>

Krait is a powerful Laravel package that simplifies the creation of Ajax Dynamic DataTables. It automates the initial structure development of both
front-end and back-end resources via simple CLI commands.

# Krait Package Monorepo
To ensure consistent versioning and enhance the core front-end components development, along with the PHP package we’ve introduced a dedicated front-end
library called `krait-ui`. So the final package consists of two parts, included in this monorepo: 

* `/krait-ui`: The VueJS-based DataTable UI kit that includes all front-end components.
* `/krait`: The main Laravel package that includes all back-end features.

## Official Documentation

More information about the package usage can be found on the [Krait Docs Website](https://mtrdesign.github.io/krait/).

# Quickstart
Krait's installation process is pretty simple, you can follow this guide to get everything configured quickly
and then review the detailed official docs.

## Installation

You can install Krait via Composer by running:

```bash
composer require mtrdesign/krait
```

Then, you should finish the installation by running:

```bash
php artisan krait:install
```

It's that simple! Now you have all the JS and PHP dependencies installed and configured.

**And One Final Step...**

To successfully run the front-end library, you should tell the Vue to use the `krait-ui` plugin.

```js
// Importing the Krait Vue plugin
import Krait from "@mtrdesign/krait-ui";

// Importing the autogenerataed tables module
import tables from './components/tables'

/**
 * ... Here you initialise your VueApp ...
 * 
 * const app = Vue.createApp({});
 */

app.use(Krait, {
    tables: tables
});
```

## Usage
To create your first table, run the following command:
```bash
php artisan krait:table MyFirstTable
```

This command will create three resources:
* `/app/Tables/MyFirstTable.php` - the where you define all columns and processing callbacks of your table
* `/app/Http/Controllers/Tables/MyFirstTableController.php` - the controller that fetches the table content
* `/resources/js/components/tables/MyFirstTable.vue` - the front-end representation of the table


**IMPORTANT:**
For consistency, all Table Classes in `app/Tables` should end on `Table` in order to be registered correctly.

For more details around the installation and the tables definition, please check the official
`installation & usage` guide.

## Contribution

Don't hesitate to raise issues and propse improvements!
Any help is welcome!

You can find more information about the package development process in the monorepo projects:
* `/krait-ui` - for the front-end functionality
* `/krait` - for the back-end functionality

More contribution details can be found in the [Krait Docs Website](https://mtrdesign.github.io/krait/contributions).

## License

Krait is open-sourced software licensed under the [MIT license](LICENSE.md).
