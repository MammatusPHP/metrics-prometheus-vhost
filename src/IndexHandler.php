<?php

declare(strict_types=1);

namespace Mammatus\Vhost\Metrics\Prometheus;

use Chimera\Mapping\Routing\FetchEndpoint;
use Mammatus\Http\Server\Annotations\Vhost;
use Psr\Http\Message\ResponseInterface;
use React\Http\Message\Response;

use const WyriHaximus\Constants\HTTPStatusCodes\PERMANENT_REDIRECT;

/**
 * @Vhost("prometheus-metrics")
 * @FetchEndpoint(app="prometheus-metrics", path="/", query=FetchIndex::class, name="FetchIndex")
 */
final class IndexHandler
{
    public function handle(FetchIndex $request): ResponseInterface
    {
        return new Response(PERMANENT_REDIRECT, ['Location' => '/metrics'], 'Shoo!');
    }
}
