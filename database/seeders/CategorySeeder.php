<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Servicios',
            ],
            [
                'name' => 'Entretenimiento',
            ],
            [
                'name' => 'Alimentación',
            ],
            [
                'name' => 'Ropa',
            ],
            [
                'name' => 'Cuidado personal',
            ],
            [
                'name' => 'Regalos',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
