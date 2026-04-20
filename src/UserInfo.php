<?php

namespace Lucinda\OAuth2;

final class UserInfo
{
    private string|int|float $id;
    private string $name;
    private string $email;

    public function __construct(string|int|float $id, string $name, string $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public function getId(): string|int|float
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}