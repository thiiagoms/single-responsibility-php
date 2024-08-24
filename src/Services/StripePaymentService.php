<?php

declare(strict_types=1);

namespace Thiiagoms\SRP\Services;

class StripePaymentService
{
    public function process(string $total): string
    {
        return "Processing payment of £{$total} through Stripe";
    }
}
