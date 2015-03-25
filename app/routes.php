<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{

	return View::make('hello');
});
Route::group(['prefix' => 'admin'],  function() 
{
	Route::get('migrate', function()
	{
		return View::make('admin.migrate');
	})->before('admin');

	Route::post('migrate', ['as' => 'migrate', 'uses' => 'MigrationController@migrate'])->before('admin');
});
Route::group(['prefix' => 'report'],  function() 
{
	Route::get('buy', ['uses' => 'ReportController@buySearch']);
	Route::get('sell', ['uses' => 'ReportController@sellSearch']);
	Route::get('sr', ['uses' => 'ReportController@support_resistance_generate']);

	Route::get('simulate/buy/{symbol}', ['uses' => 'SimulatorController@buySearch']);
	Route::get('simulate/sell/{symbol}', ['uses' => 'SimulatorController@sellSearch']);
});

