<?php
namespace App\Utils;

use Illuminate\Support\MessageBag;

class Result
{
    protected $success;
    protected $errors;

    public function __construct($success, MessageBag $errors = null)
    {
        $this->success = $success;
        $this->errors = $errors ?? new MessageBag();
    }

    public static function success()
    {
        return new static(true);
    }

    public static function error(MessageBag $errors)
    {
        return new static(false, $errors);
    }

    public function isSuccess()
    {
        return $this->success;
    }

    public function isFailure()
    {
        return !$this->success;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
