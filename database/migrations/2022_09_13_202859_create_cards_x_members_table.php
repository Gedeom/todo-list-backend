<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsXMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards_x_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->references('id')->on('cards')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->references('id')->on('users')->constrained()->restrictOnDelete();
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
        Schema::dropIfExists('cards_x_members');
    }
}
