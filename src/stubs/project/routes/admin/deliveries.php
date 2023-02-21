<?php

use App\Http\Controllers\Admin\DeliveryMethodController;
use App\Models\DeliveryMethod;

Route::group([
    'controller' => DeliveryMethodController::class,
    'middleware' => ['auth:admin', 'can:view,'.DeliveryMethod::class],
    'as' => 'deliveries.', //naming prefix
    'prefix' => 'deliveries', //for routes
], function() {
    Route::get('', 'index')->name('index');
    Route::post('', 'store')->name('store')->middleware('can:create,'.DeliveryMethod::class);
    Route::get('create', 'create')->name('create')->middleware('can:create,'.DeliveryMethod::class);
    Route::get('{chDeliveryMethod}/edit', 'edit')->name('edit');
    Route::get('{chDeliveryMethod}/move/{direction}', "move")->name('move')->middleware('can:update,chDeliveryMethod');
    Route::get('{chDeliveryMethod}', 'edit')->name('show');
    Route::patch('{chDeliveryMethod}', 'update')->name('update')->middleware('can:update,chDeliveryMethod');
    Route::delete('{chDeliveryMethod}', 'destroy')->name('destroy')->middleware('can:delete,chDeliveryMethod');

    // @HOOK_ROUTES
});
