<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_info', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('symbol');
			$table->string('name');
			$table->string('logo');
			$table->string('description');
			$table->string('link');
			$table->integer('simulated');
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
			Schema::drop('stock_info');
	}
}
