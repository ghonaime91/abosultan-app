<?php
declare(strict_types=1);

return [

    "validation_error" => "Validation Error",

    // 4xx Client Errors
    "verification_link_invalid" => "Verification link is invalid or expired",
    "bad_request" => "Bad Request",
    "unauthenticated" => "You need to login first",
    "unauthorized" => "Unauthorized",
    "forbidden" => "Forbidden",
    "not_found" => "Resource Not Found",
    "method_not_allowed" => "Method Not Allowed",
    "request_timeout" => "Request Timeout",
    "conflict" => "Conflict",
    "gone" => "Gone",
    "throttle" => "too many attempts",

    // 5xx Server Errors
    "internal_server_error" => "Internal Server Error",
    "not_implemented" => "Not Implemented",
    "bad_gateway" => "Bad Gateway",
    "service_unavailable" => "Service Unavailable",
    "gateway_timeout" => "Gateway Timeout",
    "http_version_not_supported" => "HTTP Version Not Supported",
];
