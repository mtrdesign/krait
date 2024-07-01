# Krait

`Krait` is a Laravel package for managing custom user preview configurations and API data serving.

<details>
   <summary>Table of Contents</summary>
    <ol>
      <li>
         <a href="#overview">Overview</a>
      </li>
       <li>
         <a href="#usage">Usage</a>
      </li>
       <li>
         <a href="#local-setup">Local setup</a>
      </li>
      <li>
         <a href="#local-development">Local development</a>
      </li>
      <li>
         <a href="#code-structure">Code structure</a>
      </li>
   </ol>
</details>

## Overview

This project contains all back-end functionalities for:
- Managing Custom User Preview configurations
- Automatically creating all API resources - Controllers, Routes, API Resource, Tables, etc.
- CLI Helper Commands


## Usage
Install the package via composer.

```bash
composer require mtrdesign/krait
```

Then, finish the installation by running:

```bash
php artisan krait:install
```

## Local Setup

1. Ensure that you have `git` installed
- Confirm installation via the following command: `$ git --version`

2. Clone the repository

   ```sh
   git clone git@github.com:mtrdesign/krait.git
   ```


## Local Development


There isn't a straightforward way to develop the package locally, we suggest creating an empty laravel
project and then installing and testing inside of it. You can put the package inside a `packages` folder
and then link the package with the `composer` config.

You can add the following properties to your composer configuration to link the package locally.
```json
{
    "repositories": {
        "mtrdesign/krait": {
            "type": "path",
            "url": "packages/mtrdesign/krait",
            "options": {
                "symlink": true
            }
        }
    },
    "require": {
        "mtrdesign/krait": "@dev"
    }
}
```

## Code structure
The repository contains the following core directories:

* `/config` - the default package configurations
* `/database` - the package migrations
* `/routes` - the package routes (both the internal and the `Table Resources`)
* `/src` - the main source code directory
    * `/src/Console` - the Artisan commands
    * `/src/DTO` - DTO objects for better data representation
    * `/src/HTTP` - `http` resources (controllers, middlewares, etc.)
    * `/src/Models` - the Eloquent models
    * `/src/Services` - the internal functionality services
    * `/src/Tables` - the Table generation class helpers
* `/scripts` - local development helper scripts
* `.storybook` - the Storybook configurations (all mocking functionality is there as well)

## Commands

--------
#### Generate Table
Creates all resources for a table.

```sh
php artisan create:table MyTable
```
--------

#### Lint Check
Checks for lint-related issues.

```sh
composer check-style
```
--------

#### Lint Fix
Checks and fixes some lint-related issues.

```sh
composer fix-style
```
--------

#### Dry Lint Fix
Checks and fixes some lint-related issues only in documents that had been updated.

```sh
composer dry-fix-style
```
