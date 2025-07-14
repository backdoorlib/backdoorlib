<?php

declare(strict_types=1);

namespace Backdoor\QueryBuilder\Trait;
use Backdoor\Helper\Util\StringUtil;

trait ParamsBuilderTrait
{
    private array $params;
    
    public function addParam(int|string $key, mixed $param) : static
    {
        $this->params[StringUtil::clearSpaces($key)] = $param;
        return $this;
    }

    public function addParams(array $params = []) : static
    {
        foreach ($params as $key => $param) {
            $this->addParam($key, $param);
        }
        return $this;
    }
    
    public function getParams(): array
    {
        return $this->params;
    }
}
