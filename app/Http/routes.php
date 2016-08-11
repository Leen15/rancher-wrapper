<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::group(array('prefix' => 'api'), function() //, 'middleware' => 'auth0.jwt' , 'after' => 'add_token'
{
    Route::get('/ping', ['as' => 'api', 'uses' => 'WelcomeController@api']);




    Route::get('/hosts', ['as' => 'api', 'uses' => 'HostController@index']);
    Route::get('/hosts/{id}', ['as' => 'api', 'uses' => 'HostController@show']);


    Route::get('/stacks', ['as' => 'api', 'uses' => 'StackController@index']);
    Route::get('/stacks/{id}', ['as' => 'api', 'uses' => 'StackController@show']);
    Route::get('/stacks/{stack_id}/services', ['as' => 'api', 'uses' => 'ServiceController@index_by_stack']);
    Route::get('/stacks/{stack_id}/services/{id}', ['as' => 'api', 'uses' => 'ServiceController@show_by_stack']);


    Route::get('/services', ['as' => 'api', 'uses' => 'ServiceController@index']);
    Route::get('/services/{id}', ['as' => 'api', 'uses' => 'ServiceController@show']);
    Route::get('/services/{id}/upgrade', ['as' => 'api', 'uses' => 'ServiceController@upgrade']);
    Route::get('/services/{id}/finish_upgrade', ['as' => 'api', 'uses' => 'ServiceController@finish_upgrade']);
    Route::get('/services/{id}/scale/{scale_count}', ['as' => 'api', 'uses' => 'ServiceController@scale']);


    /*Route::resource('/nodes','NodesController',  [
        'only' => [
            'index', 'store', 'show', 'update', 'destroy'
        ]
    ]);

    Route::post('/nodes/associate', array('as' => 'nodes.associate', 		'uses' => 'NodesController@associate'));

    Route::get('/node_feeds', ['as' => 'api', 'uses' => 'NodeFeedsController@index']);
    Route::get('/node_feeds/set', ['as' => 'api', 'uses' => 'NodeFeedsController@setFeed']);
    Route::post('node_feeds/{id}/set', ['as' => 'api', 	'uses' => 'NodeFeedsController@feed_set']);
    Route::get('node_feeds/{feed_id}/logs', ['as' => 'api', 	'uses' => 'FeedLogsController@show']);


    Route::get('/actions', ['as' => 'api', 'uses' => 'ActionsController@index']);


    Route::get('/actionable_types', ['as' => 'api', 'uses' => 'FeedActionableTypesController@index']);
*/
});