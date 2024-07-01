## Description

All table structures should be defined in the `/app/Tables` directory and should inherit the
`MtrDesign\Krait\Tables\BaseTable` class.

By running `php artisan krait:table MyAwesomeTable`, Krait will generate the directory and the MyAwesomeTable
class automatically for you.

## Example

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

***

## Methods

### name

Returns the table name.

```php
public name(): string
```

***

### initColumns

Initializes the table columns.

```php
public initColumns(): void
```

***

### shouldCache

Flags if the columns should be cached.

```php
protected shouldCache(): bool
```

Usable for dynamic columns serving (from a third-party services).

***

### shouldRefresh

Flags if the columns should be refreshed on every request.

```php
protected shouldRefresh(): bool
```

Usable for dynamic columns serving (from a third-party services).

***

### column

Adds a column to the table

```php
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

**Parameters:**

| Parameter    | Type                   | Description                                           |
|--------------|------------------------|-------------------------------------------------------|
| `$name`      | **string**             | - The columns name                                    |
| `$label`     | **string**             | - The columns label                                   |
| `$hideLabel` | **bool**               | - Flags if the label should be visible in the header. |
| `$datetime`  | **bool**               | - Flags if the column contains datetime object.       |
| `$sortable`  | **bool**               | - Flags if the column is sortable.                    |
| `$fixed`     | **bool**               | - Flags if the column is resizable.                   |
| `$classes`   | **string&#124;null**   | - Additional classes that will be added on FE.        |
| `$process`   | **callable&#124;null** | - The column result generation callback.              |
| `$sort`      | **callable&#124;null** | - The column sorting callback.                        |

***

### getColumns

Returns all columns

```php
public getColumns(): \MtrDesign\Krait\DTO\TableColumnDTO[]
```

***

### getColumn

Returns specific column

```php
public getColumn(string $columnName): \MtrDesign\Krait\DTO\TableColumnDTO
```

**Parameters:**

| Parameter       | Type         | Description |
|-----------------|--------------|-------------|
| `$columnName`   | **string**   |             |


**Throws:**

- [`Exception`](../../../Exception.md)

***

### getCachedColumns

Returns the columns from the cache (if there are any)

```php
protected getCachedColumns(): ?array
```
***

### getFacade

Returns a Laravel Facade of the Table class.

```php
protected static getFacade(): \MtrDesign\Krait\Tables\BaseTable
```

***

### processRecord

Processes one record.

```php
public processRecord(\Illuminate\Database\Eloquent\Model|array $resource, mixed|null $placeholder = null): array|mixed
```

**Parameters:**

| Parameter      | Type                                                 | Description                         |
|----------------|------------------------------------------------------|-------------------------------------|
| `$resource`    | **\Illuminate\Database\Eloquent\Model&#124;array**   | - The record.                       |
| `$placeholder` | **mixed&#124;null**                                  | - The placeholder for empty values. |


***

### process

Processes a record.

```php
public static process(mixed $resource, mixed|null $placeholder = null): mixed
```

**Parameters:**

| Parameter      | Type                  | Description                         |
|----------------|-----------------------|-------------------------------------|
| `$resource`    | **mixed**             | - The target record.                |
| `$placeholder` | **mixed&#124;null**   | - The placeholder for empty values. |

***

### from

Generates an API Resource Collection for the table.

```php
public static from(mixed $records): \MtrDesign\Krait\Http\Resources\TableCollection
```

**Parameters:**

| Parameter    | Type        | Description             |
|--------------|-------------|-------------------------|
| `$records`   | **mixed**   | - The target records.   |

***

### getName

Returns the table name.

```php
public static getName(): string
```

***

### additionalData

Returns the table additional data passed to the FE.

```php
public additionalData(mixed $resource): array
```


**Parameters:**

| Parameter     | Type        | Description |
|---------------|-------------|-------------|
| `$resource`   | **mixed**   |             |


***

### getKeyName

Returns the record ID key.

```php
public getKeyName(): string
```
