## Description

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

## Methods
