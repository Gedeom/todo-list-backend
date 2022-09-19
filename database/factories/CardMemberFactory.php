<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\CardMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardMemberFactory extends Factory
{
    protected $model = CardMember::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $card_id = $this->faker->randomElement(Card::all()->pluck('id','id')->toArray());

//        $member_id =  $this->faker->randomElement(User::doesntHave('cards', function($q) use($card_id){
//            $q->where('id','<>',$card_id);
//        })->get()->pluck('id','id'));
//
//        if(!$member_id){
//            return [];
//        }

        return [
            'card_id' => $card_id,
            'user_id' => User::all()->random()
        ];
    }
}
