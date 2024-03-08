<?php

use Illuminate\Database\Capsule\Manager as Capsule;


require_once("DbHandlerInterface.php");

class DBHandler implements DbHandlerInterface
{
    private $_capsule;
    public function __construct()
    {
        $this->_capsule = new Capsule();
    }
    public function connect()
    {
        try {
            $this->_capsule->addConnection([
                'driver' => __DBDRIVER__,
                'host' => __HOST__,
                'database' => __DB__,
                'username' => __USER__,
                'password' => __PASS__,
            ]);
            $this->_capsule->setAsGlobal();
            $this->_capsule->bootEloquent();
            return true;
        } catch (\Exception $e) {
            error_log('Database connection error: ' . $e->getMessage());
            return false;
        }
    }
    public function get_data($fields = array(),  $start = 0)
    {
        try {

            return empty($fields) ? Items::skip($start)->take(5)->get() : Items::skip($start)->take(5)->get($fields);
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
    public function disconnect()
    {
        try {
            Capsule::disconnect();
            return true;
        } catch (\Exception $e) {
            error_log('Error disconnecting from the database: ' . $e->getMessage());
            return false;
        }
    }
    public function get_record_by_id($id, $primary_key)
    {
        try {
            return Items::where($primary_key, '=', $id)->get();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
    public function search_by_column($name_column, $value)
    {
        try {
            return Items::where($name_column, 'like', "%$value%")->get();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
    public function get_count_items()
    {
        return Items::count();
    }
}
