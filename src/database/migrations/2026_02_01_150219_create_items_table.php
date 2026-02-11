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
            $table->bigInteger('user_id');
            $table->string('item_name');
            $table->string('brand_name')->nullable();
            $table->integer('price');
            $table->tinyInteger('condition_id')->comment('1:良好 2:目立った傷や汚れなし 3:やや傷や汚れあり 4:状態が悪い');
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
