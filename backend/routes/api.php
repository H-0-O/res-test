<?php

use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomTypeController;
use Illuminate\Support\Facades\Route;

Route::prefix("room-types")->group(function(){
    Route::get("/" , [RoomTypeController::class , 'index']);
    Route::get("/{roomTypeId}" , [RoomTypeController::class , 'show']);
    Route::post('/' , [RoomTypeController::class , 'store']);
    Route::put('/{roomTypeId}' , [RoomTypeController::class , 'update']);
    Route::delete('/{id}', [RoomTypeController::class ,'destroy']);
});

Route::prefix('reservations')->group(function(){
    Route::post('/' , [ReservationController::class , 'reserve']);
});