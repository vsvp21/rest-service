<?php

$route->get('/promotions', 'PromotionController@show');
$route->get('/promotions/links', 'PromotionController@index');
$route->put('/promotions/random', 'PromotionController@randomUpdate');
$route->post('/process/csv', 'ProcessController@store');