<p align="center">
    <img src="https://mtrdesign.github.io/krait/assets/krait-full-logo.svg"  alt="image" width="400" height="auto">
</p>

# Krait: Where Laravel Meets Dynamic Tables
### The Perfect Trinity: Bootstrap + VueJS + Laravel = 🚀

Ever found yourself in the DataTable dilemma? When building a Single Page Application with popular frameworks
like MUI, implementing dynamic tables is straightforward - just grab their ready-made components and you're good to go.

But here's the catch: What if you don't want to transform your entire project into a SPA just to get your hands on a fancy table component?
Importing an entire framework for a single feature feels like using a sledgehammer to crack a nut.

Imagine this scenario:
You're deep into a Laravel project that elegantly combines Blade templates with sprinkles of VueJS components for interactive features.
Then comes the need for a DataTable. Your options?

1. Go big: Import an entire framework for VueJS
2. Go "not so" big: Add a VueJS-specific DataTable plugin

Here's where it gets interesting - both options miss out on Laravel's powerful built-in features. Think API Resources, Pagination, Sorting -
all the good stuff you already have at your fingertips. That's where Krait steps in, embracing a simple yet powerful philosophy:
"Why add extra complexity when Laravel already provides everything you need?"

## Abstract
Krait is a Laravel package that automates the creation of Ajax Dynamic DataTables by combining VueJS components with Laravel functionalities.
With a single command-line interface operation, it efficiently generates and connects all necessary front-end and back-end resources, including
controllers, routes, Vue components, and table definitions, providing developers with an out-of-the-box working solution. Following Laravel's conventions,
developers can define and customize table structures from the back-end, including column configurations and data preprocessing, while maintaining the flexibility
to fine-tune the front-end presentation through the generated VueJS components.


## Key Features
...

## Getting Started with Krait
Krait is an open-source solution available on [GitHub](https://github.com/mtrdesign/krait). While it consists of two packages - a front-end package - [@mtrdesign/krait-ui
](https://www.npmjs.com/package/@mtrdesign/krait-ui)
and a back-end package - [mtrdesign/krait](https://packagist.org/packages/mtrdesign/krait), the installation process is streamlined through the main Laravel package.

To get started, simply install the package via Composer:

```sh
composer install mtrdesign/krait
```

Then run the Laravel Artisan installation command:

```sh
php artisan krait:install
```

This will automatically install and configure all required resources in their appropriate locations.

Let's create our first table - a table for cats! Generate it with a single command:

```sh
php artisan krait:table CatsTable
```

This command generates three key files, following the MVC pattern:

- **Model** (`/app/Tables/CatsTable.php`): Defines the table structure and column configurations
- **Controller** (`/app/Http/Controllers/Tables/CatsTableController.php`): Handles data retrieval logic
- **View** (`/resources/js/components/tables/CatsTable.js`): VueJS component for front-end visualization, available as `cats-table` in your Blade templates

That's all it takes to create a fully functional dynamic DataTable! Everything is automatically handled behind the scenes, including:
- API routes and authentication
- Data modeling and retrieval
- Pagination and sorting capabilities
- Table configurations
  - Column visibility
  - Column ordering
  - Column sizing
  - Preview settings

No additional setup required - you're ready to start customizing your table just by using the `<cats-table>` VueJS component.

### Customizing the Table
