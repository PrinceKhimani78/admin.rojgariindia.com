<?php
include('../controller/application.php');
$app = new Application();
if(isset($_GET['getdata']) && $_GET['getdata'] == 'getstate')
{
    if(isset($_POST['country_id']) && is_numeric($_POST['country_id']))
    {
        $statelist = $app->get_states($_POST['country_id']);
        ?>
            <option value="">Select state</option>
            <?php if(!empty($statelist))
            {
                foreach($statelist as $states)
                {
                    ?><option value="<?php echo $states['id']; ?>"><?php echo $states['name']; ?></option><?php
                }
            }?>
        <?php
    }
    else
    {
        echo '<option value="">Select state</option>';
    }
}
if(isset($_GET['getdata']) && $_GET['getdata'] == 'getcity')
{
    if(isset($_POST['state_id']) && is_numeric($_POST['state_id']))
    {
        $citylist = $app->get_cities($_POST['state_id']);
        ?>
            <option value="">Select city</option>
            <?php if(!empty($citylist))
            {
                foreach($citylist as $cities)
                {
                    ?><option value="<?php echo $cities['id']; ?>"><?php echo $cities['name']; ?></option><?php
                }
            }?>
        <?php
    }
    else
    {
        echo '<option value="">Select city</option>';
    }
}
if(isset($_GET['get_city_search']) && $_GET['get_city_search'] == true)
{
    $search_city = $app->selectcustom('select * from cities where name like "%'.$_GET['q'].'%" limit 50');
    if(!empty($search_city))
    {
        $json = [];
        foreach($search_city as $result)
        {
            $json[] = ['id'=>$result['id'], 'text'=>$result['name']];
        }
        echo json_encode($json);
    }
    else
    {
       
    }
}
?>