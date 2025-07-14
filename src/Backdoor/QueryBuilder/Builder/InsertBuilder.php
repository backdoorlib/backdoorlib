<?php

declare(strict_types=1);

namespace Backdoor\QueryBuilder\Builder;

use Backdoor\QueryBuilder\QueryBuilderInterface;
use Backdoor\QueryBuilder\Trait\WhereBuilderTrait;
use Backdoor\QueryBuilder\Trait\ParamsBuilderTrait;
use Backdoor\QueryBuilder\Trait\GetTableTrait;
use Backdoor\Helper\Util\StringUtil;

class InsertBuilder implements QueryBuilderInterface
{
    use WhereBuilderTrait, ParamsBuilderTrait, GetTableTrait;
    private ?array $columns;
    private ?array $values;

    public function __construct() {
    }

    public function insert(string $table): static
    {
        $this->table = $table;
        return $this;
    }

    public function columns(array $columns): static
    {
        $this->columns = [];
        foreach ($columns as $column) {
            if (is_string($column) && !empty($column) && !in_array($column, $this->columns)) {
                $this->columns[] = StringUtil::toSnakeCase($column);
            }
        }
        return $this;
    }

    public function values(array $values): static
    {
        foreach ($values as $value) {
            if (
                !empty($value) &&
                is_string($value) &&
                !in_array($value, $this->values) &&
                str_starts_with($value, ":") // TODO: Возможно стоит убрать проверку на двоеточие
            ) {
                $this->values[] = StringUtil::clearSpaces($value);
            }
        }
        return $this;
    }

    public function build(): string
    {
        $columns = "(" . implode(", ", $this->columns ?? array_keys($this->getParams())) . ")";
        
        $values = "(:" . implode(", :", $this->columns ?? array_keys($this->getParams())) . ")";
        
        $sql = <<<SQL
        INSERT INTO {$this->getTable()}
        {$columns}
        VALUES {$values}
        SQL;
        return $sql;
    }

    public function __toString(): string
    {
        return $this->build();
    }
}