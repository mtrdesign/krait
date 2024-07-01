# Core Components

## Table Classes

All table structures should be defined in the `/app/Tables` directory and should inherit the 
`MtrDesign\Krait\Tables\BaseTable` class.

By running `php artisan krait:table MyAwesomeTable`, Krait will generate the directory and the MyAwesomeTable
class automatically for you.

```php
<?php

namespace App\Tables;

use MtrDesign\Krait\Tables\BaseTable;

class MyAwesomeTable extends BaseTable
{
    function name(): string
    {
        return 'my-awesome-table';
    }

    function initColumns(): void
    {
        $this->column(
            name: 'my_first_column',
            label: 'My First Column',
            process: fn(mixed $resource) => 'This is the processed content!'
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

## Table Columns

All columns are internally represented by the [`TableColumnDTO`](https://github.com/mtrdesign/krait/blob/main/krait/src/DTO/TableColumnDTO.php) class. We define the columns
inside our Table Definition Classes using the `column` method. Here is the list of all parameters that can be assigned to specific column.


```php
<?php
protected column(
    string $name,
    string $label,
    bool $hideLabel = false,
    bool $datetime = false,
    bool $sortable = true,
    bool $fixed = false,
    string|null $classes = null,
    callable|null $process = null,
    callable|null $sort = null
): void
```

## Methods

**Parameters:**

| Parameter   | Type                   | Description                                         |
|-------------|------------------------|-----------------------------------------------------|
| `name`      | **string**             | The columns name                                    |
| `label`     | **string**             | The columns label                                   |
| `hideLabel` | **bool**               | Flags if the label should be visible in the header. |
| `datetime`  | **bool**               | Flags if the column contains datetime object.       |
| `sortable`  | **bool**               | Flags if the column is sortable.                    |
| `fixed`     | **bool**               | Flags if the column is resizable.                   |
| `classes`   | **string&#124;null**   | Additional classes that will be added on FE.        |
| `process`   | **callable&#124;null** | The column result generation callback.              |
| `sort`      | **callable&#124;null** | The column sorting callback.                        |


## Table Controllers

All table-related controllers are created in the `app/Http/Controllers/Tables` directory.
These controllers are invokable and have only one purpose - to fetch the table data.

```php
<?php

namespace App\Http\Controllers\Tables;

use App\Tables\MyAwesomeTable;
use MtrDesign\Krait\Http\Resources\TableCollection;

class MyAwesomeTableController extends Controller
{
    public function __invoke(): TableCollection
    {
        $records = [['my_first_column' => 'foo']];
        return MyAwesomeTable::from($records);
    }
}
```

## Table Vue Components

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

## Table Auto-generated Routes
Krait automatically associates routes to all table classes located in the `App/Tables` namespace.
