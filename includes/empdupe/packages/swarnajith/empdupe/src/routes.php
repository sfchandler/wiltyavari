<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 5/02/2019
 * Time: 1:25 PM
 */
Route::get('/', function(){
    echo 'Empdupe package!';
});
Route::get('generate', 'Swarnajith\Empdupe\EmpdupeController@generate');
Route::get('add/{a}/{b}', 'Swarnajith\Empdupe\EmpdupeController@add');
Route::get('subtract/{a}/{b}', 'Swarnajith\Empdupe\EmpdupeController@subtract');