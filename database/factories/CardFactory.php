<?php

namespace Database\Factories;

use App\Models\BoardList;
use App\Models\BoardMember;
use App\Models\Card;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardFactory extends Factory
{
    protected $model = Card::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $list_id = $this->faker->randomElement(BoardList::all()->pluck('id','id')->toArray());

        return [
            'list_id' => $list_id,
            'category_id' => $this->faker->randomElement(Category::all()->pluck('id','id')->toArray()),
            'project_id' => $this->faker->randomElement(Project::all()->pluck('id','id')->toArray()),
            'name' => $this->faker->text(20),
            'description' => $this->faker->text(100),
            'sequence' => Card::getNextSequence($list_id),
            'expected_date' => $this->faker->dateTimeThisYear()->format('Y-m-d'),
            'archived' => $this->faker->boolean
        ];
    }
}
