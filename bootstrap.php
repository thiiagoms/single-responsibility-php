<?php

declare(strict_types=1);

use Thiiagoms\SRP\Commands\OrderProcessingCommand;
use Thiiagoms\SRP\ContainerDI;
use Thiiagoms\SRP\Infra\Database\DatabaseConnectionContract;
use Thiiagoms\SRP\Infra\Database\MySQLConnection;
use Thiiagoms\SRP\Repositories\Product\ProductContract;
use Thiiagoms\SRP\Repositories\Product\ProductRepository;
use Thiiagoms\SRP\Repositories\Stock\StockContract;
use Thiiagoms\SRP\Repositories\Stock\StockRepository;
use Thiiagoms\SRP\Services\DiscountService;
use Thiiagoms\SRP\Services\OrderProcessingService;
use Thiiagoms\SRP\Services\StripePaymentService;

if (php_sapi_name() !== 'cli') {
    echo '<h1>Only in CLI mode</h1>';
    exit;
}

require __DIR__.'/vendor/autoload.php';

$containerDI = new ContainerDI;

$containerDI->set(DatabaseConnectionContract::class, fn (): MySQLConnection => new MySQLConnection(
    dbHost: '127.0.0.1',
    dbName: 'solid',
    dbPort: 3306,
    dbUser: 'root',
    dbPass: '',
));

$containerDI->set(
    StockRepository::class,
    fn (ContainerDI $container): StockRepository => new StockRepository(
        $container->get(DatabaseConnectionContract::class)
    )
);

$containerDI->set(
    StockContract::class,
    fn (ContainerDI $container): StockRepository => $container->get(StockRepository::class)
);

$containerDI->set(
    ProductRepository::class,
    fn (ContainerDI $container): ProductRepository => new ProductRepository(
        $container->get(DatabaseConnectionContract::class)
    )
);

$containerDI->set(
    ProductContract::class,
    fn (ContainerDI $container): ProductRepository => $container->get(ProductRepository::class)
);

$containerDI->set(DiscountService::class, fn (): DiscountService => new DiscountService);

$containerDI->set(StripePaymentService::class, fn (): StripePaymentService => new StripePaymentService);

$containerDI->set(
    OrderProcessingService::class,
    fn (ContainerDI $container): OrderProcessingService => new OrderProcessingService(
        $container->get(ProductContract::class),
        $container->get(StockContract::class),
        $container->get(DiscountService::class),
        $container->get(StripePaymentService::class)
    )
);

$containerDI->set(
    OrderProcessingCommand::class,
    fn (ContainerDI $container): OrderProcessingCommand => new OrderProcessingCommand(
        $container->get(OrderProcessingService::class)
    )
);

/** @var OrderProcessingCommand $orderProcessingCommand */
$orderProcessingCommand = $containerDI->get(OrderProcessingCommand::class);
