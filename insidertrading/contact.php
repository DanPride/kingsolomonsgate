<?php
	require_once("lib/config/Common.config.php");
	require_once("lib/classes/FormMail.class.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="images/favicon.png" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Contact Us Today to Find Out More on How we can Help You and Your Investments || 3d adviso</title>

<link href="css/style.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div id="wrapper">
    	<div id="wrapper_main">
        	<div id="wrapper_top">
                <div id="wrapper_header">
                	<div id="logo">
                    	<div class="logo_position">
                            <a href="index.php"><img src="images/3da_logo.png" alt="" border="0" /></a>
                        </div>
                    </div>
                	<div id="menu">
						<?php include("includes/menu.php"); ?>
                    </div>
                </div>
                <div id="wrapper_content">
                	<div id="column_full">
                    	<div id="content_full">
                            
                            <h1>contact us</h1>
                            
                            <p>Subscriptions available for qualified institutional investors are limited. To inquire 
                            	about available subscriptions and/or to receive sample research, please complete the 
                                form below. A 3DAdviso, LLC principal will contact qualified institutions 
                                promptly:</p>
                                
							<? 

								$oForm = new FormMail();
								$oForm->setRecipient("Contact", SITE_EMAIL);
								$oForm->setSubject("3DAdviso -- Contact Form");
								$oForm->setTitle("Contact Form");
								$oForm->setAutoResponse(SITE_NAME, SITE_EMAIL,

									"Thanks for contacting us", "lib/autoresponders/contact.auto.html");

							?>

							<table id="login" width="800" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td style="padding: 0px 40px 0px 40px;"><? $oForm->processPage('send_x'); ?>&nbsp;</td>
								</tr>
                                <tr>
									<td width="390" style="font-weight:bold;padding: 0px 0px 20px 40px;">
										<form name="contact_form" action="<?= $_SERVER['PHP_SELF'] ?>" method="post"<?= $oForm->emailIsSent() ? "disabled='true'" : ""?>>
											<input name="required" type="hidden" value="name,firm,phone,email" />
											<label>Name:</label><br />
											<input class="input_class input_height" type=text name="name" style="width:300px" value="<?= $oForm->getPost('name') ?>"><br />
											<label>Firm:</label><br />
											<input class="input_class input_height" type=text name="firm" style="width:300px" value="<?= $oForm->getPost('firm') ?>"><br />
											<label>Phone:</label><br />
											<input class="input_class input_height" type=text name="phone" style="width:300px" value="<?= $oForm->getPost('phone') ?>"><br />
											<label>Email:</label><br />
											<input class="input_class input_height" type=text name="email" style="width:300px" value="<?= $oForm->getPost('email') ?>"><br />
											<label>How did you hear about us?</label><br />
											<textarea class="input_class" name="hear_about_us" style="width:300px;height:35px;" ><?= $oForm->getPost('hear_about_us') ?></textarea><br />
											<label>Questions or Requests?</label><br />
											<textarea class="input_class" name="questions_requests" style="width:300px;height:80px;" ><?= $oForm->getPost('questions_requests') ?></textarea><br />
											<div style="padding: 10px 0px 0px 220px;">
												<input type="image" src="lib/images/submit_button.png" value="Submit" alt="[Submit]" name="send" title="Submit" />
											</div>
										</form>
									</td>
                                    <td width="50">&nbsp;
                                    	
                                    </td>
                                    <td width="350" valign="top">
                                    	<div align="right">
                                        	<h2><em>Support for current subscribers only</em>:</h2>
                                            <p style="text-align: right; font-style: italic;">For research support, contact us at:<br/>
                                            	<a href="mailto:research@3dadviso.com"><strong>research@3dadviso.com</strong></a></p>
                                            <p style="text-align: right; font-style: italic;">For all other issues, contact us at:<br/>
                                            	<a href="mailto:support@3dadviso.com"><strong>support@3dadviso.com</strong></a></p>
                                        </div>
                                    </td>
								</tr>
							</table>
                            
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
        	</div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
	</div>
    <div class="clear"></div>
    <div id="wrapper_footer">
    	<p>copyright by <a href="index.php">3d adviso, llc.</a> all rights reserved. 
        	website by <a href="http://www.taoti.com/?sourceID=3d_adviso">taoti creative</a>.</p>
    </div>
    
    <?php include("includes/analytics.php"); ?>
    
</body>
</html>