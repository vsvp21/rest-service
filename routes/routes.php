<?php

$route->get('/upload/csv', 'UploadController@getCsv');
$route->post('/process/csv', 'ProcessController@postCsv');
$route->put('/asd', 'UploadController@putCsv');