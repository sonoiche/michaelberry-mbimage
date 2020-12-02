<?php

Route::group(['namespace' => 'MichaelBerry\MBImage\Http\Controllers'], function (){
  Route::resource('mbimage', 'MBImageController');
});