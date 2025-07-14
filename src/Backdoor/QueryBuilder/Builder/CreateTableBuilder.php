<?php

declare(strict_types=1);

namespace Backdoor\QueryBuilder\Builder;

use Backdoor\QueryBuilder\QueryBuilderInterface;
use Backdoor\QueryBuilder\Trait\WhereBuilderTrait;
use Backdoor\QueryBuilder\Trait\ParamsBuilderTrait;
use Backdoor\QueryBuilder\Trait\GetTableTrait;

class CreateTableBuilder implements QueryBuilderInterface
{
    use WhereBuilderTrait, ParamsBuilderTrait, GetTableTrait;

    /**
     * @var 
     */
    public array $columns;

    public function __construct() {}

    public function create(string $table): static
    {
        $this->table = $table;
        return $this;
    }

    public function columns(array $columns): static
    {
        foreach ($columns as $key => $column) {
            $this->addColumn($column);
        }
        return $this;
    }

    public function addColumn(Column $column): static
    {
        $this->columns[] = $column;
        return $this;
    }

    public function build(): string
    {
        return "";
    }

    public function __toString(): string
    {
        return $this->build();
    }    
}