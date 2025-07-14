<?php

declare(strict_types=1);

namespace Backdoor\QueryBuilder;

interface QueryBuilderInterface
{
    public function build(): string;
    public function __toString(): string;
    public function getParams(): array;
    public function getTable(): string;
}