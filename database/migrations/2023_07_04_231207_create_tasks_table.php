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
            $table->unsignedBigInteger('project_id')->index('project_id');
            $table->unsignedBigInteger('author_id')->index('author_id');
            $table->unsignedBigInteger('status_id')->nullable()->index('status_id');
            $table->unsignedBigInteger('priority_id')->nullable()->index('priority_id_2');
            $table->unsignedBigInteger('latest_activity_id')->nullable()->index('latest_activity_id');
            $table->timestamp('latest_activity_at')->nullable();
            $table->unsignedBigInteger('number')->index('number');
            $table->string('subject', 200);
            $table->text('description')->nullable();
            $table->boolean('private');
            $table->json('tags')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->tinyInteger('billable_status')->default(0);
            $table->unsignedBigInteger('estimation')->nullable();
            $table->json('custom_fields')->nullable();
            $table->timestamp('created_at')->useCurrent()->index('created_at');
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();

            $table->index(['priority_id'], 'priority_id');
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
