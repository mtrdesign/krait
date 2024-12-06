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

The default table template has only two columns and one additional attribute:

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

...
}
```

All columns are defined in the `initColumns` function, following the syntax shown above.
Now, let's change our table a bit. Let's add a `name`, `breed`, `country`, `profession` (yes - cat's are lazy but let's assume that they can work!).

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
      );

      $this->column(
        name: 'breed',
        label: 'Breed',
      );

      $this->column(
        name: 'country_code',
        label: 'Country',
      )

      $this->column(
        name: 'profession',
        label: 'Job name',
      )
  }

...
}
```

The `name` fields correspond to the property of the data records, the `label` is the front-end title.

To make it a little bit more interesting, let's get the real country name from a third-party API.
To manipulate the `country_code` column, we can add a new `attribute` function to out `CatsTable` class like:

```
use GuzzleHttp\Client;
...


class CatsTable {
  ...

  function getCountryCodeKraitAttribute(mixed $cat) {
    $client = new Client();

    try {
        $response = $client->get("https://restcountries.com/v3.1/alpha/{$cat->country_code}");
        $data = json_decode($response->getBody(), true);
        return $data[0]['name']['common'] ?? 'Unknown';
    } catch (Exception $e) {
        return 'Unknown';
    }
  }

  ...
}

```

This is just a showcase for the dynamic data pipelines creation in the table, you can image how many possible
usages can be covered with this.

Now, let's take a close look into our `CatsTableController` class. By default the controller returns this:

```php
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

Krait works with both Eloquent collections as well as with just regular php arrays (or Laravel collections).
For now, let's assume the most general case - you have a Eloquent model `Cat` (and table `cats` that contains the `name`, `breed`, `country_code`, and `profession` fields).

#### Steps to reproduce it
1. Create the model using `php artisan make:model Cat -m`
2. Update the migration

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatssTable extends Migration
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

7. Edit the Factory (database/factories/CatFactory.php):
```php
<?php

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

8. Edit the Seeder (database/seeders/CatSeeder.php):
```php
<?php

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

9. Update your Model to use HasFactory (app/Models/Cat.php):
```php
<?php

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
# OR run specific seeder
php artisan db:seed --class=CatSeeder
```

Alrightm now we have seeded records of cats - a lot of cats!

Let's now implement them in the `CatsTableController`:

```php
use App\Tables\CatsTable;
use App\Models\Cat;

...

class CatsTableController {
  public function __invoke(): TableCollection
  {

    # Using query to let Krait manage the pagination
    $cats = Cat::query()

    return CatsTable::from($items);
  }
}
```

That's it! Now we have all the data passed/processed as expected and everything is handled out-of-the-box -
the pagination, the filtering, the user preferences, the custom columns previewing, etc.

Krait offers a lot of different ways to modify the back-end columns structure, please take a look at our
[Official Documentation](https://mtrdesign.github.io/krait/).


*Just a slight touch on the front-end customizations as well*
All rows can be customized, using the table VueJS component - `/resources/js/components/tables/CatsTable.vue`.
Let's modify it so it shows "lazy cat..." in all records that don't have profession assosiated.

```js
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

These are the fundamental customizations of Krait, but it covers a lot of features:
- Adding Filters Form
- Dynamic Columns Generation (you can have 100% dynamic tables, fetching all columns from a third-party services)
- Customizing the sortable/filterable columns

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
