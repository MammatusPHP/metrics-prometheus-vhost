<?php

declare(strict_types=1);

namespace Mammatus\Vhost\Metrics\Prometheus;

use Chimera\Mapping\Routing\FetchEndpoint;
use Mammatus\Http\Server\Annotations\Vhost;
use Psr\Http\Message\ResponseInterface;
use RingCentral\Psr7\Response;
use WyriHaximus\Metrics\Printer\Prometheus;
use WyriHaximus\Metrics\Registry;

use const WyriHaximus\Constants\HTTPStatusCodes\OK;

/**
 * @Vhost("metrics")
 * @FetchEndpoint(app="metrics", path="/metrics", query=FetchMetrics::class, name="FetchMetrics")
 */
final class MetricsHandler
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function handle(FetchMetrics $query): ResponseInterface
    {
        return new Response(OK, [], $this->registry->print(new Prometheus()));
    }
}
