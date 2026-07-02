<?php

declare(strict_types=1);

use Kensho\Cloudflare\Cache;
use Kirby\Cms\App;

App::plugin(name: 'kensho/cloudflare', extends: [
	'cacheTypes' => [
		'cloudflare' => Cache::class,
	],
	'hooks' => [
		'page.render:before' => function (string $contentType, array $data): array {
			if (option(key: 'cache.pages.type', default: 'file') === 'cloudflare') {
				/*
				 * Prevents caching pages in Cloudflare by default.
				 */
				kirby()->response()->header(key: 'Cloudflare-CDN-Cache-Control', value: 'no-store');
			}
			return $data;
		},
	],
]);
