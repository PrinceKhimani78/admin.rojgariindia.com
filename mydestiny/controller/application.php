<?php
include('../controller/databaseclass.php');
class Application extends Databaseclass
{
    function __construct()
    {
        parent::__construct();
    }
    function checklogin()
    {
        if(isset($_SESSION['user']) && $_SESSION['user']!='')
            return true;
        else
            return false;
    }
    function get_job_industry()
    {
        return $this->select(array('all'),'job_industry',array('status' => 'Active'));
    }
    function get_job_functions()
    {
        return $this->select(array('all'),'job_functions',array('status' => 'Active'));
    }
    function get_job_skills()
    {
        return $this->select(array('all'),'job_skills',array('status' => 'Active'));
    }
    function get_country()
    {
        return $this->select(array('id','name'),'countries');
    }
    function get_states($id='')
    {
        if(isset($id) && is_numeric($id))
        {
            return $this->select(array('id','name'),'states',array('country_id' => $id));
        }
        else
        {
            return $this->select(array('id','name'),'states');
        }
    }
    function get_cities($id='')
    {
        if(isset($id) && is_numeric($id))
        {
            return $this->select(array('id','name'),'cities',array('state_id' => $id));
        }
        else
        {
            return $this->select(array('id','name'),'cities');
        }
    }
    function encode_pass($password)
    {
        $algo = $this->GetAlgo();
        $pass_split = str_split($password);
        $encoded_pass = '';
        foreach($pass_split as $key => $passvalue)
        {
            $encoded_pass .= $algo[$passvalue] . ":";
        }
        return rtrim($encoded_pass, ":");
    }
    function decode_pass($hash)
    {
        $return = '';
        $algo = $this->GetAlgo();
        $unhash = explode(":", $hash);
        foreach ($unhash as $value)
        {
            $find = array_search($value, $algo);
            if ($find)
                $return .= $find;
        }
        return $return;
    }
    function GetAlgo()
    {
        return [
            "a"=>"H",
            "b"=>"He",
            "c"=>"Li",
            "d"=>"Be",
            "e"=>"B",
            "f"=>"C",
            "g"=>"N",
            "h"=>"O",
            "i"=>"F",
            "j"=>"Ne",
            "k"=>"Na",
            "l"=>"Mg",
            "m"=>"Al",
            "n"=>"Si",
            "o"=>"P",
            "p"=>"S",
            "q"=>"Cl",
            "r"=>"Ar",
            "s"=>"K",
            "t"=>"Ca",
            "u"=>"Sc",
            "v"=>"Ti",
            "w"=>"V",
            "x"=>"Cr",
            "y"=>"Mn",
            "z"=>"Fe",
            "A"=>"Co",
            "B"=>"Ni",
            "C"=>"Cu",
            "D"=>"Zn",
            "E"=>"Ga",
            "F"=>"Ge",
            "G"=>"As",
            "H"=>"Se",
            "I"=>"Br",
            "J"=>"Kr",
            "K"=>"Rb",
            "L"=>"Sr",
            "M"=>"Y",
            "N"=>"Zr",
            "O"=>"Nb",
            "P"=>"Mo",
            "Q"=>"Tc",
            "R"=>"Ru",
            "S"=>"Rh",
            "T"=>"Pd",
            "U"=>"Ag",
            "V"=>"Cd",
            "W"=>"In",
            "X"=>"Sn",
            "Y"=>"Sb",
            "Z"=>"Te",
            " "=>"I",
            "1"=>"Xe",
            "2"=>"Cs",
            "3"=>"Ba",
            "4"=>"Hf",
            "5"=>"Ta",
            "6"=>"W",
            "7"=>"Re",
            "8"=>"Os",
            "9"=>"Ir",
            "0"=>"Pt",
            "!"=>"Au",
            "£"=>"Hg",
            "$"=>"Tl",
            "%"=>"Pb",
            "^"=>"Bi",
            "&"=>"Po",
            "*"=>"At",
            "("=>"Rn",
            ")"=>"Fr",
            "_"=>"Ra",
            "-"=>"Rf",
            "="=>"Db",
            "+"=>"Sg",
            "`"=>"Bh",
            "¬"=>"Hs",
            ""=>"Mt",
            ","=>"Ds",
            "<"=>"Rg",
            "."=>"Cn",
            ">"=>"Uut",
            "/"=>"Fl",
            "?"=>"Uup",
            ";"=>"Lv",
            ":"=>"Uus",
            "'"=>"Uuo",
            "@"=>"La",
            "#"=>"Ce",
            "~"=>"Pr",
            "["=>"Nd",
            "]"=>"Pm",
            "{"=>"Sm",
            "}"=>"Eu",
            "."=>"Gd",
            "\""=>"Jpj",
        ];
    }
}
?>