<?php

declare(strict_types=1);

namespace Thiiagoms\SRP\Repositories\Stock;

use Thiiagoms\SRP\Entities\Stock;

interface StockContract
{
    public function forProduct(string $productId): Stock;

    public function record(string $productId, int $quantity): void;
}
