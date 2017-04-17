<?php

# Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#

header ("Content-type: text/html; charset=utf-8");

require("dbconnect.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];

if (isset($_GET["newscore"]))				{$sent_scoring=$_GET["newscore"];}
	elseif (isset($_POST["newscore"]))		{$sent_scoring=$_POST["newscore"];}

if (isset($_GET["camp_id"]))				{$sent_cid=$_GET["camp_id"];}
	elseif (isset($_POST["camp_id"]))		{$sent_cid=$_POST["camp_id"];}
	
$PHP_AUTH_USER = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_USER);
$PHP_AUTH_PW = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_PW);

$STARTtime = date("U");
$TODAY = date("Y-m-d");

if (!isset($begin_date)) {$begin_date = $TODAY;}
if (!isset($end_date)) {$end_date = $TODAY;}

	$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 7 and view_reports='1';";
	$rslt=mysql_query($stmt, $link);
	$row=mysql_fetch_row($rslt);
	$auth=$row[0];

$fp = fopen ("./project_auth_entries_qa.txt", "a");
$date = date("r");
$ip = getenv("REMOTE_ADDR");
$browser = getenv("HTTP_USER_AGENT");
$pagename = basename($_SERVER['PHP_SELF']);

  if( (strlen($PHP_AUTH_USER)<2) or (strlen($PHP_AUTH_PW)<2) or (!$auth))
	{
    Header("WWW-Authenticate: Basic realm=\"VICI-PROJECTS\"");
    Header("HTTP/1.0 401 Unauthorized");
    echo "Invalid Username/Password: |$PHP_AUTH_USER|$PHP_AUTH_PW|\n";
    exit;
	}
  else
	{

	if($auth>0)
		{
			$stmt="SELECT full_name from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW'";
			$rslt=mysql_query($stmt, $link);
			$row=mysql_fetch_row($rslt);
			$LOGfullname=$row[0];
		fwrite ($fp, "VICIDIAL|GOOD|$date|$PHP_AUTH_USER|$PHP_AUTH_PW|$ip|$browser|$LOGfullname|$pagename|\n");
		fclose($fp);
		}
	else
		{
		fwrite ($fp, "VICIDIAL|FAIL|$date|$PHP_AUTH_USER|$PHP_AUTH_PW|$ip|$browser|$pagename|\n");
		fclose($fp);
		}

	$stmt="SELECT full_name from vicidial_users where user='$user';";
	$rslt=mysql_query($stmt, $link);
	$row=mysql_fetch_row($rslt);
	$full_name = $row[0];

	}

//echo "sent scoring: ".$sent_scoring."<br>";		
//echo "sent cid: ".$sent_cid."<br>";		
	
?>

<htmL>
<head>
<title>VICIDIAL ADMIN: Add Questions</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">

<style>
body {
	font-family: Arial, Helvetica;
}

input[type="number"] {
    width: 50px;
}
</style>


</head>

<BODY BGCOLOR="white" marginheight="5" marginwidth="5" leftmargin="5" topmargin="5">
<TABLE WIDTH="100%" BGCOLOR="#D9E6FE" cellpadding="10" cellspacing="0">
	<TR BGCOLOR="#015B91">
		<TD ALIGN="LEFT">
			<FONT FACE="ARIAL,HELVETICA" COLOR=WHITE SIZE=2><B>VICIDIAL ADMIN</a>: Add Questions</font>
		</TD>
	</TR> 
	<TR BGCOLOR="#F0F5FE">
		<td align = "center"> 
		
	<?php
		$result = mysql_query("SELECT * FROM vicidial_campaigns WHERE campaign_id = '".$sent_cid."'");
			$row = mysql_fetch_array($result);
			$curr_score = $row['scoring'];
			
		if ($curr_score == $sent_scoring) {
		
			echo "No Changes Made.<p> \n";
		
		echo "</td> \n";
	echo "</tr> \n";
	echo "<TR BGCOLOR=\"#015B91\"> \n";
		echo "<td align = \"center\"> \n";
			echo "<center><INPUT TYPE=\"button\" VALUE=\"Cancel\" onClick=\"window.close()\"></center> \n";
		echo "</td> \n";
	echo "</tr> \n";
echo "</table> \n";

		} else { 
			mysql_query("UPDATE vicidial_campaigns SET scoring = '".$sent_scoring."' WHERE campaign_id = '".$sent_cid."'") or die(mysql_error());
			if ($sent_scoring == "PS"){
				$score_name = "Point System (PS)";
			} elseif ($sent_scoring == "AV") {
				$score_name = "Avergaing System (AV)";
			} else {
				$score_name = "Percentile System (PC)";
			}
			echo "Scoring System changed to <b>".$score_name."</b>.<p> \n";
			
		echo "</td> \n";
	echo "</tr> \n";
	echo "<TR BGCOLOR=\"#015B91\"> \n";
		echo "<td align = \"center\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Click Here to Continue Editing\" onClick=\"window.opener.location.reload();window.close();\"> \n";
		echo "</td> \n";
	echo "</tr> \n";
echo "</table> \n";
		}

	?>
		
		
