## Description

All table-related controllers are created in the `app/Http/Controllers/Tables` directory.
These controllers are invokable and have only one purpose - to fetch the table data and pass it to the .
`Table Definition Class`.

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

        /**
        * Here you might fetch/manipulate the data. 
        */
        
        return MyAwesomeTable::from($records);
    }
}
```

## Methods

***

### __invoke

Processes the incoming front-end request.

```php
public invoke(): MtrDesign\Krait\Http\Resources\TableCollection
```
