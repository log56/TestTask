<?php

namespace Tests\Unit\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\Order\ShowOrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowOrderTest extends TestCase
{
    use RefreshDatabase;

    private ShowOrderService $service;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = new ShowOrderService();
        
       
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
        
        
        $order1 = new Order();
        $order1->user_id = $this->user->id;
        $order1->status = 'completed';
        $order1->comment = 'comment';
        $order1->total_price = 300;
        $order1->save();
        $order1->products()->attach($product1, [
            'quantity' => 2,
            'unit_price' => 100
        ]);
        $order1->products()->attach($product2, [
            'quantity' => 1,
            'unit_price' => 100  
        ]);
    }

  
    public function test_it_retrieves_user_orders_with_products()
    {
        $orders = $this->service->show($this->user->id);
        
       
        $this->assertCount(1, $orders);
        $this->assertEquals('completed', $orders->first()->status);
        $this->assertEquals(300, $orders->first()->total_price);
        
    
        $products = $orders->first()->products;
        $this->assertCount(2, $products);
        
       
        $this->assertEquals(2, $products->first()->pivot->quantity);
        $this->assertEquals(100, $products->first()->pivot->unit_price);
    }

   
    public function test_it_returns_empty_collection_for_nonexistent_user()
    {
        $orders = $this->service->show(9999); 
        $this->assertCount(0, $orders);
    }

    
    public function test_it_orders_results_by_created_at_desc()
    {
        
        $order2 = new Order();
        $order2->user_id = $this->user->id;
        $order2->status = 'pending';
        $order2->comment = 'comment';
        $order2->total_price = 150; 
        $order2->created_at = now()->subDay();
        $order2->save();
        
        $orders = $this->service->show($this->user->id);
        
        $this->assertCount(2, $orders);
        $this->assertEquals('completed', $orders->first()->status); 
        $this->assertEquals('pending', $orders->last()->status); 
    }
}