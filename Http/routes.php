<?php

Route::group(['middleware' => ['web']], function () {

    Route::group(['namespace' => 'NineCells\Page\Http\Controllers'], function() {

        Route::group(['prefix' => 'pages'], function() {

            Route::get('/{key?}', 'PageController@GET_page');
            Route::get('/{key}/edit', 'AdminController@GET_edit_page_form');
            Route::get('/{key}/history', 'AdminController@GET_page_history');
            Route::get('/{key}/compare/{left}/{right}', 'AdminController@GET_page_compare');
            Route::get('/{key}/{rev}', 'AdminController@GET_rev_page');
            Route::put('/update', 'AdminController@PUT_edit_page_form');
        });
    });
});
