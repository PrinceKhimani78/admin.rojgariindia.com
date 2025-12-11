<?php

// Use absolute path to include Databaseclass.php

// require_once $_SERVER['DOCUMENT_ROOT'] . "/rojgar-india/controller/databaseclass.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/admin.rojgariindia.com/rojgar-india/controller/databaseclass.php";

class Application extends Databaseclass
{
    function __construct()
    {
        parent::__construct();
    }

    function validarecaptcha($response)
    {
        if (!empty($response)) {
            $data = [
                'secret' => "6Le7gFoUAAAAAJOhjIdEyHaqrli_CTwB7TCHDqRV",
                'response' => $response
            ];

            $verify = curl_init();
            curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($verify, CURLOPT_POST, true);
            curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($verify);
            $responseData = json_decode($response);

            return $responseData->success ?? false;
        }

        return false;
    }

    function getpagedetail($pagekey)
    {
        if (!empty($pagekey)) {
            $check = $this->select(['count(id) as page'], 'webpages', ['page_key' => $pagekey], 'first');
            if ($check['page'] == 0) $pagekey = 'home';

            return $this->select(['all'], 'webpages', ['page_key' => $pagekey], 'first');
        }
    }

    function checklogin()
    {
        return !empty($_SESSION['uid']);
    }

    function get_job_industry()
    {
        return $this->select(['all'], 'job_industry', ['status' => 'Active']);
    }

    function get_job_functions()
    {
        return $this->select(['all'], 'job_functions', ['status' => 'Active']);
    }

    function get_job_skills()
    {
        return $this->select(['all'], 'job_skills', ['status' => 'Active']);
    }

    function get_country()
    {
        return $this->select(['id','name'], 'countries');
    }

    function get_states($id='')
    {
        return is_numeric($id)
            ? $this->select(['id','name'],'states',['country_id'=>$id])
            : $this->select(['id','name'],'states');
    }

    function get_cities($id='')
    {
        return is_numeric($id)
            ? $this->select(['id','name'],'cities',['state_id'=>$id])
            : $this->select(['id','name'],'cities');
    }

    function get_city_name($id)
    {
        return $this->select(['id','name'],'cities',['id'=>$id],'first');
    }

    function encode_pass($password)
    {
        $algo = $this->GetAlgo();
        return implode(":", array_map(fn($c) => $algo[$c], str_split($password)));
    }

    function decode_pass($hash)
    {
        $return = '';
        $algo = $this->GetAlgo();
        foreach (explode(":", $hash) as $value) {
            $find = array_search($value, $algo);
            if ($find) $return .= $find;
        }
        return $return;
    }

    function GetAlgo()
    {
        return [
            "a"=>"H","b"=>"He","c"=>"Li","d"=>"Be","e"=>"B","f"=>"C","g"=>"N","h"=>"O","i"=>"F","j"=>"Ne",
            "k"=>"Na","l"=>"Mg","m"=>"Al","n"=>"Si","o"=>"P","p"=>"S","q"=>"Cl","r"=>"Ar","s"=>"K","t"=>"Ca",
            "u"=>"Sc","v"=>"Ti","w"=>"V","x"=>"Cr","y"=>"Mn","z"=>"Fe","A"=>"Co","B"=>"Ni","C"=>"Cu","D"=>"Zn"
        ];
    }
}
?>
EOF
