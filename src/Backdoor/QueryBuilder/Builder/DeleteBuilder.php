<?php

declare(strict_types=1);

namespace Backdoor\QueryBuilder\Builder;

use Backdoor\QueryBuilder\QueryBuilderInterface;
use Backdoor\QueryBuilder\Trait\WhereBuilderTrait;
use Backdoor\QueryBuilder\Trait\ParamsBuilderTrait;
use Backdoor\QueryBuilder\Trait\GetTableTrait;

class DeleteBuilder implements QueryBuilderInterface
{
    use WhereBuilderTrait, ParamsBuilderTrait, GetTableTrait;

    public function __construct() {}

    public function from(string $table): static
    {
        $this->table = $table;
        return $this;
    }

    public function build(): string
    {
        $where = $this->whereClause();
        if (!empty($where)) {
            $where = "WHERE" . PHP_EOL . $where;
        }
        $sql = <<<SQL
        DELETE FROM {$this->getTable()}
        {$where}
        SQL;
        return $sql;
    }

    public function __toString(): string
    {
        return $this->build();
    }
}