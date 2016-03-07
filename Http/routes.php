<?php

Route::group(['middleware' => ['web']], function () {

    Route::group(['namespace' => 'NineCells\Pages\Http\Controllers'], function() {

        Route::group(['prefix' => 'pages'], function() {

            Route::get('/{key?}', 'PagesController@GET_page');
            Route::get('/{key}/edit', 'AdminController@GET_page_form');
            Route::get('/{key}/history', 'AdminController@GET_page_history');
            Route::get('/{key}/compare/{left}/{right}', 'AdminController@GET_page_compare');
            Route::get('/{key}/{rev}', 'AdminController@GET_rev_page');
            Route::put('/update', 'AdminController@PUT_page_form');
        });
    });
});
