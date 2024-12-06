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

### Dynamic Front-End Capabilities

- **Resizable Columns**: All columns are resizable by default, with the option to set fixed-width columns through backend configuration
- **Column Visibility Control**: Users can show/hide columns via an intuitive dropdown interface
- **Column Reordering**: Drag-and-drop functionality allows users to customize column arrangement
- **AJAX Pagination**: Seamless data loading with adjustable items per page
- **Smart Sorting**: Built-in column sorting with the ability to disable sorting for specific columns

### User Experience

- **Persistent Settings**: All user preferences (column width, visibility, order) are automatically saved and restored
- **Zero Page Reloads**: Pure AJAX-based interactions for smooth user experience
- **Bootstrap Integration**: Leverages Bootstrap's styling for consistent look and feel
- **Responsive Design**: Adapts to different screen sizes and devices

### Developer-Friendly Features

- **Single Command Generation**: Create complete table implementations with one Artisan command
- **MVC Pattern Compliance**: Automatically generates organized code following Laravel best practices
- **Built-in Laravel Integration**:
  - Seamless API Resource utilization
  - Native Laravel pagination support
  - Built-in authentication handling
  - Automatic route registration

### Backend Flexibility

- **Customizable Data Processing**: Full control over data retrieval and transformation
- **Column Configuration API**: Comprehensive options for defining column behavior
- **Security Integration**: Leverages Laravel's authentication and authorization systems
- **Performance Optimization**: Efficient data loading and processing mechanisms

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


__One last step...__

The final part is to add Krait's plugin to your VueJS application. In the `app.js` (the JS entrypoint of the application)
add the following lines:

```js
// Import Krait JS
import Krait from "@mtrdesign/krait-ui";
import tables from "./components/tables";

// Import Krait Styles
import "@mtrdesign/krait-ui/styles";

// Inject Krait in your VueJS application
const app = Vue.createApp({});
app.use(Krait, {
    tables: tables,
});
```

**Now let's create our first table**

_Looking for Example Table Inspiration? Our Feline Friends Have the Answer! 🐱_


Generate it with a single command:

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

The default table template starts with a basic structure of two columns and one additional attribute:

```php
# /app/Tables/CatsTable.php
...
class CatsTable {
  ...
  function initColumns(): void
  {
    $this->column(
      name: 'my_first_column',
      label: 'My First Column',
      process: fn(mixed $resource) => 'This content is processed.'
    );

    $this->column(
      name: 'some_field',
      label: 'Resource Field',
    );
  }

  function additionalData(mixed $resource): array
  {
    return [
      'additional_prop' => 'Krait is awesome!',
    ];
  }
}
```

All columns are defined in the `initColumns` function.
Now, let's transform this into a more practical example for our cat management system.
Let's add a `name`, `breed`, `country`, `profession` (yes - we all know that the cats are lazy but let's assume that they can work!).

```
# /app/Tables/CatsTable.php
...
class CatsTable {
  ...
  function initColumns(): void
  {
    $this->column(
      name: 'name',
      label: 'Name',
      sortable: true,
      process: fn($cat) => ucfirst($cat->name)
    );

    $this->column(
      name: 'breed',
      label: 'Breed',
      sortable: true,
    );

    $this->column(
      name: 'country_code',
      label: 'Country',
      sortable: true,
    );

    $this->column(
      name: 'profession',
      label: 'Job Title',
      sortable: true,
    );
  }
...
}
```

The `name` field specifies the property name in the data records, while the `label` field defines the display title shown in the user interface.

To enhance the functionality, we'll fetch the actual country names from a third-party API.
Let's add a new `attribute` function to our `CatsTable` class as follows:

```php
# /app/Tables/CatsTable.php

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
...
class CatsTable {
  ...
  function getCountryCodeKraitAttribute(mixed $cat): string
  {
    return Cache::remember("country_{$cat->country_code}", 3600, function() use ($cat) {
      $client = new Client();

      try {
        $response = $client->get(
            "https://restcountries.com/v3.1/alpha/{$cat->country_code}"
        );
        $data = json_decode($response->getBody(), true);
        return $data[0]['name']['common'] ?? $cat->country_code;
      } catch (Exception $e) {
        return $cat->country_code;
      }
    });
  }
}
```

This example demonstrates how to create dynamic data pipelines in the table class, which has many practical applications in real-world scenarios.

Let's examine our `CatsTableController` class in detail. By default, the controller returns the following:

```php
# /app/Http/Controllers/Tables/CatsTableController.php
use App\Tables\CatsTable;
...
class CatsTableController {
  public function __invoke(): TableCollection
  {
    $items = collect([
      [
        'some_field' => 'Some field value'
      ]
    ]);

    return CatsTable::from($items);
  }
}
```

Krait is flexible and works with multiple data formats, including:
- Eloquent collections
- Regular PHP arrays
- Laravel collections

