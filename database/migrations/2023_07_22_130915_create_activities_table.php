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
        Schema::create('activities', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->timestamp('created_at')->useCurrent()->index();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('project_id')->index();
            $table->unsignedBigInteger('task_id')->index();
            $table->unsignedBigInteger('comment_id')->nullable()->index();
            $table->string('type', 16);
            $table->json('details')->nullable();
            $table->boolean('private')->default(false);

            $table->index(['created_at'], 'created_at_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
};
