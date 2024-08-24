<?php

declare(strict_types=1);

namespace Thiiagoms\SRP\Repositories\Product;

use Thiiagoms\SRP\Entities\Product;

interface ProductContract
{
    public function findById(string $id): Product;
}
