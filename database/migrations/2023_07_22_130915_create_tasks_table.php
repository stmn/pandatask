<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id')->index();
            $table->unsignedBigInteger('author_id')->index();
            $table->unsignedBigInteger('status_id')->nullable()->index();
            $table->unsignedBigInteger('priority_id')->nullable()->index();
            $table->unsignedBigInteger('latest_activity_id')->nullable()->index();
            $table->timestamp('latest_activity_at')->nullable()->index();
            $table->unsignedBigInteger('number')->index();
            $table->string('subject', 200);
            $table->text('description')->nullable();
            $table->boolean('private');
            $table->json('tags')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->tinyInteger('billable')->default(0);
            $table->unsignedBigInteger('estimation')->nullable();
            $table->json('custom_fields')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
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
};
