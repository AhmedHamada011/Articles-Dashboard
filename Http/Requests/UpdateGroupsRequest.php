<?php
namespace Http\Requests;

use Core\App;
use Core\Database;
use Core\Validator;
use Core\Logger;


class UpdateGroupsRequest
{

    protected $errors=[];
    protected $db;

    public function __construct(){
        try{
            $this->db = App::resolve(Database::class);
            $this->rules();

            $this->rules();
        }catch(\Exception $e){
                Logger::error($e);
        }
    }


    public function rules(){

    /**
     *  check if group exist 
     */

    if (! isset($_POST['id'])) {
        abort(404);
    }
    $group=$this->db->query("select * from `groups` where id = :id",["id"=>$_POST['id']])->find();
    if(!$group){
        abort(404);
    }

    /**
     *  validate data
     */
    if (! Validator::string($_POST['name'], 4, 100)) {
        if(!empty($_POST['name'])){
            $this->errors['name'] = 'A Group name of no less than 4 characters is required.';
        }else{
            $this->errors['name'] = 'Group name should be provided.';
        }
    }

    /**
     *  check if icon exists in database
     */
    $isIcon=$this->db->query("select * from `icons` where name = :name",["name"=>$_POST['icon']])->find();
    if ($isIcon==false) {
        $this->errors['icon'] = 'Group should have an icon from menu.';
    }

    if (! Validator::string($_POST['description'], 10, 255)) {
        $this->errors['description'] = 'A Group description of no more than 255 and no less than 10 characters is required.';
    }

    /**
     *  check if group already exist
     */
    $group_name_exists=$this->db->query("select * from `groups` where name = :name and id!= :id ",[
    "name"=>$_POST['name'],
    "id"=>$_POST['id']]
    )->find();

    /**
     *  if group already exist add error message
     */
    if ($group_name_exists) {
        $this->errors['name'] = 'Group name should be unique.';
    }

        return $this->errors;
    }

    
    
    
}
