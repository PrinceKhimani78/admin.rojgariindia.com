<?php
class Jobposts extends Application
{
    function __construct()
    {
        parent::__construct();
        if(isset($_POST['add_job']) && $_POST['add_job'] == 'Add Job')
        {
            $this->addjob($_POST);
        }
        $this->jobdetail="";
        if(isset($_GET['action']) && $_GET['action']=='edit' && isset($_GET['id']) && is_numeric($_GET['id']))
        {
            $this->jobdetail = $this->joblist($_GET['id']);
        }
        if(isset($_POST['add_job']) && $_POST['add_job'] == 'Update' && isset($_GET['id']) && is_numeric($_GET['id']))
        {
            $this->editjob($_POST);
        }
        if(isset($_GET['action']) && $_GET['action']=='delete' && isset($_GET['id']) && is_numeric($_GET['id']))
        {
            $this->delete_job($_GET['id']);
        }
    }
    function addjob($posted_data)
    {
        if(!empty($posted_data))
        {
            $job_location = implode(',',$posted_data['job_location']);
            $industry_types = implode(',',$posted_data['industry_types']);
            $job_functions = implode(',',$posted_data['job_functions']);
            $key_skills = implode(',',$posted_data['key_skills']);
            
            $fields = array('job_title','job_location_country','job_location_state','job_location','min_exp','max_exp','job_type','education','min_salary','max_salary','industry_types','job_functions','key_skills','company_name','contact_email','mobile_no','whatsapp_no','additional_links','status');
            $data = array(addslashes($posted_data['job_title']),$posted_data['job_location_country'],$posted_data['job_location_state'],$job_location,$posted_data['min_exp'],$posted_data['max_exp'],$posted_data['job_type'],addslashes($posted_data['education']),$posted_data['min_salary'],$posted_data['max_salary'],$industry_types,$job_functions,$key_skills,addslashes($posted_data['company_name']),$posted_data['contact_email'],$posted_data['mobile_no'],$posted_data['whatsapp_no'],$posted_data['additional_links'],$posted_data['status']);
            $tablename = 'job_posts';
            $this->insert($fields,$data,$tablename);
            ?><script type="text/javascript">window.location="?view=job_post"</script><?php
            exit;
        }
    }
    function editjob($posted_data)
    {
        if(!empty($posted_data))
        {
            $job_location = implode(',',$posted_data['job_location']);
            $industry_types = implode(',',$posted_data['industry_types']);
            $job_functions = implode(',',$posted_data['job_functions']);
            $key_skills = implode(',',$posted_data['key_skills']);
            
            $fields = array('job_title','job_location_country','job_location_state','job_location','min_exp','max_exp','job_type','education','min_salary','max_salary','industry_types','job_functions','key_skills','company_name','contact_email','mobile_no','whatsapp_no','additional_links','status');
            $data = array(addslashes($posted_data['job_title']),$posted_data['job_location_country'],$posted_data['job_location_state'],$job_location,$posted_data['min_exp'],$posted_data['max_exp'],$posted_data['job_type'],addslashes($posted_data['education']),$posted_data['min_salary'],$posted_data['max_salary'],$industry_types,$job_functions,$key_skills,addslashes($posted_data['company_name']),$posted_data['contact_email'],$posted_data['mobile_no'],$posted_data['whatsapp_no'],$posted_data['additional_links'],$posted_data['status']);
            $tablename = 'job_posts';
            $this->update($fields,$data,$tablename,array('id' => $_GET['id']));
            $_SESSION['message'] = '<div class="alert alert-success">Job post updated successully</div>';
            ?><script type="text/javascript">window.location="?view=addjob&action=edit&id=<?php echo $_GET['id']; ?>"</script><?php
            exit;
        }
    }
    function delete_job($id)
    {
        if(isset($id) && is_numeric($id))
        {
            $this->delete('job_posts',array('id' => $id));
            $_SESSION['message'] = '<div class="alert alert-success">Job post deleted successully</div>';
        }
        ?><script type="text/javascript">window.location="?view=job_post"</script><?php
        exit;
    }
    function joblist($id="")
    {
        if(isset($id) && is_numeric($id))
        {
            return $this->select(array('all'),'job_posts',array('id' => $id),$find='first');
        }
        else
        {
            return $this->select(array('all'),'job_posts');
        }
    }
    function getjoblist()
    {
        return $this->selectcustom('select job_posts.*,GROUP_CONCAT(cities.name separator ", ") as cities_name from job_posts LEFT JOIN cities on find_in_set(cities.id, job_posts.job_location) GROUP BY id');
        /*return $this->selectcustom('select job_posts.*,countries.name as country_name,states.name as state_name,GROUP_CONCAT(cities.name) as cities_name from job_posts
                                   LEFT JOIN countries on countries.id = job_posts.job_location_country
                                   LEFT JOIN states on states.id = job_posts.job_location_state
                                   LEFT JOIN cities on find_in_set(cities.id, job_posts.job_location) where job_posts.id = 1');*/
    }
}
?>