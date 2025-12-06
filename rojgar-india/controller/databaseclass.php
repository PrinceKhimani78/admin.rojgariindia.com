<?php
define("HOST", 'localhost');
define("DBNAME", 'admin_rojgari');
define("DBUSER", 'admin_rojgari');
define("DBPASSWORD", 'Hitesh@123');

class Databaseclass
{
    public $dbpdo; // ✅ FIX: prevents PHP 8.2 dynamic property warning

    function __construct()
    { 
        $this->connectdb(HOST, DBNAME, DBUSER, DBPASSWORD);
    }

    public function connectdb($host, $dbname, $dbuser, $dbpassword)
    {
        try {
            $this->dbpdo = new PDO(
                'mysql:host=' . $host . ';dbname=' . $dbname,
                $dbuser,
                $dbpassword
            );
            $this->dbpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }

    public function select($fields, $tablename, $conditions = '', $find = '')
    {
        $whereclause = "";

        if ($conditions != "") {
            foreach ($conditions as $key => $value) {
                $whereclause .= $key . "='" . $value . "' AND ";
            }
            $whereclause = trim($whereclause, " AND ");
            $whereclause = ' WHERE ' . $whereclause;
        }

        ($fields[0] == "all") ? $fields = "*" : $fields = implode(",", $fields);

        $qry = "SELECT " . $fields . " FROM " . $tablename . " " . $whereclause;

        $data = $this->dbpdo->prepare($qry);
        $data->execute();

        if ($find == 'first')
            return $data->fetch(PDO::FETCH_ASSOC);
        else
            return $data->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectcustom($query, $find = '')
    {
        $data = $this->dbpdo->prepare($query);
        $data->execute();

        if ($find == 'first')
            return $data->fetch(PDO::FETCH_ASSOC);
        else
            return $data->fetchAll(PDO::FETCH_ASSOC);
    }

    function insert($fields = '', $dataparams = '', $tablename = '')
    {
        $fields = implode(",", $fields);
        $dataparams = implode('","', $dataparams);

        $qry = 'INSERT INTO ' . $tablename . ' (' . $fields . ') VALUES("' . $dataparams . '")';

        $data = $this->dbpdo->prepare($qry);
        $data->execute();

        return $this->dbpdo->lastInsertId(); 
    }

    function update($fields = '', $dataparams = '', $tablename = '', $conditions = array())
    {
        $whereclause = "";

        foreach ($conditions as $key => $value) {
            $whereclause .= $key . "='" . $value . "' AND ";
        }

        $whereclause = trim($whereclause, " AND ");

        $add_qry = '';

        foreach ($fields as $key => $value) {
           $add_qry .= $value . " = '" . $dataparams[$key] . "', ";
        }

        $add_qry = trim($add_qry, ', ');

        $qry = 'UPDATE ' . $tablename . ' SET ' . $add_qry . ' WHERE ' . $whereclause;

        $data = $this->dbpdo->prepare($qry);
        $data->execute();

        return $data->rowCount();
    }

    function delete($tablename, $conditions = array())
    {
        $whereclause = '';

        foreach ($conditions as $key => $value) {
            $whereclause .= $key . "='" . $value . "' AND ";
        }

        $whereclause = trim($whereclause, " AND ");

        $qry = 'DELETE FROM ' . $tablename . ' WHERE ' . $whereclause;

        $data = $this->dbpdo->prepare($qry);
        $data->execute();
    }
}
?>
