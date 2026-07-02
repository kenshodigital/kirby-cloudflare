<?php

declare(strict_types=1);

namespace Kensho\Cloudflare;

use Exception;
use Kirby\Cache\NullCache;
use Kirby\Http\Remote;
use Override;

class Cache extends NullCache
{
	private bool $enabled {
		get => (string) $this->options['prefix'] ?? '';
	}

	private string $prefix {
		get => (string) $this->options['prefix'] ?? '';
	}

	private string $token {
		get => (string) $this->options['token'] ?? '';
	}

	private string $zone {
		get => (string) $this->options['zone'] ?? '';
	}

	#[Override]
	public function enabled(): bool
	{
		return $this->enabled;
	}

	#[Override]
	public function set(string $key, $value, int $minutes = 0): bool
	{
		$response = kirby()->response();
		$response->header(key: 'Cloudflare-CDN-Cache-Control', value: 'public');
		$response->header(key: 'Cache-Tag', value: "{$this->tag(value: $this->prefix)},{$this->tag(value: $key)}");
		return true;
	}

	private function tag(string $value): string
	{
		return substr(string: hash(algo: 'sha256', data: $value), offset: 0, length: 8);
	}

	#[Override]
	public function remove(string $key): bool
	{
		try {
			return Remote::request(url: "https://api.cloudflare.com/client/v4/zones/$this->zone/purge_cache", params: [
				'data' => json_encode(value: ['tags' => [$this->tag(value: $key)]]),
				'headers' => ["Authorization: Bearer $this->token", 'Content-Type: application/json'],
				'method' => 'POST',
			])->code() === 200;
		} catch (Exception) {
			return false;
		}
	}

	#[Override]
	public function flush(): bool
	{
		return $this->remove(key: $this->prefix);
	}
}
