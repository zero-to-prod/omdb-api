<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Zerotoprod\OmdbApi\OmdbApi;

class OmdbTest extends TestCase
{

    #[Test] public function instantiation(): void
    {
        $OmdbApi = new OmdbApi('apiKey');

        self::assertEquals('apiKey', $OmdbApi->apikey);
        self::assertEquals('https://www.omdbapi.com/', $OmdbApi->base_url);
        self::assertEquals('https://img.omdbapi.com/', $OmdbApi->img_url);
    }
}