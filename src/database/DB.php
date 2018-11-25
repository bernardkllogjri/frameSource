<?php


class DB{
    const FETCH_TYPE = \PDO::FETCH_OBJ;
    private static $connection = null;
    private $table = null;

    public static function init($db){
        static::$connection = $db;
    }

    public static function raw($statement,$data=[]){

        return static::prepare($statement,$data)['statement']->fetchAll(static::FETCH_TYPE) ?? [];
    }

    public static function table($table){
        $database = new self();
        $database->table = $table;
        return $database;
    }

    public function insert($data){
        $fields = implode(', ',array_keys($data));
        $placeholders = implode(', :',array_keys($data));
        return static::prepare(
            "INSERT INTO {$this->table} ({$fields}) VALUES (:{$placeholders})"
            ,$data
        )['status'];
    }

    public static function prepare($statement, $data){
        try{
            $prepared_statement = static::$connection->prepare($statement);
            $status = $prepared_statement->execute($data);
        }catch (Exception $e){
            die($e->getMessage());
        }
        return [
            'statement' => $prepared_statement,
            'status' => $status
        ];
    }

    public function find($id){
        return DB::raw("SELECT * FROM {$this->table} WHERE id = :id",['id' => $id])[0];
    }

    public function get(){
        return DB::raw("SELECT * FROM {$this->table} ");
    }

    public function delete($id){
        return static::prepare("DELETE FROM {$this->table} WHERE id = :id",['id' => $id])['status'];
    }

    public function update($id,$data){
        $statement = 'UPDATE '.$this->table.' set';
        $keys = count($data) - 1;
        $i=0;
        foreach($data as $key => $value){
            $statement.=" {$key} = :{$key}";
            if($i !== $keys){
                $statement.=",";
            }
            $i++;
        }
        $statement.=" WHERE id = {$id}";
        return static::prepare($statement,$data)['status'];
    }

}
