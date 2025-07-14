<?php

declare(strict_types=1);

namespace Backdoor\Bag\InputBag;

use Backdoor\Bag\InputBag;
use Backdoor\Helper\Util\StringUtil;

class EnvBag extends InputBag
{
    public const KEY_APP_ENV = "APP_ENV";
    protected array $data;

    public function __construct()
    {
        $this->data = $_ENV;
    }

    public function isDev(): bool
    {
        return StringUtil::upper($this->getStr(self::KEY_APP_ENV, "PROD")) === "DEV";
    }
}