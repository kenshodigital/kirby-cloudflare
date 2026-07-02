# Kirby Cloudflare

Enables [Cloudflare Cache][cFrGCp] for pages in [Kirby][UPxnrx] projects.

[cFrGCp]: https://developers.cloudflare.com/cache/
[UPxnrx]: https://getkirby.com

## General

If you’re proxying a website through Cloudflare, static assets are typically already cached. HTML pages aren’t cached by default, though. While this can be easily enabled with a basic Cache Rule in Cloudflare, the CDN cannot determine which pages are cacheable by Kirby’s standards, or when content has been updated in the CMS. It simply caches every page until the cache gets manually purged or expires.

The plugin provides a custom cache driver for Kirby's page cache. This leverages Kirby’s core logic for determining what pages are cacheable and when to flush the cache. Instead of caching pages on the server, the cache driver only sends the appropriate Cloudflare headers, and leaves the actual caching to the CDN.

It also handles purging the cache via API when appropriate, making the overall caching behavior consistent with Kirby’s built-in page cache.

### Further reading

- [Caching guide][9KJBUN]

[9KJBUN]: https://getkirby.com/docs/guide/cache

## Usage

### Installation

```shell
composer require kenshodigital/kirby-cloudflare ^1.0
```

### Setup

#### Cloudflare

The domain has to be proxied through Cloudflare and a **Cache Rule** needs to be set up to actually cache all eligible responses, including HTML pages.

An **API token** with _Cache Purge_ permission for the domain is also required.

##### Further reading

- [Cache Rules][3uKMfP]
- [API token][MzAkkR]

[3uKMfP]: https://developers.cloudflare.com/cache/how-to/cache-rules/
[MzAkkR]: https://developers.cloudflare.com/fundamentals/api/get-started/create-token/

#### Kirby

Enable the page cache and set the cache type to `cloudflare` in your `config.php`. Make sure to provide your zone ID along with your API token.

```php
<?php declare(strict_types=1);

return [
  'cache' => [
    'pages' => [
      'active' => true,
      'type'   => 'cloudflare',
      'zone'   => '***',
      'token'  => '***',
    ],
  ],
];
```

##### Further reading

- [Cache drivers and options][Qth8KW]

[Qth8KW]: https://getkirby.com/docs/guide/cache#cache-drivers-and-options
