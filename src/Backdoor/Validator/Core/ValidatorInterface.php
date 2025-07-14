<?php

declare(strict_types=1);

namespace Backdoor\Validator\Core;

interface ValidatorInterface
{
    public function addRule(string|int $field, callable $rule): static;
    public function validate(mixed $data): array;
}