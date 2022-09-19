<?php

namespace Database\Factories;

use App\Models\Board;
use App\Models\BoardMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoardMemberFactory extends Factory
{
    protected $model = BoardMember::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
//        $board_id = $this->faker->randomElement(Board::all()->pluck('id','id')->toArray());
//        $user_id = $this->faker->randomElement(User::leftJoin('boards_x_members as member','member.user_id','=','users.id')
//            ->where('member.board_id','<>', $board_id)
//            ->select('users.id')
//            ->get()
//            ->pluck('id','id')
//            ->toArray());
//
//        if(!$user_id)
//            return false;


        return [
            'board_id' => $this->faker->randomElement(Board::all()->pluck('id','id')->toArray()),
            'user_id' => $this->faker->randomElement(User::all()->pluck('id','id')->toArray()),
        ];
    }
}
