<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoardsXActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boards_x_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->references('id')->on('boards')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->references('id')->on('users')->constrained()->restrictOnDelete();
            $table->string('description', 2000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boards_x_activities');
    }
}
