<?php

declare(strict_types=1);

namespace Mammatus\Tests\Vhost\Metrics\Prometheus;

use Chimera\Input;
use Mammatus\Vhost\Metrics\Prometheus\FetchIndex;
use Mammatus\Vhost\Metrics\Prometheus\IndexHandler;
use WyriHaximus\TestUtilities\TestCase;

use const WyriHaximus\Constants\HTTPStatusCodes\PERMANENT_REDIRECT;

final class IndexHandlerTest extends TestCase
{
    /**
     * @test
     */
    final public function redirectToMetrics(): void
    {
        $input        = new class implements Input {
            /**
             * @param mixed|null $default
             *
             * @return mixed|null
             */
            public function getAttribute(string $name, $default = null)
            {
                return $default;
            }

            /**
             * @return mixed[]
             */
            public function getData(): array
            {
                return [];
            }
        };
        $indexHandler = new IndexHandler();

        $response = $indexHandler->handle(FetchIndex::fromInput($input));

        self::assertSame(PERMANENT_REDIRECT, $response->getStatusCode());
        self::assertSame('/metrics', $response->getHeaderLine('Location'));
        self::assertSame('Shoo!', (string) $response->getBody());
    }
}
