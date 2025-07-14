<?php

declare(strict_types=1);

namespace Backdoor\QueryBuilder;

use Backdoor\QueryBuilder\Builder;

/**
 * QueryBuilder Factory
 */
class QueryBuilder
{
    public function __construct() {}

    public function insert(string $table): Builder\InsertBuilder
    {
        return (new Builder\InsertBuilder())->insert($table);
    }

    public function select(array|string $select = []) : Builder\SelectBuilder
    {
        return (new Builder\SelectBuilder())->select($select);
    }

    public function update(string $table): Builder\UpdateBuilder
    {
        return (new Builder\UpdateBuilder())->update($table);
    }

    public function delete(string $table): Builder\DeleteBuilder
    {
        return (new Builder\DeleteBuilder())->from($table);
    }

    public function describe(string $table): Builder\DescribeBuilder
    {
        return (new Builder\DescribeBuilder())->describe($table);
    }

    public function createTable(string $table): Builder\CreateTableBuilder
    {
        return (new Builder\CreateTableBuilder())->create($table);
    }
}