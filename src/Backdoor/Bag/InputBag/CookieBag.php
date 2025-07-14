<?php

declare(strict_types=1);

namespace Backdoor\Bag\InputBag;

use Backdoor\Bag\InputBag;

class CookieBag extends InputBag
{
    protected array $data;
    public function __construct()
    {
        $this->data = &$_COOKIE;
    }
}