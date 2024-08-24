<?php

declare(strict_types=1);

namespace Thiiagoms\SRP\Repositories\Product;

use Thiiagoms\SRP\Entities\Product;
use Thiiagoms\SRP\Infra\Database\DatabaseConnectionContract;

class ProductRepository implements ProductContract
{
    public function __construct(private readonly DatabaseConnectionContract $connection) {}

    public function findById(string $id): Product
    {
        $query = 'SELECT * FROM products WHERE id = :id';

        $stmt = $this->connection->getConnection()->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return Product::from($stmt->fetch());
    }
}
