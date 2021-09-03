<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

// 分类
Route::get('goods_cat/list', 'GoodsCatController@list');
Route::get('goods_cat/ajaxList', 'GoodsCatController@ajaxList');
Route::any('goods_cat/edit', 'GoodsCatController@edit');
Route::post('goods_cat/del', 'GoodsCatController@del');
Route::post('goods_cat/saveShow', 'GoodsCatController@saveShow');
Route::post('goods_cat/saveRecommend', 'GoodsCatController@saveRecommend');
Route::post('goods_cat/saveField', 'GoodsCatController@saveField');

// 商品
Route::post('goods/xmSelect', 'GoodsController@xmSelect');
Route::get('goods/list', 'GoodsController@list');
Route::get('goods/ajaxList', 'GoodsController@ajaxList');
Route::any('goods/edit', 'GoodsController@edit');
Route::post('goods/del', 'GoodsController@del');
Route::post('goods/saveStatus', 'GoodsController@saveStatus');
Route::post('goods/saveQuick', 'GoodsController@saveQuick');
Route::any('goods/batchIntegral', 'GoodsController@batchIntegral');
Route::post('goods/saveSort', 'GoodsController@saveSort');
Route::post('goods/qrcode', 'GoodsController@qrcode');

