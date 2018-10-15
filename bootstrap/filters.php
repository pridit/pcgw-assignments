<?php

$authenticated = function ($request, $response, $next) {
    if ($this->session->exists('user') && \App\User::active()->find($this->session->get('user')->id)) {
        return $next($request, $response);
    }

    if ($this->session->exists('user')) {
        return $response->withHeader('Location', $this->router->pathFor('users.logout'));
    }

    return $response->withHeader('Location', $this->router->pathFor('home'));
};

$mediawiki = function ($request, $response, $next) {
    if ($this->mediawiki->authenticate()) {        
        return $next($request, $response);
    }

    $this->logger->warning('Unauthenticated with MediaWiki', $this->cookie->get($request)->toArray());

    $this->flash->addMessage('mediawiki', true);
    
    if ($request->getHeader('HTTP_REFERER')) {
        $path = $request->getHeader('HTTP_REFERER');
    }

    return $response->withHeader('Location', $path ?: $this->router->pathFor('home'));
};
