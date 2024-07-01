# Contribution

## Bugs Reporting
For any issues that you find, don't hesitate to raise a GH issue and then create 
a PR with some suggested fixes.

## Features Proposing
For any feature ideas, again - please raise a GH issue with a descriptive title and a nice description
of the feature. If you create a PR for it, the issue will get more attention, and it will be pushed forward
faster.

## Contribution Flow
As Krait library is actually a combination of PHP and NPM packages, we can't fix the bug in an already published
version (NPM has restrictions about the Packages Republishing). So we suggest you to always start from the `main` branch
as this is the latest branch that will be deployed (as next version of the package).

For any PRs, please attach the correct GH labels and provide a clear title and description (it would be even better if
there is a GH issue attached as well).

Please keep your PRs in `draft` state until they are ready for review.

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
