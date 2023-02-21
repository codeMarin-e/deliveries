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
        Schema::create('delivery_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('site_id');
            $table->string('type');
            $table->float('tax')->default(0);
            $table->float('new_tax')->default(0);
            $table->float('vat')->nullable();
            $table->string('overview')->nullable();
            $table->boolean('default')->default(0);
            $table->boolean('test_mode')->default(0);
            $table->boolean('active')->default(0);
            $table->integer('ord')->default(0);
            $table->timestamps();
        });

        Schema::create('delivery_methods_payment_methods', function (Blueprint $table) {
            $table->unsignedBigInteger('delivery_method_id');
            $table->unsignedBigInteger('payment_method_id');

            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade');
            $table->foreign('delivery_method_id')->references('id')->on('delivery_methods')->onDelete('cascade');

            $table->unique([
                'delivery_method_id', 'payment_method_id'
            ], 'delivery_payment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_methods_payment_methods');
        Schema::dropIfExists('delivery_methods');
    }
};
