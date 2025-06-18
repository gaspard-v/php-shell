<?php

namespace GaspardV\PhpShell\Http;

enum MethodEnum: string
{
    case GET = "GET";
    case POST = "POST";
    case PUT = "PUT";
    case DELETE = "DELETE";
    case PATCH = "PATCH";
    case OPTIONS = "OPTIONS";
    case HEAD = "HEAD";
}
