<?php

declare(strict_types=1);

namespace App\Support;

final class Validator
{
    private array $errors = [];

    public function __construct(private readonly array $data)
    {
    }

    public function required(string $field): self
    {
        if (!isset($this->data[$field]) || trim((string) $this->data[$field]) === '') {
            $this->errors[$field] = 'This field is required';
        }

        return $this;
    }

    public function email(string $field): self
    {
        if (isset($this->data[$field]) && $this->data[$field] !== '' && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = 'Enter a valid email address';
        }

        return $this;
    }

    public function min(string $field, int $length): self
    {
        if (isset($this->data[$field]) && mb_strlen((string) $this->data[$field]) < $length) {
            $this->errors[$field] = sprintf('Must be at least %d characters', $length);
        }

        return $this;
    }

    public function phone(string $field): self
    {
        if (isset($this->data[$field]) && $this->data[$field] !== '' && !preg_match('/^\+[1-9][0-9]{7,14}$/', (string) $this->data[$field])) {
            $this->errors[$field] = 'Enter a valid international phone number';
        }

        return $this;
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
