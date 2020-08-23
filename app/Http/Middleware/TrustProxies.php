<?php

namespace App\Http\Middleware;

use App\Http\Requests\SanitiseRequest;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array
     */
    protected $proxies;

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = SanitiseRequest::HEADER_X_FORWARDED_ALL;
}
