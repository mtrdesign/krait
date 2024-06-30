# Overview

## What is Krait?
Krait is a powerful Laravel package that simplifies the creation of Ajax Dynamic DataTables.
It automates the initial structure development of both front-end and back-end resources via simple CLI commands.

The package can create a whole set of `controllers`, `routes`, `Vue components` and `tables definitions` with just one 
simple command:
```sh
php artisan krait:table MyAwesomeTable
```

All the resources linking happens behind the scenes for you and you should think about the data representation **only**.

## Features

### Front-End Dynamic Tables
All front-end tables include the following features out-of-the-box:

- resizable columns
- custom visible columns selection
- columns reordering
- AJAX pagination fetching
- columns sorting
- pagination dynamic configuring the `items per page` value

### Per-user Preview Configurations
Krait saves all user preview preferences in the database. Whenever a user hides/resizes/reorder columns,
the update are saved and reused the next time that user opens the table.

## When Should I Use Krait?

There are two factors that should be taken into account when answering this question:

- The package handles all resources creation and linking out-of-the-box.
    * that helps you focus on the more important parts (defining processing data callbacks, fetching records, etc.)
    * that provides you with already tested back-end functionality 
- The front-end uses VueJS components so the data is represented in an async (Ajax) way.
    * as all filtering, sorting, pagination processes use Ajax, the whole table gets faster
    * that helps you to develop more dynamic table content

The package is suitable for:

* Projects that serve massive datasets to the end-users
* Projects that contain complex tables
    * with more dynamic front-end elements
    * with complex callbacks for pre-processing the shown data
