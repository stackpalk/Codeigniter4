<?php 
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'user';  // model realated to which table
    protected $primaryKey = 'id'; // primary key of the table

    //protected $returnType     = 'array';  // model return type of qury done on this model
    protected $useSoftDeletes = false; // if using soft delete you need to mention the soft delete colomn in table   protected $deletedField  = 'deleted_at';

    protected $allowedFields = ['username', 'email','password'] ;// onlhy these colomn are allowed in insert and update

    protected $useTimestamps = true;  // timestamp to be used in created at updated at adn delted at in case of soft delete
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
   // protected $deletedField  = 'deleted_at';  // used if the soft deltete is true and have a colomn delte_at in table

   protected $beforeInsert=['beforeInsert'];// callback function that will run on before every insert on this table
   protected $beforeUpdate=['beforeUpdate'];// similar to above call back



    protected function beforeInsert(array $data){
        if(isset($data['data']['password'])){
            $data['data']['password']= password_hash($data['data']['password'],PASSWORD_DEFAULT);
            return $data;
        }
        
    }

    protected function beforeUpdate(array $data){
        if(isset($data['data']['password'])){
            $data['data']['password']= password_hash($data['data']['password'],PASSWORD_DEFAULT);
            return $data;
        }
    }



}

?>