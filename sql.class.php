<?php

//require_once 'essentials.class.php';

class Sql
{
    private $pass;
    private $link;

    public $savedtables;

    function __construct($server,$user,$password,$db)
    {
        $this->link = mysqli_connect($server,$user,$password,$db);
    }

    public function read($table,$col = null,$where = null,$is = null,$log = true)
    {
        if($this->returnSaved($table,$col,$where,$is) != "thisisafalsestatment" && isset($is)){return $this->tmp;}

        $sql = "SELECT * FROM $table";

        if($result = mysqli_query($this->link, $sql)){
            if(mysqli_num_rows($result) > 0){
                $i = 0;
                while($row = mysqli_fetch_array($result)){
                    $this->savedtables[$table][$i] = $row;
                    $i++;
                }

                if($this->returnSaved($table,$col,$where,$is) != "thisisafalsestatment" && isset($is)){return $this->tmp;}
            }else{ if($log) echo "Error 1:2 ".$table.'-'.$col."-".$where."-".$is.'|<br>';}
        }else {if($log){echo "Error 1:1:";}}
    }

    private function returnSaved($table,$col,$where,$is){
        if(isset($this->savedtables[$table])){
            foreach ($this->savedtables[$table] as $key => $value) {
                if(isset($this->savedtables[$table][$key][$where]) && $this->savedtables[$table][$key][$where] === $is){
                    $this->tmp = $this->savedtables[$table][$key][$col];
                    return Essentials::noInject($this->savedtables[$table][$key][$col]);
                }
            }
        }
        return "thisisafalsestatment";
    }

    public function update($table,$col,$where,$is,$data)
    {
        $data = Essentials::noInject($data);
        $sql = "UPDATE $table SET `$col`='$data' WHERE $where='$is'";

        //Query the update
        if(mysqli_query($this->link, $sql)){
        } else{
            echo "ERROR: Could not able to update 1:1. Error-Message: " . mysqli_error($this->link);
        }	
    }
}


?>
