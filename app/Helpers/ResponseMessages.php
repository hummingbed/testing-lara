<?php

namespace App\Helpers;

class ResponseMessages
{
    const AUTH_SUCCESS = 'Authentication successful';
    const UNPROCESSABLE_CONTENT = 'Unprocessable content';


    public static function getSuccessMessage($message): string
    {
        return sprintf( $message);
    }

    public static function getEntityNotExistMessage($model): string
    {
        return sprintf("$model does not exist");
    }

    public static function unprocessableEntityMessage($message): string
    {
        return sprintf($message);
    }

}
