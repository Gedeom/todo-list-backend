<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesXUsersNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities_x_users_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->references('id')->on('boards_x_activities')->constrained()->restrictOnDelete();
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
        Schema::dropIfExists('activities_x_users_notifications');
    }
}
