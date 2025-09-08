<?php
namespace Database;

use PDO;
use PDOException;

class Database{
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $port;
    private $dbType;
    protected $DB;
    function __construct(){
        $this->servername = getenv('SERVERNAME');
        $this->username   = getenv('USERNAME');
        $this->password   = getenv('PASSWORD');
        $this->dbname     = getenv('DBNAME');
        $this->port = getenv('PORT');
        $this->dbType = getenv('DBTYPE');
        $this->connectDB();
    }
    public function connectDB(){
        if($this->DB)return;
        try{
             $this->DB = new PDO("$this->dbType:host=$this->servername;dbname=$this->dbname;port=$this->port",$this->username,$this->password);
             $this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch (PDOException $e){
            $a=$e->getMessage();
            echo json_encode($a);
        }
        return ;
    }
    public function query($query,$param = null){
        if($this->DB){
            $stm = $this->DB->prepare($query);
            if($param != null){
                $stm->execute($param);
            }else{
                $stm->execute();
            }
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        }
    }

}
