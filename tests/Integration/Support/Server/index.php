<?php

declare(strict_types=1);

/**
 * Simple PHP server for integration tests: handles /status, /redirect, and /reflect endpoints
 */
$parsed = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = is_string($parsed) ? $parsed : '/';

if (preg_match('#^/status/(\d{3})$#', $path, $m)) {
    http_response_code((int)$m[1]);
    header('Content-Type: text/plain');
    echo 'status';
    return;
}

if (preg_match('#^/redirect/(\d{3})$#', $path, $m)) {
    $code = (int)$m[1];
    if ($code < 300 || $code >= 400) {
        $code = 302;
    }
    header('Location: /reflect', true, $code);
    return;
}

if ($path === '/reflect') {
    $headers = [];

    foreach ($_SERVER as $k => $v) {
        if (str_starts_with($k, 'HTTP_')) {
            $name = strtolower(str_replace('_', '-', substr($k, 5)));
            $headers[$name] = is_array($v) ? '[array]' : (string) $v;
        }
    }

    if (isset($_SERVER['CONTENT_TYPE'])) {
        $headers['content-type'] = (string) $_SERVER['CONTENT_TYPE'];
    }

    if (isset($_SERVER['CONTENT_LENGTH'])) {
        $headers['content-length'] = (string) $_SERVER['CONTENT_LENGTH'];
    }

    $input = file_get_contents('php://input');
    $body = $input === false ? '' : $input;

    header('Content-Type: application/json');
    echo json_encode([
        'method' => $_SERVER['REQUEST_METHOD'] ?? 'GET',
        'path' => $path,
        'headers' => $headers,
        'body' => $body,
    ], JSON_THROW_ON_ERROR);
    return;
}

http_response_code(404);
header('Content-Type: text/plain');
echo 'not found';
