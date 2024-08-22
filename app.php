<?php

declare(strict_types=1);

use App\NotFoundException;

class OrderService
{

    private function getConnection(): PDO
    {
        return new PDO("mysql:host=127.0.0.1;dbname=solid", "root", "");
    }

    public function execute(string $productId, array $request): array
    {
        $connection = $this->getConnection();

        // DB::table('products')->where('id', $productId)->first();
        $product = $connection->query("SELECT * FROM products WHERE id = '{$productId}'")->fetch(PDO::FETCH_OBJ);

        $stock = $connection->query("SELECT * FROM stocks WHERE product_id = '{$productId}'")->fetch(PDO::FETCH_OBJ);

        if ($stock->quantity < 1) {
            throw new NotFoundException('We are out of stock');
        }

        $total = $this->applySpecialDiscount($product);

        $paymentSuccessMessage = '';

        if (array_key_exists('payment_method', $request) && $request['payment_method'] === 'stripe') {
            $paymentSuccessMessage = $this->processPaymentViaStripe('stripe', $total);
        }

        if (!empty($paymentSuccessMessage)) {

            // update Stock
            $quantity = $stock->quantity - 1;

            $connection->query("UPDATE stocks SET quantity = {$quantity} WHERE product_id = '{$productId}'");

            return [
                'payment_message' => $paymentSuccessMessage,
                'discounted_price' => $total,
                'original_price'  => $product->price,
                'message' => 'Thank you, your order is being processed'
            ];
        }
    }

    protected function processPaymentViaStripe($provider, $total)
    {
        $price = "£{$total}";
        return 'Processing payment of ' . $price . ' through ' . $provider;
    }

    protected function applySpecialDiscount($product)
    {
        $discount = 0.20 * $product->price;
        return number_format($product->price - $discount, 2);
    }
}

$orderProcess = new OrderService();

$result = $orderProcess->execute('9cd0e8e7-7b2d-47de-b587-6907c574c7ba', [
    'payment_method' => 'stripe'
]);

var_dump($result);
