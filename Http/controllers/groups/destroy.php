<?php
use Core\App;
use Core\Database;
use Core\Validator;
$db = App::resolve(Database::class);


if(!isset($_POST["id"])){
    abort(404);
}
// change after users is finished to use user auth ToDo
/**
 * check if group not admin or editor before delete
 */
if(in_array($_POST["id"] ,[1,2])){
    abort(403);
}

$group = $db->query('select * from `groups` where id= :id',[
    "id"=>$_POST["id"]
])->findOrFail();

$db->query('update `groups` set is_deleted= :date where id= :id',[
    "id"=>$_POST["id"],
    "date"=>date("Y-m-d H:i:s")
]);

header('location: /groups');
die();