<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockRecords extends Migration {

	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_records', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('symbol',100)->nullable();
			$table->date('date')->nullable();
			$table->double('open', 15,9)->nullable();
			$table->double('high', 15,9)->nullable();
			$table->double('low', 15,9)->nullable();
			$table->double('close', 15,9)->nullable();
			$table->integer('volume')->nullable();
			$table->double('nfbs', 15,9)->nullable();
			$table->string('trend', 10)->nullable();
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
		Schema::drop('stock_records');
	}
}
