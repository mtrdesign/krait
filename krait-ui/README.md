# Krait UI Library

Krait UI Library is a VueJS-based project for handling the Krait DataTable components
front-end functionality.

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
      <li>
         <a href="#helper-scripts">Helper scripts</a>
      </li>
   </ol>
</details>

## Overview

This project contains VueJS and Bootstrap-based components for handling the Datatable
Logic and Appearance features.

## Usage

Although `krait-ui` is a separate NPM package (it can be installed and used as a stand-alone library,
its purpose is the integration with the main `krait` Laravel package. The package serves as a helper service to the Laravel codebase.

_Dev Note:_
_Changes in the back-end package will be reflected in this package as well._

## Local Setup

1. Ensure that you have `git` installed

- Confirm installation via the following command: `$ git --version`

2. Clone the repository

   ```sh
   git clone git@github.com:mtrdesign/krait.git
   ```

3. Change your current directory to the local clone of the `krait-ui` folder

   ```sh
   cd krait/krait-ui
   ```

4. Install NVM and NodeJS

- install them by following their [official guide](https://nodejs.org/en/download/package-manager)

5. After NVM is installed, install the project specific NodeJS version

   ```sh
   nvm install
   ```

_Dev Note: Ensure that you are running this command inside the `krait-ui` directory as
the node version is documented in the `.nvmrc` file._

6. Activate the corresponding NodeJS environment
   ```bash
   nvm use
   ```

_Dev Note: Again - ensure that you are running this command inside the `krait-ui` directory._

7. Install all dependencies
   ```bash
   npm ci
   ```

## Local Development

We have integrated [Storybook](https://storybook.js.org) to facilitate the visualization and development of features on local environments.
All backend functionalities are mocked, enabling independent development of this package without the need for local installation and integration with the primary 'krait' Laravel setup.

First, start the `local dev` server:

```sh
npm run dev
```

Then, start the storybook local server:

```shell
npm run storybook
```

## Code structure

The repository contains the following core directories:

- `/src` - the main source code
  - `/src/actions` - all the API actions grouped as `events` that can
    be dispatched from all modules in the app
  - `/src/components` - the Vue components
  - `/src/framework` - the core framework utils (Request, Response, Validation, etc.)
  - `/src/mixins` - the Vue mixins (similar to React hooks) that are shared across all components
  - `/src/types` - the more generic types
- `/scripts` - local development helper scripts
- `.storybook` - the Storybook configurations (all mocking functionality is there as well)

## Helper scripts

---

#### Generate Table Response

Converts CSV file to an example back-end response (as a JSON file).

```sh
generate-table-response {path to the file goes here}
```

---

#### Lint Check

Checks for lint-related issues.

```sh
npm run lint
```

---

#### Lint Fix

Checks and fixes some lint-related issues.

```sh
npm run lint:fix
```

---

#### Prettier Check

Checks for formatting-related issues.

```sh
npm run prettier
```

---

#### Prettier Fix

Checks and fixes some formatting-related issues.

```sh
npm run prettier:fix
```
