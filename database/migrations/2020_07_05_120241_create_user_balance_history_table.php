<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUserBalanceHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_balance_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->integer('balance_before');
            $table->integer('balance_after');
            $table->string('activity', 255);
            $table->enum('type', ['credit', 'debit']);
            $table->string('ip', 25);
            $table->string('location', 255);
            $table->string('user_agent', 255);
            $table->string('author', 255);
            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_balance_history');
    }
}
