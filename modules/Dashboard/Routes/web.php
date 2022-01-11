<?php

        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::redirect('/', '/dashboard');
            Route::prefix('dashboard')->group(function () {
                Route::get('/', 'DashboardController@index')->name('tenant.dashboard.index');
                Route::get('filter', 'DashboardController@filter');
                Route::post('data', 'DashboardController@data');
                Route::post('data_aditional', 'DashboardController@data_aditional');
                Route::get('stock-by-product/records', 'DashboardController@stockByProduct');
                Route::post('utilities', 'DashboardController@utilities');
            });
        });

