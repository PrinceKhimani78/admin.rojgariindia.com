<?php
define("HOST", 'localhost');
define("DBNAME", 'rojgar_india');
define("DBUSER", 'root');
define("DBPASSWORD",'');

class Databaseclass
{
    function __construct()
    { 
        $this->connectdb(HOST,DBNAME,DBUSER,DBPASSWORD);
    }

    public function connectdb($host,$dbname,$dbuser,$dbpassword)
    {
        $this->dbpdo = new PDO('mysql:host='.$host.';dbname='.$dbname, $dbuser, $dbpassword) or die("Error Connecting to The Database");
    }

    public function select($fields,$tablename,$conditions='',$find = '')
    {
        $whereclause="";
        if($conditions != "")
        {
            foreach($conditions as $key=>$value) :
                $whereclause.=$key."='".$value."' AND ";
            endforeach;
            $whereclause=trim($whereclause," AND ");
            $whereclause = ' where '.$whereclause;
        }

        ($fields[0]=="all") ? $fields="*" : $fields=implode(",",$fields);
        $qry="select ".$fields." from ".$tablename." ".$whereclause;
        //echo $qry;exit;
        $data=$this->dbpdo->prepare($qry);
        $data->execute();
        if($find == 'first')
            $data_recieved = $data->fetch(PDO::FETCH_ASSOC);
        else
            $data_recieved = $data->fetchAll(PDO::FETCH_ASSOC);

        $result=$data_recieved;
        return $result;
    }

    public function selectcustom($query,$find = '')
    {
        $data=$this->dbpdo->prepare($query);
        $data->execute();
        if($find == 'first')
            $data_recieved = $data->fetch(PDO::FETCH_ASSOC);
        else
            $data_recieved = $data->fetchAll(PDO::FETCH_ASSOC);

        $result=$data_recieved;
        return $result;
    }
    function insert($fields='',$dataparams='',$tablename='')
    {
        $fields=implode(",",$fields);
        $dataparams=implode('","',$dataparams);
        $qry='insert into '.$tablename.' ('.$fields.') VALUES("'.$dataparams.'")';
        //echo $qry;exit;
        $data=$this->dbpdo->prepare($qry);
        $data->execute();
        return $this->dbpdo->lastInsertId(); 
    }
    function update($fields='',$dataparams='',$tablename='',$conditions = array())
    {
        $whereclause="";
        foreach($conditions as $key=>$value)
        {
            $whereclause.=$key."='".$value."' AND ";
        }
        $whereclause=trim($whereclause," AND ");
        $add_qry = '';
        foreach($fields as $key=>$value)
        {
           $add_qry .= $value . " = '" . $dataparams[$key] . "', ";
        }
        $add_qry = trim($add_qry,', ');
        $qry='update '.$tablename.' set ' . $add_qry . ' where '.$whereclause;
        $data=$this->dbpdo->prepare($qry);
        $data->execute();
        return $data->rowCount();
    }
    function delete($tablename,$conditions = array())
    {
        $whereclause = '';
        foreach($conditions as $key=>$value)
        {
            $whereclause.=$key."='".$value."' AND ";
        }
        $whereclause=trim($whereclause," AND ");
        $qry = 'delete from '.$tablename.' where '.$whereclause;
        $data=$this->dbpdo->prepare($qry);
        $data->execute();
    }
}
?>
