<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            Author::all()->each(function ($author) {
                $author->books()->createMany(Book::factory()->count(rand(1, 3))->make()->toArray());
            });

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
