<?php
class HttpStatus
{
    const OK = 'success!';
    const AUTH_ERROR_MSG = 'The server could not verify that you are authorized to access the URL requested.';
    const UNAUTHORIZED = 'you don\'t have permission to access the requested resource';
    const RESOURCE_NOT_EXIST = 'The requested resource doesn\'t exists';
    const DEFAULT_ERROR_MESSAGE = 'An error occurred. Please try again!';
    const UNEXPECTED_ERROR = 'Unexpected Error';
}
