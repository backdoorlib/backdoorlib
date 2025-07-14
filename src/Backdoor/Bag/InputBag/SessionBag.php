<?php

declare(strict_types=1);

namespace Backdoor\Bag\InputBag;

use Backdoor\Bag\InputBag;

class SessionBag extends InputBag
{
    protected array $data;
    
    public function __construct()
    {
        $this->data = &$_SESSION;
    }

    public function set(string|array $keys, mixed $value): void 
    {
        $keys = $this->parseKeys($keys);
        $result = &$this->data;
        foreach ($keys as $i => $key) {
            if ($i === count($keys) - 1) {
                $result[$key] = $value;
            } else {
                if (!isset($result[$key])) {
                    $result[$key] = [];
                }
                $result = &$result[$key];
            }
        }
    }

    public function isAuth(): bool
    {
        return $this->getBool("isAuth");
    }
}