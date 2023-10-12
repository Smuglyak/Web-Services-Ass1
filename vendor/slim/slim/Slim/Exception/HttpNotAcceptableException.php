<?php

declare(strict_types=1);
namespace Slim\Exception;

class HttpNotAcceptableException extends HttpSpecializedException
{

    /**
     * @var int
     */
    protected $code = 406;

    /**
     * @var string
     */
    protected $message = '406 Not Acceptable';

    protected string $title = '406 Not Acceptable';
    protected string $description = "The server cannot produce a response matching the list of acceptable values defined in the request's proactive content negotiation headers, and the server is unwilling to supply a default representation.";

}