For this example, we'll use an Eloquent model called `Cat`, which corresponds to a database table `cats` containing the following fields:
- `name`
- `breed`
- `country_code`
- `profession`

------------------
#### Steps to reproduce it
1. Create the model using `php artisan make:model Cat -m`
2. Update the migration

```php
# /database/migrations/xxxxxx_create_cats_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatsTable extends Migration
{
  public function up()
  {
    Schema::create('cats', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('breed');
      $table->string('country_code');
      $table->string('profession');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('cats');
  }
}
```

3. Run the migration

```bash
php artisan migrate
```

4. Update the `Cat` model
```php
# /app/Models/Cat.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
  protected $fillable = [
    'name',
    'breed',
    'country_code',
    'profession'
  ];
}
```

5. Create the Factory:

```bash
php artisan make:factory CatFactory
```

6. Create the Seeder:

```bash
php artisan make:seeder CatSeeder
```

7. Edit the Factory:
```php
<?php
# /database/factories/CatFactory.php

namespace Database\Factories;

use App\Models\Cat;
use Illuminate\Database\Eloquent\Factories\Factory;

class CatFactory extends Factory
{
  protected $model = Cat::class;

  public function definition()
  {
    return [
      'name' => $this->faker->firstName,
      'breed' => $this->faker->randomElement(['Persian', 'Siamese', 'Maine Coon', 'British Shorthair', 'Ragdoll']),
      'country_code' => $this->faker->countryCode,
      'profession' => $this->faker->jobTitle
    ];
  }
}
```

8. Edit the Seeder:
```php
<?php
# /database/seeders/CatSeeder.php

namespace Database\Seeders;

use App\Models\Cat;
use Illuminate\Database\Seeder;

class CatSeeder extends Seeder
{
  public function run()
  {
    Cat::factory()->count(500)->create();
  }
}
```

9. Update your Model to use `HasFactory`:
```php
<?php
# /app/Models/Cat.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'breed',
    'country_code',
    'profession'
  ];
}
```

10. Run the seeder:

```bash
php artisan db:seed --class=CatSeeder
```

------------------

Alright, now we have seeded the cats - a lot of cats!

**Let's show them!**

```php
# /app/Http/Controllers/Tables/CatsTableController.php

use App\Tables\CatsTable;
use App\Models\Cat;
...
class CatsTableController {
  public function __invoke(): TableCollection
  {
    # Using query to let Krait manage the pagination
    $cats = Cat::query()

    return CatsTable::from($cats);
  }
}
```

That's it! The setup is now complete with all features working automatically:
- Pagination
- Filtering
- User preferences
- Custom column previews
- And more

![Table Example](./example.png | width=250)

For advanced column structure customization options, please refer to our [Official Documentation](https://mtrdesign.github.io/krait/).

#### A Slight Touch on the Front-end Customizations

You can customize how table rows are displayed by modifying the VueJS component at `/resources/js/components/tables/CatsTable.vue`.
For example, let's modify the component to display "lazy cat..." for records where the profession field is empty.

```js
// /resources/js/components/tables/CatsTable.vue

<script setup>
defineProps({
  'filtersForm': {
    type: String,
    required: false,
    default: undefined,
  }
});
</script>

<template>
  <DynamicTable
    apiEndpoint="users-table"
    :filtersForm="filtersForm"
  >
    <template #row="{ record, column }">
      <div class="cell" v-if="column.name === 'prefession'">
        {{ record[column.name] }} ?? "lazy cat..."
      </div>
      <div class="cell" v-else>
        {{ record[column.name] ?? 'N/A' }}
      </div>
    </template>
  </DynamicTable>
</template>
```

These examples cover the basic customization of Krait, but the framework offers many more advanced features, including:

- **Adding Filters Form**: Create custom search and filtering interfaces
- **Dynamic Columns Generation**: Build fully dynamic tables with columns fetched from external services
- **Customizing Column Behavior**: Configure which columns can be sorted or filtered

For a complete overview of all features, please refer to our [documentation](https://mtrdesign.github.io/krait/).

## Conclusion

Krait offers a pragmatic solution to a common challenge in Laravel development - implementing dynamic
tables without overcomplicating your application architecture. By leveraging Laravel's existing strengths and
combining them with lightweight VueJS components, Krait provides a perfect balance between functionality and simplicity.

The package's philosophy of "working with Laravel, not against it" means you can maintain clean, maintainable code
while still delivering powerful, feature-rich tables. Whether you're building a small project or a large-scale application,
Krait's approach of minimal overhead and maximum integration makes it an excellent choice for Laravel developers who want
sophisticated table functionality without the complexity of full-scale SPA frameworks.

With its single-command setup, intuitive API, and extensive customization options, Krait empowers developers to create
professional-grade dynamic tables while staying true to Laravel's elegant syntax and conventions.

Remember to check out our [Official Documentation](https://mtrdesign.github.io/krait/) for more advanced features and customization options!
