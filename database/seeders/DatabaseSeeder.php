<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\BoardList;
use App\Models\BoardMember;
use App\Models\Card;
use App\Models\CardMember;
use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create(['name' => 'Admin', 'email' => 'admin@email.com', 'password' => bcrypt('12345678'), 'is_admin' => 1]);
        User::create(['name' => 'Usuário normal', 'email' => 'user@email.com', 'password' => bcrypt('12345678'), 'is_admin' => 0]);

        if (config('app.env') != 'production') {
//            User::factory(30)->create();
            Board::factory(10)
                ->hasMembers(2)
                ->create()
                ->each(function ($board) {
                    BoardList::insert([
                            ['board_id' => $board->id, 'description' => 'Aguardando', 'sequence' => 1, 'archived' => 0],
                            ['board_id' => $board->id, 'description' => 'Em Andamento', 'sequence' => 2, 'archived' => 0],
                            ['board_id' => $board->id, 'description' => 'Pendência', 'sequence' => 3, 'archived' => 0],
                            ['board_id' => $board->id, 'description' => 'Finalizado', 'sequence' => 4, 'archived' => 0],
                        ]
                    );
                });

            Category::factory(10)->create();
            Project::factory(10)->create();

            Card::factory(30)
                ->hasMembers(2)
                ->create();
        }

    }
}
