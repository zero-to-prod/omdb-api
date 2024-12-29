# Zerotoprod\OmdbApi

[![Repo](https://img.shields.io/badge/github-gray?logo=github)](https://github.com/zero-to-prod/omdb-api)
[![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/zero-to-prod/omdb-api/test.yml?label=tests)](https://github.com/zero-to-prod/omdb-api/actions)
[![Packagist Downloads](https://img.shields.io/packagist/dt/zero-to-prod/omdb-api?color=blue)](https://packagist.org/packages/zero-to-prod/omdb-api/stats)
[![Packagist Version](https://img.shields.io/packagist/v/zero-to-prod/omdb-api?color=f28d1a)](https://packagist.org/packages/zero-to-prod/omdb-api)
[![License](https://img.shields.io/packagist/l/zero-to-prod/omdb-api?color=red)](https://github.com/zero-to-prod/omdb-api/blob/main/LICENSE.md)

A cURL wrapper for the OMDb API.

## Installation

```shell
composer require zero-to-prod/omdb-api
```

## Usage

```php
use Zerotoprod\OmdbApi\OmdbApi;

$OmdbApi = new OmdbApi('apiKey');

$OmdbApi->byIdOrTitle('Avatar');
$OmdbApi->search('Avatar');
$OmdbApi->poster('tt0499549');
```

## Testing

```shell
./vendor/bin/phpunit
```