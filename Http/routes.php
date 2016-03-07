<?php

Route::group(['middleware' => ['web']], function () {

    Route::group(['namespace' => 'NineCells\Pages\Http\Controllers'], function() {

        Route::group(['prefix' => 'pages'], function() {

            Route::get('/{key?}', 'PagesController@GET_page');
            Route::get('/{key}/edit', 'PagesController@GET_page_form');
            Route::get('/{key}/history', 'PagesController@GET_page_history');
            Route::get('/{key}/compare/{left}/{right}', 'PagesController@GET_page_compare');
            Route::get('/{key}/{rev}', 'PagesController@GET_rev_page');
            Route::put('/update', 'PagesController@PUT_page_form');
        });
    });
});
