<?php

namespace App\Exceptions;

use App\Traits\HttpResponses;
use Exception;

class UnprocessableEntityException extends Exception
{
    use HttpResponses;

    public function render()
    {
        return $this->errorHttpMessage(null, 422, $this->getMessage());
    }
}
