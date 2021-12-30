<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function() {


            Route::get('categories', 'CategoryController@index')->name('tenant.categories.index');
            Route::get('categories/records', 'CategoryController@records');
            Route::get('categories/columns', 'CategoryController@columns');
            Route::get('categories/record/{category}', 'CategoryController@record');
            Route::post('categories', 'CategoryController@store');
            Route::delete('categories/{category}', 'CategoryController@destroy');

            Route::get('brands', 'BrandController@index')->name('tenant.brands.index');
            Route::get('brands/records', 'BrandController@records');
            Route::get('brands/record/{brand}', 'BrandController@record');
            Route::post('brands', 'BrandController@store');
            Route::get('brands/columns', 'BrandController@columns');
            Route::delete('brands/{brand}', 'BrandController@destroy');

            Route::get('colors', 'ColorController@index')->name('tenant.colors.index');
            Route::get('colors/records', 'ColorController@records');
            Route::get('colors/record/{color}', 'ColorController@record');
            Route::post('colors', 'ColorController@store');
            Route::get('colors/columns', 'ColorController@columns');
            Route::delete('colors/{color}', 'ColorController@destroy');

            Route::get('sizes', 'SizeController@index')->name('tenant.sizes.index');
            Route::get('sizes/records', 'SizeController@records');
            Route::get('sizes/record/{size}', 'SizeController@record');
            Route::post('sizes', 'SizeController@store');
            Route::get('sizes/columns', 'SizeController@columns');
            Route::delete('sizes/{size}', 'SizeController@destroy');

            Route::get('incentives', 'IncentiveController@index')->name('tenant.incentives.index');
            Route::get('incentives/records', 'IncentiveController@records');
            Route::get('incentives/record/{incentive}', 'IncentiveController@record');
            Route::post('incentives', 'IncentiveController@store');
            Route::get('incentives/columns', 'IncentiveController@columns');
            Route::delete('incentives/{incentive}', 'IncentiveController@destroy');

            Route::get('items/barcode/{item}', 'ItemController@generateBarcode');

            Route::post('items/import/item-price-lists', 'ItemController@importItemPriceLists');

            Route::prefix('item-lots')->group(function () {

                Route::get('', 'ItemLotController@index')->name('tenant.item-lots.index');
                Route::get('/records', 'ItemLotController@records');
                Route::get('/record/{record}', 'ItemLotController@record');
                Route::post('', 'ItemLotController@store');
                Route::get('/columns', 'ItemLotController@columns');
                Route::get('/export', 'ItemLotController@export');

            });

            Route::post('items/import/item-sets', 'ItemSetController@importItemSets');
            Route::post('items/import/item-sets-individual', 'ItemSetController@importItemSetsIndividual');


            Route::prefix('web-platforms')->group(function () {

                Route::get('', 'WebPlatformController@index');
                Route::get('/records', 'WebPlatformController@records');
                Route::get('/record/{brand}', 'WebPlatformController@record');
                Route::post('', 'WebPlatformController@store');
                Route::delete('/{record}', 'WebPlatformController@destroy');

            });


        });
    });
}
