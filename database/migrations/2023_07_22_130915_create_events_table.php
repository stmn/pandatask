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
        Schema::create('events', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->unsignedBigInteger('author_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
};
