<?php
if($app->checklogin() === false)
{
    ?><script>window.location='?view=login'</script><?php
    exit;
}
else
{
    ?><script>window.location='?view=dashboard'</script><?php
    exit;
}
?>