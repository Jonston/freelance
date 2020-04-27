<?php

$router->get('/projects/:number?', 'IndexController@index');
$router->get('/projects/info', 'IndexController@info');


