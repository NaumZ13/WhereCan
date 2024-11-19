<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Reply;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(RoleSeeder::class);
        $this->call(CategorySeeder::class);

        $clientRole = Role::where('name', 'client')->first();
        
        $clients = User::factory(10)->create(['role_id' => $clientRole->id]);
        $users = User::factory(10)->create(['role_id' => Role::where('name', 'user')->first()->id]);
        $categories = Category::all();
        
        Product::factory(100)
            ->state(function () use ($clients, $categories) {
                return [
                    'client_id' => $clients->random()->id,
                    'category_id' => $categories->random()->id,
                ];
            })
            ->has(
                Review::factory(15)
                    ->state(function () use ($users) {
                        return [
                            'user_id' => $users->random()->id,
                        ];
                    })
                    ->has(
                        Reply::factory(3)->state(function (array $attributes, Review $review) use ($users) {
                            return [
                                'user_id' => $users->random()->id,
                                'review_id' => $review->id,
                            ];
                        })
                    )
                )
        ->create();
        
        User::factory()->create([
            'name' => 'Naum Zdravkov',
            'email' => 'naumz@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'admin')->first()->id,
        ]);

        $client = User::factory()->create([
            'name' => 'Client Clientovski',
            'email' => 'client@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'client')->first()->id,
        ]);

        Product::factory(100)->create(['client_id' => $client->client->id]);
    }
}
