<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Task;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->tinyInteger('team')->index()->nullable();
            $table->tinyInteger('type')->index(Task::type_bug);
            $table->tinyInteger('status')->index()->default(Task::status_to_do);
            $table->tinyInteger('priority')->index(Task::priority_medium);
            $table->text('subject')->nullable();
            $table->text('description')->nullable();
            $table->text('steps_to_reproduce')->nullable();
            $table->json('files')->nullable();
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
        Schema::dropIfExists('tasks');
    }
}
