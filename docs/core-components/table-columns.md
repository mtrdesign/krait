## Description

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


## TableColumnDTO Description

DTO Object for handling consistent column generation.

* Full name: `\MtrDesign\Krait\DTO\TableColumnDTO`

## TableColumnDTO Methods

### __construct

```php
public __construct(
    string $name,
    string $label,
    bool $hideLabel = false,
    bool $datetime = false,
    bool $sortable = true,
    bool $fixed = false,
    string|null $classes = null,
    callable|null $process = null,
    callable|null $sort = null
): mixed
```

**Parameters:**

| Parameter    | Type                   | Description                                          |
|--------------|------------------------|------------------------------------------------------|
| `$name`      | **string**             | - The column name.                                   |
| `$label`     | **string**             | - The column label.                                  |
| `$hideLabel` | **bool**               | - Flags if the label should be visible.              |
| `$datetime`  | **bool**               | - Flags if the column contains datetime information. |
| `$sortable`  | **bool**               | - Flags if the column is sortable.                   |
| `$fixed`     | **bool**               | - Flags if the column is resizable.                  |
| `$classes`   | **string&#124;null**   | - Sets FE style classes.                             |
| `$process`   | **callable&#124;null** | - Sets the result processing callback.               |
| `$sort`      | **callable&#124;null** | - Sets the records sorting callback.                 |

***

### process

Processes one record.

```php
public process(mixed $model): mixed
```

**Parameters:**

| Parameter  | Type        | Description              |
|------------|-------------|--------------------------|
| `$model`   | **mixed**   | the target resource data |

***

### sort

Sorts records.

```php
public sort(mixed $records, string $direction): mixed
```

**Parameters:**

| Parameter    | Type        | Description              |
|--------------|-------------|--------------------------|
| `$records`   | **mixed**   | - The records.           |
| `$direction` | **string**  | - The sorting direction. |

***

### hasProcessingCallback

Flags if the column has processing callback assigned.

```php
public hasProcessingCallback(): bool
```

***

### toArray

Returns the column represented as array.

```php
public toArray(): array
```
