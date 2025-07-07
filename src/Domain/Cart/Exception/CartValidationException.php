<?php declare(strict_types=1);

namespace App\Domain\Cart\Exception;

use Exception;
use Throwable;

class CartValidationException extends Exception
{
    private array $errors;

    public function __construct(
        array $errors = [],
        string $message = "Validation failed",
        int $code = 0,
        ?Throwable $previous = null)
    {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public static function create(array $errors): CartValidationException
    {
        return new CartValidationException($errors);
    }
}
