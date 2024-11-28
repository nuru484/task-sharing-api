<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharedTaskListsTable extends Migration
{
    public function up()
    {
        Schema::create('shared_task_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_list_id')
                ->constrained('task_lists')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('permission'); // 'view', 'edit'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shared_task_lists');
    }
}
