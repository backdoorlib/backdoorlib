<?php

declare(strict_types=1);

namespace Backdoor\QueryBuilder\Builder;

use Backdoor\QueryBuilder\QueryBuilderInterface;
use Backdoor\QueryBuilder\Trait\WhereBuilderTrait;
use Backdoor\QueryBuilder\Trait\ParamsBuilderTrait;
use Backdoor\QueryBuilder\Trait\GetTableTrait;
use Backdoor\Helper\Util\StringUtil;

class UpdateBuilder implements QueryBuilderInterface
{
    use WhereBuilderTrait, ParamsBuilderTrait, GetTableTrait;
    private array $setParams;

    public function __construct() {}

    public function update(string $table): static
    {
        $this->table = $table;
        return $this;
    }

    public function setParam(string $key, mixed $value): static
    {
        $this->setParams[StringUtil::clearSpaces($key)] = $value;
        return $this;
    }

    public function setParams(array $setters): static
    {
        foreach ($setters as $col => $value) {
            $this->setParam($col, $value);
        }
        return $this;
    }

    public function getParams(): array
    {
        return [...$this->setParams, ...$this->params];
    }

    public function build(): string
    {
        $setParams = [];
        foreach ($this->setParams as $col => $value) {
            $setParams[] = "{$col} = :{$col}";
        }
        $set = implode(", ", $setParams);
        $where = $this->whereClause();

        $sql = <<<SQL
        UPDATE {$this->getTable()}
        SET {$set}
        WHERE
        {$where}
        SQL;
        return $sql;
    }

    public function __toString(): string
    {
        return $this->build();
    }
}