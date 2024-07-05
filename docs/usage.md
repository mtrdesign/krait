# Usage Guide

## Table Creation

Run the artisan command to create all table resource

```sh
php artisan krait:table {here goes the table name}
```

!!! tip "Custom Directory"
    Similar to other Laravel generation commands, you can specify the
    table assets directory structure by prepending it to the name.

    For example, this command:
    ```sh
    php artisan krait:table Admin\\StatisticsTable
    ```
    
    will generate the following assets:

    - _/app/Tables/Admin/StatisticsTable.php_
    - _/app/Http/Controllers/Tables/Admin/StatisticsTable.php_
    - _/resources/js/components/tables/admin/StatisticsTable.vue_

### Naming Conventions
- All table names should follow the UpperCamelCase
- All table names should end on `Table`

???+ info "Valid vs Invalid Examples"
    - `php artisan krait:table UsersTable` ✅
    - `php artisan krait:table TableForUsers` ❌
    - `php artisan krait:table users-table` ❌
    - `php artisan krait:table users_table` ❌

Let's suppose that we want to generate a table that shows all available users.

By running `php artisan krait:install UsersTable`, we create the following resources:

#### The Table Definition Class

```php title="/app/Tables/UsersTable.php" linenums="1"
<?php
namespace App\Tables;

use MtrDesign\Krait\Tables\BaseTable;

class UsersTable extends BaseTable
{
    function name(): string
    {
        return 'users-table';
    }

    function initColumns(): void
    {
        $this->column(
            name: 'my_first_column',
            label: 'My First Column',
            process: fn(mixed $resource) => 'This is the processed content!'
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

- The `initColumns` method is the place where all columns are defined.
- The `additionalData` method is the place for defining data that should be passed along with the column values to the front-end.
- The `name` method returns the table name that's used for creating the routes.

There are a couple of methods that you can override in order to further customise the table behaviour:

##### Custom Authorization
All Table Definition classes contain the `authotize` method that accepts the incoming request and returns a `boolean` value, 
flagging if the request is authorized to access the table data.

```php
<?php
...
    class MyTable extends BaseTable
    {
        ...
        public function authorize(\Illuminate\Http\Request $request): bool
        {
            /**
            * Here you can check if the request is permitted or not.  
            */
            return true;
        }
    }
```

By default, all requests are permitted.

##### Custom Middlewares
Apart from the `global_middlewares` value from the `krait.php` configuration file,
you can attach specific middlewares to each table, overriding the `middlewares` method.

```php
<?php
...
    class MyTable extends BaseTable
    {
        ...
        public function middlewares(): array
        {
            /**
            * Here you list the table specific middlewares.  
            */
            return ['my-custom-middleware'];
        }
    }
```

##### Columns Initialisation
As you can see, all columns are defined in the `initColumns` method using the `column()` class helper.
You can find more information on all column attributes and properties in the [Core Components Columns Section](/core-components/#table-columns).
The Table Definition Class is a flexible way to define columns, they can even be fetched from a third-party library/dataset.

Logically, we can associate these "Table Definition" classes with the `model` part of the `model-view-controller` pattern.

#### The Table Controller
Krait automatically generates this empty controller.

```php title="/app/Http/Controllers/Tables/UsersTableController.php" linenums="1"
<?php
namespace App\Http\Controllers\Tables;

use MtrDesign\Krait\Http\Resources\TableCollection;
namespace App\Http\Controllers\Controller;
use App\Tables\UsersTable;

class UsersTableController extends Controller
{
    public function __invoke(): TableCollection
    {
        $items = collect([
            [
                'some_field' => 'Some field value'
            ]
        ]);

        return UsersTable::from($items);
    }
}
```

The `UsersTable::from` static method returns an API resource collection class that contains the
correct table response structure, which the front-end will consume.

You can pass `arrays`, and `collections` (both Eloquent and Regular ones).

This is the place where you can fetch the data and then pass it to the already-defined table.
For example, if we want to fetch all `users`, we will write something like:

```php
<?php
use App\Models\User;

...
    public function __invoke(): TableCollection
    {
        $users = User::query();
        
        /**
        * Here you can manipulate the query (filtering, slicing, etc.) 
        */
        
        return UsersTable::from($users);
    }
...
```

#### The Front-End Vue Components
```html title="/resources/js/components/tables/UsersTable.vue"
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
    tableName="users-table"
    :filtersForm="filtersForm"
  >
    <template #row="{ record, column }">
      <div class="cell" v-if="column.name === 'my_first_column'">
        Krait is awesome!
      </div>
      <div class="cell" v-else>
        {{ record[column.name] ?? 'N/A' }}
      </div>
    </template>
  </DynamicTable>
</template>

<style scoped lang="scss"></style>
```

In most cases, you will not update the `apiEndpoint` and the `tableName` props of the `DynamicTable`
component. The `DynamicTable` component uses [slots](https://vuejs.org/guide/components/slots.html)
to provide an easy way to manipulate the front-end representation of the data.

- the `record` object contains the values for all columns (defined in the Table Definition Class)
- the `column` object contains all column properties (from the DTO class [`TableColumnDTO`](https://github.com/mtrdesign/krait/blob/main/krait/src/DTO/TableColumnDTO.php))

#### Implementing the Table in Blade Template
Krait will register the table VueJS component automatically for you. You can directly attach it to your view files.

```html title="your-blade-template-view-file.blade.php"
...
<body>
    ...
    <users-table></users-table>
    ...
</body>
...
```

## Configurations

The Krait configurations are placed in the `config/krait.php` file.

| Parameter            | Type       | Description                                                                         |
|----------------------|------------|-------------------------------------------------------------------------------------|
| `debug`              | **bool**   | Turns the front-end debug mode on/off (showing more details in the console).        |
| `krait_path`         | **string** | The internal Krait API path prefix (for Krait actions).                             |
| `tables_path`        | **string** | The Tables API path prefix (used to generate the routes for all registered tables). |
| `global_middlewares` | **array**  | List of middlewares that should be applied to all Krait routes.                     |
| `use_csrf`           | **bool**   | Flags if all Krait front-end requests should contain the CSRF token.                |

???+ warning "Current Package Version Requires Authentication"
    For now, Krait works for registered users only. We will expand it for unauthenticated usage in the upcoming versions.
