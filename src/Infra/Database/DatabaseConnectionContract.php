<?php

declare(strict_types=1);

namespace Thiiagoms\SRP\Infra\Database;

use PDO;

interface DatabaseConnectionContract
{
    public function connect(): void;

    public function disconnect(): void;

    public function getConnection(): PDO;
}
