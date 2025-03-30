<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('category_id');
            $table->foreignId('from_account_id');
            $table->foreignId('to_account_id')->nullable(true);
            $table->foreignId('loan_id')->nullable(true);
            $table->string('type')->comment('income, expense, special_expense, transfer, loan_transfer');
            $table->integer('amount');
            $table->timestamps();

            $table->foreign(['user_id'])->references(['id'])->on('users');
            $table->foreign(['category_id'])->references(['id'])->on('categories');
            $table->foreign(['from_account_id'])->references(['id'])->on('accounts');
            $table->foreign(['to_account_id'])->references(['id'])->on('accounts');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
