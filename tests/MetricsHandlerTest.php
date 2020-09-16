<?php

declare(strict_types=1);

namespace Mammatus\Tests\Vhost\Metrics\Prometheus;

use Chimera\Input;
use Mammatus\Vhost\Metrics\Prometheus\FetchMetrics;
use Mammatus\Vhost\Metrics\Prometheus\MetricsHandler;
use WyriHaximus\Metrics\InMemory\Registry;
use WyriHaximus\Metrics\Label;
use WyriHaximus\Metrics\Label\Name;
use WyriHaximus\TestUtilities\TestCase;

final class MetricsHandlerTest extends TestCase
{
    /**
     * @test
     */
    final public function forwardToMiddleware(): void
    {
        $input    = new class implements Input {
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
        $registry = new Registry();
        $registry->counter('name', 'description', new Name('key'))->counter(new Label('key', 'value'))->incrBy(123);
        $metricsHandler = new MetricsHandler($registry);

        $response     = $metricsHandler->handle(FetchMetrics::fromInput($input));
        $responseBody = (string) $response->getBody();

        self::assertStringContainsString('# HELP name_total description', $responseBody);
        self::assertStringContainsString('# TYPE name_total counter', $responseBody);
        self::assertStringContainsString('name_total{key="value"} 123', $responseBody);
    }
}
