<?php
/***************************************************************************
 *                      STAND-ALONE VERSION OF FORMMAIL
 *                            FormMail.class.php
 *                             ---------------- 
 * [INFO]
 *	 AUTHOR				  : Kevin K. Nelson
 *   EMAIL                : knelson@taoti.com
 *   COPYRIGHT            : (C) 2005 Taoti Enterprises International, Inc.
 *                           -  ALL RIGHTS RESERVED
 *
 *   CREATED              : 2005-10-17
 *   LAST UPDATED         : 2006-02-17
 *   VERSION              : See FormMail->version variable
 *
 *   PURPOSE              : An Object-Oriented FormMail application that will 
 *                          allow for self-submitting pages, so that on 
 *                          user-error, the same form will re-load itself and 
 *                          allow the user to modify their entry.
 * [NOTES]
 *   2005-03-02 - v. 1.0.3: created requireAll() method to require all posts.
 *   2005-02-17 - v. 1.0.2: Added variables $obj->site_name, $obj->site_email,
 *                and $obj->formmail_dir, which need to be defined on a 
 *                site-by-site basis.  They can be defined in the constructor.
 *              - Also, added status_message() as a built-in method to the 
 *                class.
 *              - Made it so that the constructor and status_message() are
 *                the only major difference between SA version and the 
 *                standard library version.
 *   2005-02-16 - v. 1.x SA: The SA version is modified to be a STAND-ALONE 
 *                FormMail class that does not rely upon either of our 
 *                libraries.
 *
 ***************************************************************************/
	
	class FormMail {
		var $errors		= array();
		var $recipients	= array();
		var $subject, $title, $sender_name, $sender_email, $recipient_name, $recipient_email;
		var $html_header, $html_content, $html_footer, $stylesheet;
		var $http_root, $page_url, $date_time, $reserved_words;
		var $email_sent, $magic_quotes_on;

		function FormMail() {
			$this->site_name				= "3DAdviso";
			$this->site_email				= "support@3DAdviso.com";
//			$this->site_email				= "tnelson@taoti.com";		//@@@
			$this->formmail_dir				= BASE_DIR."/lib/classes";

			$this->subject					= "You have new mail from website Contact form";
			$this->title					= "You have a new inquiry from your website";
			$this->email_sent				= false;
			$this->http_root				= "http://".$_SERVER['HTTP_HOST'];
			$this->page_url					= $this->http_root . $_SERVER['PHP_SELF'];
			$this->icon_folder				= $this->http_root . $this->formmail_dir . "/icons";
			$this->date_time				= date("l, F jS, Y (g:ia)");
			$this->reserved_words			= array("name","email","subject","recipient","required","ar_file","ar_subject","ar_from");
			$this->magic_quotes_on			= get_magic_quotes_gpc() == 1 ? true : false;
			$this->lf						= "\n";
			
			$this->version					= "1.0.3 SA";  // SA VERSION -- STAND-ALONE
			$this->getPostedData();
		}
		function status_message( $strTitle, $strMessage, $bError=0 ) {
			$strErrStyle					= $bError ? "color:#710700;" : "";
			echo("<div style='border-style:solid;border-width:0px 0px 0px 0px;padding-left:20px;margin-bottom:15px;'>");
			  echo("<div style='font-size:16px;border-style:solid;border-width:0px 0px 1px 0px;{$strErrStyle}'><b>{$strTitle}</b></div>");
			  echo("<div style='font-size:12px;{$strErrStyle}'>{$strMessage}</div>");
			echo("</div>");
		}
		function clipString( $p_strText, $p_iMaxLength ) {
			return( strlen($p_strText) > $p_iMaxLength ? preg_replace("/([\,\.\?\!\s\/]+)([^\s\,\?\!\.\/]*)$/","...",substr( $p_strText, 0, $p_iMaxLength ) ) : $p_strText);
		}
		// FUNCTIONS TO MANUALLY SET VALUES
		function setStyleSheet($p_strLink) {
			$this->stylesheet				= $p_strLink;
		}
		function setHeader( $p_strHeader ) {
			$this->html_header				= $p_strHeader;
		}
		function setFooter( $p_strFooter ) {
			$this->html_footer				= $p_strFooter;
		}
		function setSubject( $p_strSubject ) {
			$this->subject					= $p_strSubject;
		}
		function setTitle( $p_strTitle ) {
			$this->title					= $p_strTitle;
		}
		function setRecipient( $p_strName, $p_strEmail ) {
			$this->recipients[$p_strName]	= $p_strEmail;
			$this->input['recipient']		= $p_strName;
		}
		function setAutoResponse( $p_strName, $p_strEmail, $p_strSubject, $p_strFile ) {
			$this->input['ar_from']			= "{$p_strName} <{$p_strEmail}>";
			$this->input['ar_subject']		= $p_strSubject;
			$this->input['ar_file']			= $p_strFile;
		}
		function requireAll() {
			foreach( $_POST AS $key => $value ) {
				if( strstr($key,"_require")==false && strstr($key, "_match")==false ) {
					$this->input["{$key}_require"]	= 1;
				}
			}
		}
		// ADD AVAILABLE RECIPIENTS
		function addRecipient( $p_strName, $p_strEmail ) {
			$this->recipients[$p_strName]	= $p_strEmail;
		}
		// RETURNS TRUE IF E-MAIL WAS SENT SUCCESSFULLY
		function emailIsSent() {
			return( $this->email_sent );
		}
		function getElementValue( $p_strName ) {
			return( isset( $this->input[$p_strName] ) ? htmlentities($this->input[$p_strName],ENT_QUOTES) : "" );
		}
		// alias of getElementValue for backwards-compatibility
		function getPost( $p_strName ) { return($this->getElementValue($p_strName)); }
		
		function processPage($p_strVarName) {
			if( !isset($this->input[$p_strVarName]) ) { return; }

			$this->setVariables();
			$this->html_content				= $this->getHeader();

			$i=1;
			foreach( $this->input AS $key => $value ) {
				$this->checkForErrors( $key, $value );
				
				if( strstr($key, "_match")==false && strstr($key, "_require")==false && !in_array($key,$this->reserved_words) ) {
					$this->html_content	.= $this->addRow( $key, $value, $i );
					$i ^= 3;
				}
			}
			$this->html_content				.= $this->getFooter();
			
			if( count($this->errors) > 0 ) {
				$this->status_message("ERROR:", "<div style='text-align:left'>Please fix the following errors:<ul>".implode("\n",$this->errors)."</ul></div>",1);
			}
			else {
				$this->sendEmail();
				$this->status_message("Thank You", "Your e-mail has been sent successfully.");
				$this->email_sent			= true;
			}
			
			if( $this->email_sent == true && !empty($this->input['ar_file']) ) {
				$this->sendAutoResponse();
			}
		}
		/* ********************************************
		**  INTERNAL FUNCTIONS NOT CALLED EXTERNALLY 
		******************************************** */
		function addRow( $p_key, $p_value, $i ) {
			return("<tr class='row{$i}'><td class='left_info_cell'>{$p_key}:</td><td class='right_info_cell'>".(is_array($p_value) ? $this->getList($p_value) : $p_value)."</td></tr>");
		}
		function getList( $p_array ) {
			$strList				= "";
			foreach( $p_array AS $key => $value ) { $strList .= "<li>{$value}</li>"; }
			return( "<ul>".$strList."</ul>" );
		}
		function checkForErrors( $p_key, $p_value ) {
			if( isset( $this->input["{$p_key}_require"] ) && empty( $this->input[$p_key] ) ) {
				$this->errors[]		= "<li>The <b>{$p_key}</b> field cannot be empty</li>";
			}
			elseif( !empty( $this->input["{$p_key}_match"] ) ) {
			  $strRegExp			= $this->input["{$p_key}_match"];
			  if( !preg_match($strRegExp, $p_value) ) {
				$this->errors[]		= "<li>The form element <b>{$p_key}</b> is invalidly formatted</li>";
			  }
			}
		}
		function getHeader() {
			if( isset( $this->html_header ) ) { return( $this->html_header ); }

			$strHeader		= "<html>
	<head>
		<title>Formmail E-mail</title>";
			if( isset( $this->stylesheet ) ) {
			$strHeader		.= "
		<link rel='stylesheet' type='text/css' href='{$this->stylsheet}' />";
			}
			else {
			$strHeader		.= "
		<style type='text/css'>
			body, td { background-color:#FFFFFF; color:#000000; font-family:Tahoma, Verdana, Arial, sans-serif;font-size:12px; }
			.row1 td { background-color:#EFEFEF; }
			.row2 td { background-color:#DDDDDD; }
			.rowT td { background-color:#D8BE89; border-style:solid; border-width:0px 0px 1px 0px; border-color:#FFFFCC }
			.left_info_cell  { font-size:14px; padding:5px 5px 5px 5px; font-weight:bold; }
			.right_info_cell { font-size:14px; padding:5px 5px 5px 5px; }	
			a:link, a:visited { color:navy; }
			a:hover, a:active { color:#CC0000; }
		</style>";
			}
			$strHeader		.= "
	</head>
	<body>
		<h1 class='page_title'><img src='".$this->icon_folder."/icon[email].gif' width='32' height='32' /> {$this->title}</h1>
		<table cellspacing='0' style='width:400px;' style='border-style:solid;border-color:black;border-width:1px;'>
			<tr class='rowT'>
				<td class='left_info_cell' nowrap='true'>
					<img src='".$this->icon_folder."/sm_icon[person].gif' width='16' height='16' />
					From:
				</td><td class='right_info_cell'>
					{$this->sender_name} &lt;<a href='mailto:{$this->sender_email}'>{$this->sender_email}</a>&gt;
				</td>
			</tr><tr class='rowT'>
				<td class='left_info_cell' nowrap='true'>
					<img src='".$this->icon_folder."/sm_icon[folder].gif' width='16' height='16' />
					Subject:
				</td><td class='right_info_cell'>
					{$this->subject}
				</td>
			</tr><tr class='rowT'>
				<td class='left_info_cell' nowrap='true'>
					<img src='".$this->icon_folder."/sm_icon[daily].gif' width='16' height='16' />
					Date/Time:
				</td><td class='right_info_cell'>
					{$this->date_time}
				</td>
			</tr><tr class='rowT'>
				<td class='left_info_cell' nowrap='true'>
					<img src='".$this->icon_folder."/sm_icon[webpage].gif' width='16' height='16' />
					Page:
				</td><td class='right_info_cell'>
						<a href='{$this->page_url}'>".$this->clipString($this->page_url,52)."</a>
				</td>
			</tr>";
			return( $strHeader );
		}
		function getFooter() {
			if( isset( $this->html_footer ) ) { return( $this->html_footer ); }

			return("
		</table>
	</body>
</html>");
		}
		function setVariables() {
			$this->subject			= isset( $this->input['subject'] )   ? $this->getPost("subject")   : $this->subject;
			$this->sender_name		= isset( $this->input['name'] )      ? $this->getPost("name")      : "Anonymous";
			$this->sender_email		= isset( $this->input['email'] )     ? $this->getPost("email")     : $this->site_email;
			$this->recipient		= isset( $this->input['recipient'] ) ? $this->getPost("recipient") : 0;
			$this->required			= !empty( $this->input['required'] ) ? explode(",",$this->input["required"])  : (!empty($this->input['require']) ? explode(",",$this->input["require"]) : array());
			$bValidRecipient		= isset( $this->recipients[$this->recipient] );
			$this->recipient_name	= $bValidRecipient ? $this->recipient                    : "";
			$this->recipient_email	= $bValidRecipient ? $this->recipients[$this->recipient] : "";

			foreach( $this->required AS $key => $value ) {
				$this->input[$value."_require"]	= 1;
			}
			
			// IF E-MAIL IS REQUIRED, MAKE SURE IT IS PROPERLY FORMATTED BY
			// ADDING AN email_match ENTRY
			if( !empty($this->input['email_require']) ) {
				$this->input['email_match']		= "/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)*\.[\w-]{1,6}$/";
			}
			
			if( empty($this->recipient_name) || empty( $this->recipient_email ) ) {
				foreach( $this->recipients AS $key => $value ) {
					$this->recipient_name	= $key;
					$this->recipient_email	= $value;
					break;
				}
			}

			if( empty($this->recipient_name) ) {
				$this->status_message("ERROR:","There is no recipient defined for this e-mail",1);
			}
		}
		function sendEmail() {
			$l_strHeaders		= "MIME-Version: 1.0".$this->lf;
			$l_strHeaders		.= "Content-type: text/html; charset=iso-8859-1".$this->lf;
			$l_strHeaders		.= "To: ".addslashes($this->recipient_name)." <".$this->recipient_email.">".$this->lf;
			$l_strHeaders		.= "From: ".addslashes($this->sender_name)." <".$this->sender_email.">".$this->lf;
			
			mail( '', $this->subject, $this->html_content, $l_strHeaders );
		}
		function sendAutoResponse() {
			$strFile			= is_file($this->input['ar_file'])   ? $this->input['ar_file']    : (is_file($_SERVER['DOCUMENT_ROOT'].$this->input['ar_file']) ? $_SERVER['DOCUMENT_ROOT'].$this->getPost('ar_file') : "");
			$strFrom			= !empty($this->input['ar_from'])    ? $this->input['ar_from']    : $this->site_name." <".$this->site_email.">";
			$strSubject			= !empty($this->input['ar_subject']) ? $this->input['ar_subject'] : "Your E-mail Was Received";

			$l_strHeaders		= "MIME-Version: 1.0".$this->lf;
			$l_strHeaders		.= "Content-type: text/html; charset=iso-8859-1".$this->lf;
			$l_strHeaders		.= "To: ".addslashes($this->sender_name)." <".$this->sender_email.">".$this->lf;
			$l_strHeaders		.= "From: ".$strFrom.$this->lf;
			
			if( empty($strFile) ) {
				$this->status_message("ERROR:","Your e-mail was sent, but auto-response file could not be found.  Please notify webmaster",1);
			}
			else {
				$strHTML			= file_get_contents($strFile);
				
				mail( '', $strSubject, $strHTML, $l_strHeaders );
			}
		}
		function getPostedData() {
			if( $this->magic_quotes_on ) {
			  foreach( $_POST AS $key => $value ) {
				$this->input[$key]	= stripslashes( $value );
			  }
			}
			else {
			  $this->input			=& $_POST;
			}
		}
	}
?>
