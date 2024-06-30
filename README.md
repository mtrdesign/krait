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

# Quickstart

## Installation

You can quickly install Krait via Composer by running:

```bash
composer require mtrdesign/krait
```

Then, you can finish the installation by running:

```bash
php artisan krait:install
```

It's that simple! Now you have all the JS and PHP dependencies installed and configured.

**One Final Step...**

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

## Official Documentation

More information about the package usage can be found on the [Krait Docs Website](https://mtrdesign.github.io/krait/).

## Contribution

The contribution guide can be found in the [Krait Docs Website](https://mtrdesign.github.io/krait/contributions).

## License

Krait is open-sourced software licensed under the [MIT license](LICENSE.md).
