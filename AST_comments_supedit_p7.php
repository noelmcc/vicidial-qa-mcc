<?php

# Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#

session_start();

header ("Content-type: text/html; charset=utf-8");

require("dbconnect.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];

	
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


//do get posts here
if (isset($_GET["campaigns"]))				{$case_cid=$_GET["campaigns"];}
	elseif (isset($_POST["campaigns"]))		{$case_cid=$_POST["campaigns"];}

if (isset($_GET["campaigns"]))				{$seq_num=$_GET["seqnum"];}
	elseif (isset($_POST["campaigns"]))		{$seq_num=$_POST["seqnum"];}


	
?>

<htmL>
<head>
<title>VICIDIAL ADMIN: Evaluation</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">

<style>
body {
	font-family: Arial, Helvetica;
}

</style>


</head>

<BODY BGCOLOR="white" marginheight="5" marginwidth="5" leftmargin="5" topmargin="5">
<TABLE WIDTH="100%" BGCOLOR="#D9E6FE" cellpadding="10" cellspacing="0">
	<TR BGCOLOR="#015B91">
		<TD ALIGN="LEFT">
			<FONT FACE="ARIAL,HELVETICA" COLOR=WHITE SIZE=2><B>VICIDIAL ADMIN</a>: Submit Evaluation</font>
		</TD>
	</TR> 
	<TR BGCOLOR="#F0F5FE">
		<td align = "center"> 
		
	<?php
		
		$currdate = date("Y-m-d h:m:s");
		
		$newactq = $_POST["newactq"];
		//echo "newactq: ".$newactq."<br>";
		
		if (empty($newactq)) {
			echo "No choice made. Please choose a question to enable.<br> \n";
		echo "</td> \n";
	echo "</tr> \n";
	echo "<TR BGCOLOR=\"#015B91\"> \n";
		echo "<td align=\"center\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Cancel\" onClick=\"window.close();\"> \n";
		echo "</td> \n";
	echo "</tr> \n";
echo "</table> \n";
		} else {
			
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = 'INACTIVE' WHERE seq_num = '".$seq_num."' AND status = 'ACTIVE' ") or die(mysql_error());  
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = 'ACTIVE' WHERE seq_num = '".$seq_num."' AND question = '".$newactq."' ") or die(mysql_error());
		
		echo "Question enabled! You may now close this window.<br> \n";
		
		echo "</td> \n";
	echo "</tr> \n";
	echo "<TR BGCOLOR=\"#015B91\"> \n";
		echo "<td align=\"center\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Close Window\" onClick=\"window.opener.location.href='AST_comments_supedit_p2.php?campaigns=".$case_cid."';window.close();\"> \n";
		echo "</td> \n";
	echo "</tr> \n";
echo "</table> \n";
			
		}
		
		


		
				

		?>
		
		
