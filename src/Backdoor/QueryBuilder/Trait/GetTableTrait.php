<?php

declare(strict_types=1);

namespace Backdoor\QueryBuilder\Trait;

use Backdoor\QueryBuilder\Exception\QueryBuilderException;

trait GetTableTrait
{
    private string $table;

    /**
     * @throws QueryBuilderException
     * @return string
     */
    public function getTable(): string
    {
        if (isset($this->table)) {
            return $this->table;
        }
        throw new QueryBuilderException("Таблица не указана.");
    }
}