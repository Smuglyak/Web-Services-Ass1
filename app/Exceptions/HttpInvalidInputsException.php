<?php
namespace Vanier\Api\Exceptions;
use Slim\Exception\HttpSpecializedException;
class HttpInvalidInputsException extends HttpSpecializedException
{
/**
     * @var int
     */
    protected $code = 422;

    /**
     * @var string
     */
    protected $message = 'Not found.';

    protected string $title = '422 Invalid Inputs';
    protected string $description = 'The input given was invalid. Please verify and try again.';
}



