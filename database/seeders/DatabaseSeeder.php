<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SettingSeeder::class);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'phone' => '012345678',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $customerUser = User::create([
            'name' => 'Sokha',
            'email' => 'customer@customer.com',
            'phone' => '098765432',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        Customer::create([
            'user_id' => $customerUser->id,
            'address' => '#123, Street 456, Sangkat TTP',
            'city' => 'Phnom Penh',
            'note' => 'Near the market',
        ]);

        User::create([
            'name' => 'Dara',
            'email' => 'delivery@delivery.com',
            'phone' => '011223344',
            'password' => Hash::make('password'),
            'role' => 'delivery',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Rithy',
            'email' => 'rithy@delivery.com',
            'phone' => '015566778',
            'password' => Hash::make('password'),
            'role' => 'delivery',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $vegetables = Category::create(['category_name' => 'Fresh Vegetables', 'description' => 'Fresh and organic vegetables', 'status' => 'active']);
        $fruits = Category::create(['category_name' => 'Fresh Fruits', 'description' => 'Seasonal fresh fruits', 'status' => 'active']);
        $meat = Category::create(['category_name' => 'Meat & Poultry', 'description' => 'Fresh meat and chicken', 'status' => 'active']);
        $seafood = Category::create(['category_name' => 'Seafood', 'description' => 'Fresh fish and seafood', 'status' => 'active']);
        $dairy = Category::create(['category_name' => 'Dairy & Eggs', 'description' => 'Milk, cheese, eggs', 'status' => 'active']);
        $rice = Category::create(['category_name' => 'Rice & Noodles', 'description' => 'Rice, noodles, pasta', 'status' => 'active']);
        $beverages = Category::create(['category_name' => 'Beverages', 'description' => 'Drinks and juices', 'status' => 'active']);
        $condiments = Category::create(['category_name' => 'Condiments & Spices', 'description' => 'Sauces, spices, seasonings', 'status' => 'active']);

        $this->createProduct($vegetables->id, 'Fresh Chinese Cabbage', 'Locally grown Chinese cabbage', 1.50, 'kg', 'cabbage.jpg', 'Local Farm');
        $this->createProduct($vegetables->id, 'Morning Glory (Trokuon)', 'Fresh water spinach', 0.80, 'bunch', 'morning_glory.jpg', 'Local Farm');
        $this->createProduct($vegetables->id, 'Tomatoes', 'Ripe red tomatoes', 2.00, 'kg', 'tomato.jpg', 'Local Farm');
        $this->createProduct($vegetables->id, 'Cucumber', 'Fresh green cucumber', 1.20, 'kg', 'cucumber.jpg', 'Local Farm');
        $this->createProduct($vegetables->id, 'Carrots', 'Fresh orange carrots', 1.80, 'kg', 'carrot.jpg', 'Imported');
        $this->createProduct($fruits->id, 'Bananas', 'Fresh local bananas', 1.50, 'bunch', 'banana.jpg', 'Local Farm');
        $this->createProduct($fruits->id, 'Mangoes', 'Sweet ripe mangoes', 2.50, 'kg', 'mango.jpg', 'Local Farm');
        $this->createProduct($fruits->id, 'Dragon Fruit', 'Red dragon fruit', 3.00, 'kg', 'dragon_fruit.jpg', 'Local Farm');
        $this->createProduct($fruits->id, 'Oranges', 'Juicy oranges', 2.00, 'kg', 'orange.jpg', 'Imported');
        $this->createProduct($fruits->id, 'Coconuts', 'Fresh young coconuts', 1.00, 'piece', 'coconut.jpg', 'Local Farm');
        $this->createProduct($meat->id, 'Chicken Breast', 'Fresh boneless chicken breast', 5.00, 'kg', 'chicken_breast.jpg', 'Angkor Poultry');
        $this->createProduct($meat->id, 'Pork Belly', 'Fresh pork belly', 6.50, 'kg', 'pork_belly.jpg', 'Local Farm');
        $this->createProduct($meat->id, 'Beef Tenderloin', 'Premium beef tenderloin', 12.00, 'kg', 'beef.jpg', 'Imported');
        $this->createProduct($meat->id, 'Whole Chicken', 'Farm fresh whole chicken', 4.50, 'kg', 'whole_chicken.jpg', 'Angkor Poultry');
        $this->createProduct($seafood->id, 'Fresh Tilapia', 'Whole fresh tilapia fish', 3.50, 'kg', 'tilapia.jpg', 'Local Fishery');
        $this->createProduct($seafood->id, 'Shrimp', 'Fresh peeled shrimp', 8.00, 'kg', 'shrimp.jpg', 'Local Fishery');
        $this->createProduct($seafood->id, 'Squid', 'Fresh cleaned squid', 6.00, 'kg', 'squid.jpg', 'Local Fishery');
        $this->createProduct($dairy->id, 'Fresh Milk 1L', 'Fresh cow milk 1 liter', 2.50, 'liter', 'milk.jpg', 'Dairy Brand');
        $this->createProduct($dairy->id, 'Eggs (10 pcs)', 'Farm fresh chicken eggs', 2.00, 'pack', 'eggs.jpg', 'Local Farm');
        $this->createProduct($dairy->id, 'Yogurt', 'Plain yogurt 400g', 1.50, 'cup', 'yogurt.jpg', 'Dairy Brand');
        $this->createProduct($rice->id, 'Jasmine Rice 5kg', 'Premium Cambodian jasmine rice', 7.00, 'bag', 'rice.jpg', 'Local Mill');
        $this->createProduct($rice->id, 'Instant Noodles', 'Instant noodle pack 30 pcs', 4.00, 'pack', 'noodles.jpg', 'Imported');
        $this->createProduct($rice->id, 'Rice Noodles', 'Fresh rice noodles 1kg', 2.00, 'kg', 'rice_noodles.jpg', 'Local');
        $this->createProduct($beverages->id, 'Coconut Water', 'Fresh coconut water 500ml', 1.50, 'bottle', 'coconut_water.jpg', 'Local');
        $this->createProduct($beverages->id, 'Soy Milk', 'Soy milk 250ml', 1.00, 'pack', 'soy_milk.jpg', 'Local');
        $this->createProduct($beverages->id, 'Sugar Cane Juice', 'Fresh sugar cane juice 500ml', 1.00, 'bottle', 'sugarcane.jpg', 'Local');
        $this->createProduct($condiments->id, 'Fish Sauce', 'Premium fish sauce 500ml', 2.00, 'bottle', 'fish_sauce.jpg', 'Local');
        $this->createProduct($condiments->id, 'Soy Sauce', 'Dark soy sauce 500ml', 1.80, 'bottle', 'soy_sauce.jpg', 'Imported');
        $this->createProduct($condiments->id, 'Prahok', 'Traditional fermented fish paste', 3.00, 'jar', 'prahok.jpg', 'Local');
        $this->createProduct($condiments->id, 'Salt', 'Fine table salt 1kg', 0.80, 'pack', 'salt.jpg', 'Local');
        $this->createProduct($condiments->id, 'Sugar', 'White sugar 1kg', 1.20, 'kg', 'sugar.jpg', 'Local');
    }

    private function createProduct($categoryId, $name, $description, $price, $unit, $image, $brand)
    {
        $product = Product::create([
            'category_id' => $categoryId,
            'product_name' => $name,
            'description' => $description,
            'price' => $price,
            'unit' => $unit,
            'image' => $image,
            'brand' => $brand,
            'status' => 'active',
        ]);

        Inventory::create([
            'product_id' => $product->id,
            'qty_in_stock' => rand(20, 100),
            'reorder_level' => 10,
            'last_updated' => now(),
        ]);
    }
}
