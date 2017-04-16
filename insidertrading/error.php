<?php
    $errorType = $_GET['error_type'];
    switch($errorType) {
        case 'system' :
            $errorTitle = 'System Error';
            $errorText = 'There was a system error or the system is temporarily unavailable.  Please hit the back button and try again or try again later at <a href="index.php">www.3dadviso.com</a>.';
            break;
        case 'authorization' :
            $errorTitle = 'Authorization Error';
            $errorText  = 'You are not authorized to view this page.  Please hit the back button or return to the <a href="index.php">home page</a>.';
            break;
        default :
            $errorTitle = 'Page Request Error';
            $errorText  = 'An invalid page request or other error occurred.  Please hit the back button and try again or return to the <a href="index.php">home page</a> and login again.';
            break;
    }
?>
<html>
<head>
<title>3D adviso - <?php echo $errorTitle; ?></title>
<LINK REL="stylesheet" HREF="content/styles/3da_ss1.css">
</head>
<body bgcolor="#FCFCFC" leftmargin="0" topmargin="0" marginheight="0" marginwidth="0">
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
	<tr>
		<td align="center" valign="top" width="100%" height="100%">
			<table border="0" cellpadding="0" cellspacing="0" width="664" height="100%">
				<tr>
					<td align="left" valign="top" width="50" height="100%" background="content/img/3da_lbg.jpg"><img src="content/img/spacer.gif" alt="" border="0" height="1" width="50"></td>
					<td align="left" valign="top" width="1" height="100%" bgcolor="#BABAB1"><img src="content/img/spacer.gif" alt="" border="0" height="1" width="1"></td>
					<td align="left" valign="top" width="29" height="100%" bgcolor="#FFFFFF"><img src="content/img/spacer.gif" alt="" border="0" height="1" width="29"></td>
					<td align="left" valign="top" width="504" height="100%" bgcolor="#FFFFFF">
						<table border="0" cellpadding="0" cellspacing="0" width="504" height="100%">
							<tr>
								<td align="left" valign="top" width="504" height="10" colspan="5"><img src="content/img/spacer.gif" alt="" border="0" height="10" width="1"></td>
							</tr>
							<tr>
								<td align="left" valign="top" width="120" height="67"><img src="content/img/3da_hp_logo.gif" alt="3DAdvisor Logo" border="0" height="67" width="120"></td>
								<td align="left" valign="top" width="362"><img src="content/img/spacer.gif" alt="" border="0" height="1" width="1"></td>
								<td align="left" valign="top" width="1"><img src="content/img/spacer.gif" alt="" border="0" height="1" width="1"></td>
								<td align="left" valign="top" width="1"><img src="content/img/spacer.gif" alt="" border="0" height="1" width="1"></td>
								<td align="left" valign="top" width="20"><img src="content/img/spacer.gif" alt="" border="0" height="1" width="20"></td>
							</tr>
							<tr>
								<td align="left" valign="top" width="504" height="17" colspan="5"><img src="content/img/spacer.gif" alt="" border="0" height="17" width="1"></td>
							</tr>
							<tr>
								<td align="left" valign="top" width="504" height="4" colspan="5" background="content/img/3da_nav_border.gif"><img src="content/img/spacer.gif" alt="" border="0" height="4" width="1"></td>
							</tr>
							<tr>
								<td align="left" valign="top" width="504" height="100%" colspan="5">
								    <p class="sTitle"><?php echo $errorTitle; ?></p><br>
                                    <?php echo $errorText; ?>
								</td>
							</tr>
						</table>
					</td>
					<td align="left" valign="top" width="29" height="100%" bgcolor="#FFFFFF"><img src="content/img/spacer.gif" alt="" border="0" height="1" width="29"></td>
					<td align="left" valign="top" width="1" height="100%" bgcolor="#BABAB1"><img src="content/img/spacer.gif" alt="" border="0" height="1" width="1"></td>
					<td align="left" valign="top" width="50" height="100%" background="content/img/3da_rbg.jpg"><img src="content/img/spacer.gif" alt="" border="0" height="1" width="50"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>

