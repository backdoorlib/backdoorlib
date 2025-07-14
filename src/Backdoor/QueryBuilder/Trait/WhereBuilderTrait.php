<?php

declare(strict_types=1);

namespace Backdoor\QueryBuilder\Trait;

trait WhereBuilderTrait
{
    private array $andWhere = [];
    private array $orWhere = [];

    public function andWhere(string $where) : static
    {
        $this->andWhere[] = $where;
        return $this;
    }

    public function orWhere(string $where) : static
    {
        $this->orWhere[] = $where;
        return $this;
    }

    public function whereClause(): string
    {
        $orWhere = implode(" OR " . PHP_EOL . "    ", $this->orWhere);
        if (!empty($orWhere)){
            $orWhere = <<<SQL
            (
                $orWhere
                )
            SQL;
        } 
        $andWhere = [$orWhere, ...$this->andWhere];
        $andWhere = array_filter($andWhere);

        $where = "    " . implode(" AND " . PHP_EOL . "    ", $andWhere);
        return $where;
    }
}
