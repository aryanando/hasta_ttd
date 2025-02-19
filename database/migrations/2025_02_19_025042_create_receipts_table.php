<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_name')->default('RS BHAYANGKARA TK. III HASTA BRATA BATU');
            $table->string('received_from')->default('RS BHAYANGKARA HASTA BRATA BATU');
            $table->decimal('amount', 15, 2);
            $table->text('amount_in_words');
            $table->text('payment_purpose');
            $table->string('recipient');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('receipts');
    }
};
