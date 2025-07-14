<?php

declare(strict_types=1);

namespace Backdoor\QueryBuilder\Builder;

use Backdoor\QueryBuilder\QueryBuilderInterface;
use Backdoor\QueryBuilder\Trait\WhereBuilderTrait;
use Backdoor\QueryBuilder\Trait\ParamsBuilderTrait;
use Backdoor\QueryBuilder\Trait\GetTableTrait;

class DescribeBuilder implements QueryBuilderInterface
{
    use GetTableTrait;

    public function describe(string $table): static
    {
        $this->table = $table;
        return $this;
    }

    public function getParams(): array
    {
        return [];
    }

    public function build(): string
    {
        return <<<SQL
        DESCRIBE {$this->getTable()}
        SQL;
    }

    public function __toString(): string
    {
        return $this->build();
    }
}