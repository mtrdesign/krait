# Contribution

## Bugs Reporting
For any issues that you find, don't hesitate to raise a GH issue and then create a PR with some suggested fixes.

## Features Proposing
For any feature ideas, please create a GitHub issue with a descriptive title and clear description.
Creating a pull request for it will attract more attention to the issue and speed up the process.

## Contribution Flow
As the Krait library is a combination of PHP and NPM packages, we can't fix the bug in an already published version (NPM has restrictions about the Packages Republishing). So we suggest you always start from the main branch as this is the latest branch that will be deployed as the next version of the package.
For any PRs, please attach the correct GH labels and provide a clear title and description (it would be even better if there is a GH issue attached as well).

Please keep your PRs in a `draft` state until they are ready for review.

## Coding Style
Please run the following commands and ensure that there aren't any failures before pushing your PR for review.

#### For the Laravel `krait` package

```sh
composer check-style
```

#### For the Front-end UI Library `krait-ui` package

```sh
npm run prettier:check
npm run lint:check
```
