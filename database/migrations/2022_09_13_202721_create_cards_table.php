<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('list_id')->references('id')->on('lists')->constrained()->restrictOnDelete();
            $table->foreignId('category_id')->references('id')->on('categories')->constrained()->restrictOnDelete();
            $table->foreignId('project_id')->references('id')->on('projects')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('description');
            $table->unsignedInteger('sequence');
            $table->dateTime('expected_date');
            $table->boolean('archived')->default(0);
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
        Schema::dropIfExists('cards');
    }
}
