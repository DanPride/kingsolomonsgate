<?php


	$theFolder = "http://www.kingsolomonsgate.com/images/FamilyPhotos/"; 
	$webFilePaths = glob(""); //Webs
	foreach (glob("*.jpg") as $filename) {
	    echo '<a href=' .  $theFolder . $filename . ">" . $filename . "<br>";
	}
	
?>