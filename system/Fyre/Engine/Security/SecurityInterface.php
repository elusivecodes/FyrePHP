<?php

namespace Fyre\Engine\Security;

interface SecurityInterface
{

    public function csrfHash(): string;
    public function csrfToken(): string;
    public function passwordHash(string $password, int $algorithm = PASSWORD_ARGON2I): string;
    public function passwordVerify(string $password, string $hash): bool;
    public function passwordStrength(string $password): int;
    public function sanitize(string $value): string;

}
