<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class VersionMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $content = json_decode($response->getContent());
        $setResponse['code'] = $content ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;
        $setResponse['status'] = $content ? true : false;
        $setResponse['message'] = $content ? 'Request successfull.' : 'No record found.';
        $setResponse['version'] = 'v1';
        $setResponse['data'] = $content;

        $response->setContent($setResponse);
        
        return $response;
    }
}