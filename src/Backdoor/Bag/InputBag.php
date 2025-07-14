<?php

declare(strict_types=1);

namespace Backdoor\Bag;

use Backdoor\Bag\InputBag\CookieBag;
use Backdoor\Bag\InputBag\EnvBag;
use Backdoor\Bag\InputBag\ServerBag;
use Backdoor\Bag\InputBag\SessionBag;
use DateTimeImmutable;
use DateTimeInterface;

class InputBag
{
    protected array $data;

    public function __construct(array $data = []) {
        $this->data = $data;
    }

    public function empty(): bool
    {
        return empty($this->data);
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function has($keys): bool
    {
        $keys = $this->parseKeys($keys);
        $result = $this->data;
        foreach ($keys as $key) {
            if (isset($result[$key])){
                $result = $result[$key];
            } else {
                return false;
            }
        }
        return true;
    }

    public function get(array|string $keys, mixed $default = null) : mixed
    {
        $keys = $this->parseKeys($keys);
        $result = $this->data;
        foreach ($keys as $key) {
            if (isset($result[$key])){
                $result = $result[$key];
            } else {
                $result = $default;
            }
        }
        return $result;
    }

    public function getAll() : mixed
    {
        return $this->data;
    }

    public function getInt(array|string $keys, ?int $default = null) : ?int
    {
        $result = $this->get($keys, $default);
        if ($result === $default) {
            return $default;
        }else{
            return (int) $result;
        }
    }

    public function getStr(array|string $keys, ?string $default = null) : ?string
    {
        $result = $this->get($keys, $default);
        if ($result === $default) {
            return $default;
        }else{
            return (string) $result;
        }
    }

    public function getBool(array|string $keys, ?bool $default = null): ?bool
    {
        $result = $this->get($keys, $default);
        if ($result === $default) {
            return $default;
        }else{
            return (bool) $result;
        }
    }

    public function getDate(array|string $keys, ?DateTimeInterface $default = null): ?DateTimeInterface
    {
        $result = $this->getStr($keys, "now");
        if ($result === $default) {
            return $default;
        }else{
            return new DateTimeImmutable($result);
        }
    }

    public function query() : self
    {
        return new self($_GET);
    }

    public function files() : self
    {
        return new self($_FILES);
    }

    public function post() : self
    {
        return new self($_POST);
    }

    public function session() : SessionBag
    {
        return new SessionBag();
    }

    public function env() : EnvBag
    {
        return new EnvBag();
    }

    public function server() : ServerBag
    {
        return new ServerBag();
    }

    public function cookie() : CookieBag
    {
        return new CookieBag();
    }

    protected function parseKeys(array|string $keys) : array
    {
        if (is_string($keys)) $keys = explode(".", $keys);
        $keys = array_values($keys);
        return $keys;
    }
}