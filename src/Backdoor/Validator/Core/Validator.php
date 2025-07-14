<?php

declare(strict_types=1);

namespace Backdoor\Validator\Core;

use Backdoor\Validator\Core\ValidatorInterface;

class Validator implements ValidatorInterface
{
    protected array $rules = [];

    // для добавления правил валидации для поля
    public function addRule(string|int $field, callable $rule): static 
    {
        $this->rules[$field][] = $rule;
        return $this;
    }

    // валидация значений
    public function validate(mixed $data): array
    {
        $errors = [];
        foreach ($this->rules as $field => $rules) {
            foreach ($rules as $rule) {
                $result = $rule($data[$field] ?? null);
                if ($result !== true) {
                    $errors[$field][] = $result;
                }
            }
        }
        
        return $errors;
    }
}