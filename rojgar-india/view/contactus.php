<?php
$userdata = '';
$fullname = '';
$email = '';
$contact_no = '';
if(isset($_SESSION['uid']) && $_SESSION['uid']!='')
{
    if(isset($_SESSION['userdata']) && !empty($_SESSION['userdata']))
    {
        $fullname = $_SESSION['userdata']['fullname'];
        $email = $_SESSION['userdata']['email'];
        $contact_no = $_SESSION['userdata']['phone_no'];
    }
}
?>
<section class="allcontainer">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="rightbar">
                    <?php
                    if(isset($_SESSION['message']))
                    {
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    }
                    ?>
                    <form method="post">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="fullname" id="fullname" placeholder="Your name" data-validation="required" data-validation-error-msg="Enter your full name" value="<?php echo $fullname; ?>" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" data-validation="email" data-validation-error-msg="Enter your email" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" data-validation="required" data-validation-error-msg="Enter your contact number" name="contactno" id="contactno" placeholder="Contact No" value="<?php echo $contact_no; ?>" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" data-validation="required" data-validation-error-msg="Enter Subject" name="subject" id="subject" placeholder="Subject" />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <textarea name="message" id="message" data-validation="required" data-validation-error-msg="Enter Message" class="form-control" placeholder="Message"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="captchabox">
                                    <div class="g-recaptcha" data-sitekey="<?php echo $grecaptcha_key; ?>"></div>
                                    <input type="hidden" data-validation="recaptcha" data-validation-recaptcha-sitekey="<?php echo $grecaptcha_key; ?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="submitbutton"><input class="register btn btn-primary" type="submit" name="contact" value="Submit" /></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="rightbar">
                    <div class="cbox1">
                        <div class="row">
                            <div class="col-sm-2"><div class="contacticons"><i class="fa fa-address-book-o" aria-hidden="true"></i></div></div>
                            <div class="col-sm-10"><p>Office No. 358, Jasal Commercial Complex, Opp. Nanavati BRTS Bus stop, 150 Feet Ring Road, Rajkot(360007) Gujarat, India</p></div>
                        </div>
                    </div>
                    <div class="cbox1">
                        <div class="row">
                            <div class="col-sm-2"><div class="contacticons"><i class="fa fa-envelope-o" aria-hidden="true"></i></div></div>
                            <div class="col-sm-10"><p><a href="mailto:careers@foursis.com">careers@foursis.com</a></p></div>
                        </div>
                    </div>
                    <div class="cbox1">
                        <div class="row">
                            <div class="col-sm-2"><div class="contacticons"><i class="fa fa-phone" aria-hidden="true"></i></div></div>
                            <div class="col-sm-10"><p>+91 - 9662441222</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="projects">
    <div class="container">
        <div class="maintitle">
            <h4>More Contact Details</h4>
        </div>
        <div class="morecontactinfo">
            <div class="row">
                <div class="col-md-8">
                    <table class="contacttable" cellspacing="0" cellpadding="0">
                        <tr>
                            <th colspan="2">Call</th>
                        </tr>
                        <tr>
                            <td>Manufacturing / Engineering Units Technical Jobs</td>
                            <td>+91-7405434608 / 9662441222</td>
                        </tr>
                        <tr>
                            <td>Academics / Admin / Back Office / HR Jobs</td>
                            <td>+91-8866559179 / 9662441222</td>
                        </tr>
                        <tr>
                            <td>IT / BPO / KPO / ITES jobs</td>
                            <td>+91-8855669213 / 9662441222</td>
                        </tr>
                        <tr>
                            <td>Executive Search jobs</td>
                            <td>+91-8855669213 / 9662441222</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="contacttable" cellspacing="0" cellpadding="0">
                        <tr>
                            <th>Job Seekers Mailing Ids</th>
                        </tr>
                        <tr>
                            <td><a href="mailto:careers@foursis.com">careers@foursis.com</a>, <a href="mailto:foursishr@gmail.com">foursishr@gmail.com</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="morecontactinfo">
            <div class="row">
                <div class="col-md-8">
                    <table class="contacttable" cellspacing="0" cellpadding="0">
                        <tr>
                            <th colspan="2">For Client Inquiries</th>
                        </tr>
                        <tr>
                            <td>Public Relationship Officer</td>
                            <td>+91-7405434608 / 9662441222</td>
                        </tr>
                        <tr>
                            <td>Business Development Manager</td>
                            <td>+91-8866559213 / 9662441222</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="contacttable" cellspacing="0" cellpadding="0">
                        <tr>
                            <th>Clients Mailing Ids</th>
                        </tr>
                        <tr>
                            <td><a href="mailto:info@foursis.com">info@foursis.com</a>, <a href="mailto:foursishr@gmail.com">foursishr@gmail.com</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="location_map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3691.263496564969!2d70.76641861447612!3d22.305872348330414!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3959c983ba528aef%3A0xdb847022b48704b4!2sFoursis+Technical+Solutions!5e0!3m2!1sen!2sin!4v1538306422309" width="100%" height="450" frameborder="0" style="border:0;display: block;" allowfullscreen></iframe>
</section>
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<script>
$.validate({
    modules : 'security',
    validateHiddenInputs : true,
    scrollToTopOnError : false
});
</script>