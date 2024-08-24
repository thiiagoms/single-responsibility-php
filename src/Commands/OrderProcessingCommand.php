<?php

declare(strict_types=1);

namespace Thiiagoms\SRP\Commands;

use Thiiagoms\SRP\Services\OrderProcessingService;

class OrderProcessingCommand
{
    public function __construct(private readonly OrderProcessingService $orderProcessingService) {}

    public function execute(string $productId): array
    {
        $order = $this->orderProcessingService->execute($productId);

        return $order->toArray();
    }
}
