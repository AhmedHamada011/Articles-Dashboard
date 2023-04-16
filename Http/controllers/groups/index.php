<?php

use Core\App;
use Core\Database;
use Core\Logger;
use Core\Response;

$db = App::resolve(Database::class);

if(isset($_GET["search"])){
    $groups = $db->query('select * from `groups` where is_deleted is null and (name like :search or description like :search)',["search"=>'%'.$_GET["search"].'%'])->get();
    
}else{
    $groups = $db->query('select * from `groups` where is_deleted is null')->get();
}

view("groups/index.view.php",[
    "groups"=>$groups
]);

