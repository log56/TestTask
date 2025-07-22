<?php

namespace Tests\Unit\Services;

use App\DTO\UserProductDTO;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\Order\StoreOrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreOrderTest extends TestCase
{
    use RefreshDatabase;

    private StoreOrderService $service;
    private User $user;
    private array $products = [];

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = new StoreOrderService();
        
        
        $this->user = new User();
        $this->user->username = 'testuser';
        $this->user->phone = '+1234567890';
        $this->user->adress = 'Test Address';
        $this->user->password = bcrypt('password');
        $this->user->save();
        
        
        $product1 = new Product();
        $product1->name = 'Product A';
        $product1->description = 'Description';
        $product1->category_id = 1;
        $product1->price = 100;
        $product1->availible = true;
        $product1->save();
        
        $product2 = new Product();
        $product2->name = 'Product B';
        $product2->description = 'Description';
        $product2->category_id = 1;
        $product2->price = 200;
        $product2->availible = true;
        $product2->save();
        
        $this->products = [$product1, $product2];
    }

    
    public function test_it_creates_order_with_products()
    {
        $orderData = new UserProductDTO(
            user_id: $this->user->id,
            comment: 'Test order',
            items: [
                (object)['id' => $this->products[0]->id, 'quantity' => 2],
                (object)['id' => $this->products[1]->id, 'quantity' => 1],
            ]
        );

        $order = $this->service->store($orderData);

        
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $this->user->id,
            'status' => 'pending',
            'total_price' => 400, // (2*100) + (1*200)
            'comment' => 'Test order'
        ]);

        
        $this->assertCount(2, $order->products);
        
        
        $this->assertDatabaseHas('order_product', [
            'order_id' => $order->id,
            'product_id' => $this->products[1]->id,
            'quantity' => 1,
            'unit_price' => 200
        ]);
        $this->assertDatabaseHas('order_product', [
        'order_id' => $order->id,
        'product_id' => $this->products[0]->id,
        'quantity' => 2,
        'unit_price' => 100
        ]);
    }

    
    public function test_it_throws_exception_for_nonexistent_product()
    {
        $orderData = new UserProductDTO(
            user_id: $this->user->id,
            comment: 'Invalid order',
            items: [
                (object)['id' => 9999, 'quantity' => 1], // not product
            ]
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Product not found: 9999');

        $this->service->store($orderData);
    }

    
    public function test_it_rolls_back_on_failure()
    {
        
        $initialOrdersCount = Order::count();
        $initialProductsCount = Product::count();

        try {
            $orderData = new UserProductDTO(
                user_id: $this->user->id,
                comment: 'Failing order',
                items: [
                    (object)['id' => $this->products[0]->id, 'quantity' => 1],
                    (object)['id' => 9999, 'quantity' => 1], // Will cause failure
                ]
            );
            
            $this->service->store($orderData);
        } catch (\Exception) {}

        
        $this->assertDatabaseCount('orders', $initialOrdersCount);
        $this->assertDatabaseCount('order_product', 0);
    }

    
    public function test_it_handles_zero_quantity_correctly()
    {
        $orderData = new UserProductDTO(
            user_id: $this->user->id,
            comment: 'Zero quantity order',
            items: [
                (object)['id' => $this->products[0]->id, 'quantity' => 0],
            ]
        );

        $order = $this->service->store($orderData);

        $this->assertEquals(0, $order->total_price);
        $this->assertEquals(
            0,
            $order->products->find($this->products[0]->id)->pivot->quantity
        );
    }

   
    public function test_it_calculates_total_price_correctly()
    {
        $orderData = new UserProductDTO(
            user_id: $this->user->id,
            comment: 'Price calculation test',
            items: [
                (object)['id' => $this->products[0]->id, 'quantity' => 3], // 3*100 = 300
                (object)['id' => $this->products[1]->id, 'quantity' => 2], // 2*200 = 400
            ]
        );

        $order = $this->service->store($orderData);
        
        $this->assertEquals(700, $order->total_price);
    }
}