<?php

declare(strict_types=1);

namespace Backdoor\QueryBuilder\Builder;

use Backdoor\QueryBuilder\QueryBuilderInterface;
use Backdoor\QueryBuilder\Trait\WhereBuilderTrait;
use Backdoor\QueryBuilder\Trait\ParamsBuilderTrait;
use Backdoor\QueryBuilder\Trait\GetTableTrait;
use Backdoor\Helper\Util\StringUtil;

class SelectBuilder implements QueryBuilderInterface
{
    use WhereBuilderTrait, ParamsBuilderTrait, GetTableTrait;
    public const SORT_ASC = "ASC";
    public const SORT_DESC = "DESC";
    public const JOIN_INNER = "INNER JOIN";
    public const JOIN_LEFT = "LEFT JOIN";

    private array $select = [];
    private ?string $alias = null;
    private array $joins = [];
    private array $order = [];
    private array $groupBy = [];
    private ?int $limit = null;
    private ?int $offset = null;


    public function __construct() {}

    public function select(array|string $select = []) : static
    {
        if (is_string($select)) {
            $select = explode(", ", $select);
        }
        $select = array_values($select);
        $this->select = [...$this->select, ...$select];
        return $this;
    }

    public function from(string $table, string $as) : static
    {
        $this->table = $table;
        $this->alias = $as;
        return $this;
    }

    // Inner Join
    public function join(string $table, string $as, string $on, ?string $type = null) : static
    {
        $this->joins[] = [
            "type" => $type ?? self::JOIN_INNER,
            "table" => $table,
            "as" => $as,
            "on" => $on
        ];
        return $this;
    }

    public function leftJoin(string $table, string $as, string $on) : static
    {
        $this->join($table, $as, $on, self::JOIN_LEFT);
        return $this;
    }

    public function innerJoin(string $table, string $as, string $on) : static
    {
        $this->join($table, $as, $on, self::JOIN_INNER);
        return $this;
    }

    public function order(string $by, string $sort = self::SORT_ASC) : static
    {
        $sort = StringUtil::upper($sort);
        if (!in_array($sort, [self::SORT_ASC, self::SORT_DESC])) {
            $sort = self::SORT_ASC;
        }
        $this->order[] = [
            "by" => $by,
            "sort" => $sort
        ];
        return $this;
    }
    
    public function limit(int $limit, int $offset = 0) : static
    {
        $this->limit = max($limit, 1);
        $this->offset =  max($offset, 0);
        return $this;
    }
    
    public function groupBy(string|array $group) : static
    {
        if (is_string($group)) $group = [$group];
        $this->groupBy = [...$this->groupBy, ...$group];
        return $this;
    }

    public function getParams(): array
    {
        return $this->params ?? [];
    }

    public function buildCount() : string
    {
        $from = $this->getTable();
        if ($this->alias) $from .= " {$this->alias}";

        $sql = <<<SQL
        SELECT
        COUNT(*) as count
        FROM $from
        SQL;
        return $sql;
    }
    
    public function build() : string
    {
        $from = $this->getTable();
        if ($this->alias) $from .= " {$this->alias}";

        $this->select = array_filter($this->select, function($select) : bool {
            $select = trim($select);
            return !empty($select);
        });
        if (!empty($this->select)){
            $select = "    " . implode("," . PHP_EOL . "    ", $this->select);
        }else{
            $select = "    {$from}.*";
        }
        $join = [];
        foreach ($this->joins as $item) {
            $join[] = "{$item["type"]} {$item["table"]} {$item["as"]} ON {$item["on"]}";
        }
        $join = implode(PHP_EOL,  $join);

        $where = "    " . $this->whereClause();
        if (!empty(trim($where))){
            $where = "WHERE " . PHP_EOL . $where;
        }
        $order = "";
        $sorts = [];
        foreach ($this->order as $value) {
            if (isset($value["by"], $value["sort"])){
                $sorts[] = "{$value["by"]} {$value["sort"]}";
            }
        }
        if (!empty($sorts)){
            $order = "ORDER BY " . implode(", ", $sorts);
        }

        $limit = "";
        if ($this->limit > 0){
            $limit .= "LIMIT {$this->limit}";
            if ($this->offset !== null){
                $limit .= " OFFSET {$this->offset}";
            }
        }

        $group = "";
        if (!empty($this->groupBy)) {
            $group = "GROUP BY " . implode(", ", $this->groupBy);
        }

        $sql = <<<SQL
        SELECT
        $select
        FROM $from
        $join
        $where
        $group
        $order
        $limit
        SQL;
        return $sql;
    }
    
    public function __toString() : string
    {
        return $this->build();
    }
}