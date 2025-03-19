<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;
use App\Models\Canteen;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default categories if they don't exist
        $defaultCategories = [
            'Main Course', 'Appetizer', 'Dessert', 'Beverage', 'Snack'
        ];
        
        foreach ($defaultCategories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
        
        // Create a seller user if none exists
        $seller = User::where('role', 'seller')->first();
        
        if (!$seller) {
            $seller = User::create([
                'name' => 'Test Seller',
                'email' => 'seller@example.com',
                'password' => bcrypt('password'),
                'role' => 'seller',
            ]);
            
            // Create a canteen for the seller
            Canteen::create([
                'name' => 'Test Canteen',
                'description' => 'This is a test canteen for demonstration purposes.',
                'user_id' => $seller->id,
                'status' => true,
            ]);
        }
        
        // Create a buyer user if none exists
        $buyer = User::where('role', 'buyer')->first();
        
        if (!$buyer) {
            User::create([
                'name' => 'Test Buyer',
                'email' => 'buyer@example.com',
                'password' => bcrypt('password'),
                'role' => 'buyer',
            ]);
        }
    }
}

