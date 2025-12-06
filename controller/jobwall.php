<?php
class Jobwall extends Application
{
    function __construct()
    {
        parent::__construct();
    }
    function get_jobwall($limit,$page)
    {
        $posted_data = '';
        if(isset($_POST['search_result']) && $_POST['search_result'] == 'Search Result')
        {
            $posted_data = $_POST;
        }
        if(isset($_POST['reset_search']) && $_POST['reset_search'] == 'See All Result')
        {
            $posted_data = array();
        }
        
        $starting_limit = ($page-1)*$limit;
        
        $job_function_area_qry = '';
        $this->search_job_function = '';
        
        $key_skills_qry = '';
        $this->key_skills = '';
        
        $this->location = '';
        $location_qry = '';
        
        $this->min_salary = '';
        $this->max_salary = '';
        $salary_qry = '';
        
        $this->min_exp = '';
        $this->max_exp = '';
        $experience_qry = '';
        
        if($posted_data == '' && !(isset($_SESSION['is_search']) && $_SESSION['is_search'] == 1))
        {
            if(isset($_SESSION['userdata']['job_function_area']) && $_SESSION['userdata']['job_function_area'] != '')
                $this->search_job_function = $_SESSION['userdata']['job_function_area'];
            if(isset($_SESSION['userdata']['key_skills']) && $_SESSION['userdata']['key_skills'] != '')
                $this->key_skills = $_SESSION['userdata']['key_skills'];
            if(isset($_SESSION['userdata']['location']) && $_SESSION['userdata']['location'] != '')
                $this->location = $_SESSION['userdata']['location'];
            
            if(isset($_SESSION['userdata']['salary']) && $_SESSION['userdata']['salary'] != '')
            {
                $this->min_salary = 0;
                $this->max_salary = (int)($_SESSION['userdata']['salary']/100000);
            }
            
            if(isset($_SESSION['userdata']['experience_year_month']) && $_SESSION['userdata']['experience_year_month'] != '')
            {
                $this->min_exp = 0;
                $max_exp_data = explode(",",$_SESSION['userdata']['experience_year_month']);
                $this->max_exp = $max_exp_data[0];
            }
        }
        elseif($posted_data == '' && isset($_SESSION['is_search']) && $_SESSION['is_search'] ==1)
        {
            if(isset($_SESSION['job_function_area']) && $_SESSION['job_function_area']!='')
                $this->search_job_function = $_SESSION['job_function_area'];
            if(isset($_SESSION['key_skills']) && $_SESSION['key_skills']!='')
                $this->key_skills = $_SESSION['key_skills'];
            if(isset($_SESSION['location']) && $_SESSION['location']!='')
                $this->location = $_SESSION['location'];
                
            if(isset($_SESSION['min_salary']) && $_SESSION['min_salary']!='')
                $this->min_salary = $_SESSION['min_salary'];
            if(isset($_SESSION['max_salary']) && $_SESSION['max_salary']!='')
                $this->max_salary = $_SESSION['max_salary'];
                
            if(isset($_SESSION['min_exp']) && $_SESSION['min_exp']!='')
                $this->min_exp = $_SESSION['min_exp'];
            if(isset($_SESSION['max_exp']) && $_SESSION['max_exp']!='')
                $this->max_exp = $_SESSION['max_exp'];
        }
        else
        {
            $_SESSION['job_function_area'] = '';
            $_SESSION['key_skills'] = '';
            $_SESSION['location'] = '';
            
            $_SESSION['min_salary'] = '';
            $_SESSION['max_salary'] = '';
            
            $_SESSION['min_exp'] = '';
            $_SESSION['max_exp'] = '';
            
            if(isset($posted_data['job_function_area']) && is_numeric($posted_data['job_function_area']) && $posted_data['job_function_area'] != 0)
                $_SESSION['job_function_area'] = $posted_data['job_function_area'];  
            if(isset($posted_data['key_skills']) && !empty($posted_data['key_skills']))
                $_SESSION['key_skills'] = implode(',',$posted_data['key_skills']);
            if(isset($posted_data['location']) && !empty($posted_data['location']))
                $_SESSION['location'] = implode(',',$posted_data['location']);
                
            if(isset($posted_data['min_salary']) && is_numeric($posted_data['min_salary']))
                $_SESSION['min_salary'] = $posted_data['min_salary'];
            if(isset($posted_data['max_salary']) && is_numeric($posted_data['max_salary']))
                $_SESSION['max_salary'] = $posted_data['max_salary'];
                
            if(isset($posted_data['min_exp']) && is_numeric($posted_data['min_exp']))
                $_SESSION['min_exp'] = $posted_data['min_exp'];
            if(isset($posted_data['max_exp']) && is_numeric($posted_data['max_exp']))
                $_SESSION['max_exp'] = $posted_data['max_exp'];
            
            
            $_SESSION['is_search'] = 1;
            ?><script type="text/javascript">window.location="jobwall"</script><?php
            exit;
        }
        
        //echo $_SESSION['key_skills'];exit;
        //print_r($this->key_skills);exit;
        if(!empty($this->search_job_function) && $this->search_job_function != '')
        {
            $job_function_area_qry = 'And '.$this->search_job_function.' IN(job_posts.job_functions) ';
        }
        if(!empty($this->key_skills) && $this->key_skills != '')
        {
            $key_skill_array = explode(',',$this->key_skills);
            foreach($key_skill_array as $key_skills)
            {
                $key_skills_qry .= 'find_in_set('.$key_skills.',job_posts.key_skills) Or ';
            }
            $key_skills_qry = 'And ('.trim($key_skills_qry, 'Or ').') ';
        }   
        if(!empty($this->location) && $this->location != '')
        {
            $location_array = explode(',',$this->location);
            foreach($location_array as $location)
            {
                $location_qry .= 'find_in_set('.$location.',job_posts.job_location) Or ';
            }
            $location_qry = 'And ('.trim($location_qry, 'Or ').') ';
        }
        if(!empty($this->min_salary) && $this->min_salary != '' && !empty($this->max_salary) && $this->max_salary != '')
        {
            $salary_qry = 'And min_salary >='.$this->min_salary.' And max_salary <= '.$this->max_salary.' ';
        }
        if(!empty($this->min_exp) && $this->min_exp != '' && !empty($this->max_exp) && $this->max_exp != '')
        {
            $experience_qry = 'And min_exp >='.$this->min_exp.' And max_exp <= '.$this->max_exp.' ';
        }
        $qryadd = $job_function_area_qry.$key_skills_qry.$location_qry.$salary_qry.$experience_qry;
        //print_r($qryadd);exit;
        $total_records = $this->selectcustom('select count(id) as total_records from job_posts Where job_posts.status="Active" '.$qryadd,$first='first');
        $this->total_records = $total_records['total_records'];
        $this->total_pages = ceil($this->total_records/$limit);
        
        $qry =  'select job_posts.*,
                cities.name as job_location,
                job_functions.job_function_name as job_function_area,
                job_industry.name as industry_type,
                GROUP_CONCAT(job_skills.skill_name separator ", ") as job_skills from job_posts
                LEFT JOIN cities on cities.id = job_posts.job_location
                LEFT JOIN job_functions on job_functions.id = job_posts.job_functions
                LEFT JOIN job_industry on job_industry.id = job_posts.industry_types
                LEFT JOIN job_skills on find_in_set(job_skills.id, job_posts.key_skills) Where job_posts.status="Active" '.$qryadd.' GROUP BY id order by job_posts.date_added DESC';
        //echo $qry;
        $qry .= ' LIMIT '.$starting_limit.', '.$limit;
        $data = $this->selectcustom($qry);
        return $data;
    }
}
?>