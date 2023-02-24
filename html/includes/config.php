<?php

/*
* Used to store website configuration information
* @param string or null $key
*/

function config($key=""){
    $config = [
        'db' => [
            "servername" => "mysql-server",
            "username" => "root",
            "password" => "secret",
            "dbName" => "ski_lift_app"
        ]
        // 'db' => [
        //     "servername" => "localhost",
        //     "username" => "root",
        //     "password" => "",
        //     "dbName" => "ski_lift_app"
        // ]
    ];
    return isset($config[$key])? $config[$key] : null;
}