<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalysissupresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('analysis_supres', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('symbol');
			$table->double('support', 15, 3);
			$table->double('resistance', 15, 3);
			$table->double('difference', 15, 3);
			$table->integer('report_id');
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
		Schema::drop('analysis_supres');
	}

}
