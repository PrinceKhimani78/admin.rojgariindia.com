<?php if(isset($_SESSION['uid']) && $_SESSION['uid'] != '')
{
    $profile = new Account();
?>
    <div class="profilepart">
        <div class="profilepic"><img src="<?php echo $profile->get_profile_photo(); ?>" alt="User" /></div>
        <div class="cdetailmain">
            <div class="candidatedetails">
                <h3><?php echo $_SESSION['userdata']['fullname']; ?></h3>
                <?php /* ?><p class="lighttext">Web developer and Front-end developer</p><?php */ ?>
            </div>
            <div class="candidatedetails">
                <p><span>Account Status : </span> Verfied</p>
            </div>
            <div class="candidatedetails">
                <p><span>Payment Status : </span> Paid</p>
            </div>
        </div>
    </div>
<?php
} ?>
