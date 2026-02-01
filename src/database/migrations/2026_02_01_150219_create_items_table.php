<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('seller_user_id');
            $table->bigInteger('purchaser_user_id')->nullable();
            $table->string('item_name');
            $table->string('brand_name')->nullable();
            $table->integer('price');
            $table->bigInteger('condition_id');
            $table->string('detail');
            $table->string('item_path');
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
        Schema::dropIfExists('items');
    }
}
