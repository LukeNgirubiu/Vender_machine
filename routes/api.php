<?php
use App\Models\Slots;
use App\Models\Coins;
use App\Http\Controllers\Maintenance;
use App\Http\Controllers\Buying;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/products', function () {
    return Slots::all();
});
Route::get('/slots/all',[Maintenance::class,'show_slots'] );
Route::put('/slots/stock',[Maintenance::class,'update_stock']);
Route::put('/slots/stock/withdrawal',[Maintenance::class,'stock_withdrawal']);
Route::post('/slots/buy',[Buying::class,'buy']);
Route::put('/slots/update/price',[Maintenance::class,'update_price']);
Route::get('/coins/all',[Maintenance::class,'show_coins'] );
Route::put('/coins/add',[Maintenance::class,'add_coins'] );
Route::put('/coins/withrawal',[Maintenance::class,'cash_withdrawal'] );
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
?> 
