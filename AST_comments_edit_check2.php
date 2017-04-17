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


$sent_cid = $_SESSION['campid'];
$uniqueid = $_SESSION['uniqueid'];
$agent = $_SESSION['agent'];
$usergroup = $_SESSION['usergroup'];
$supid = $_SESSION['supid'];
$comments = $_SESSION['comments'];
$rate01 = $_SESSION['rate01'];
$rate02 = $_SESSION['rate02'];
$rate03 = $_SESSION['rate03'];
$rate04 = $_SESSION['rate04'];
$rate05 = $_SESSION['rate05'];
$rate06 = $_SESSION['rate06'];
$rate07 = $_SESSION['rate07'];
$rate08 = $_SESSION['rate08'];
$rate09 = $_SESSION['rate09'];
$rate010 = $_SESSION['rate010'];
$scoring = $_SESSION['scoring'];
$fscore = $_SESSION['fscore'];
$af1 = $_SESSION['af1'];
$af2 = $_SESSION['af2'];
$af3 = $_SESSION['af3'];
$af4 = $_SESSION['af4'];
$af5 = $_SESSION['af5'];
$af6 = $_SESSION['af6'];
$af7 = $_SESSION['af7'];
$af8 = $_SESSION['af8'];
$af9 = $_SESSION['af9'];
$af10 = $_SESSION['af10'];

//test post submissions
//echo "sent_cid: ".$sent_cid."<br> \n";
//echo "uniqueid: ".$uniqueid."<br> \n";
//echo "agent: ".$agent."<br> \n";
//echo "usergroup: ".$usergroup."<br> \n";
//echo "supid: ".$supid."<br> \n";
//echo "comments: ".$comments."<br> \n";
//echo "rate01: ".$rate01."<br> \n";
//echo "rate02: ".$rate02."<br> \n";
//echo "rate03: ".$rate03."<br> \n";
//echo "rate04: ".$rate04."<br> \n";
//echo "rate05: ".$rate05."<br> \n";
//echo "rate06: ".$rate06."<br> \n";
//echo "rate07: ".$rate07."<br> \n";
//echo "rate08: ".$rate08."<br> \n";
//echo "rate09: ".$rate09."<br> \n";
//echo "rate010: ".$rate010."<br> \n";
//echo "scoring: ".$scoring."<br> \n";
//echo "af1: ".$af1."<br> \n";
//echo "af2: ".$af2."<br> \n";
//echo "af3: ".$af3."<br> \n";
//echo "af4: ".$af4."<br> \n";
//echo "af5: ".$af5."<br> \n";
//echo "af6: ".$af6."<br> \n";
//echo "af7: ".$af7."<br> \n";
//echo "af8: ".$af8."<br> \n";
//echo "af9: ".$af9."<br> \n";
//echo "af10: ".$af10."<br> \n";
//echo "fscore: ".$fscore."<br> \n";	
	
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
		
		if ($fscore < 50.00) {
			$fscorename = "FAIL";
		} else {
			$fscorename = "PASS";
		}
		
		$currdate = date("Y-m-d h:m:s");
		
		mysql_query("UPDATE vicidial_agent_comments SET comment_date = '$currdate' WHERE uniqueid = '$uniqueid' AND comments = '' ") or die(mysql_error());  
		mysql_query("UPDATE vicidial_agent_comments SET user = '$agent' WHERE uniqueid = '$uniqueid' AND comments = '' ") or die(mysql_error());  
		mysql_query("UPDATE vicidial_agent_comments SET user_group = '$usergroup' WHERE uniqueid = '$uniqueid' AND comments = '' ") or die(mysql_error());  
		mysql_query("UPDATE vicidial_agent_comments SET campaign_id = '$sent_cid' WHERE uniqueid = '$uniqueid' AND comments = '' ") or die(mysql_error());  
		mysql_query("UPDATE vicidial_agent_comments SET supervisor_id = '$supid' WHERE uniqueid = '$uniqueid' AND comments = '' ") or die(mysql_error());  
		mysql_query("UPDATE vicidial_agent_comments SET comments = '$comments' WHERE uniqueid = '$uniqueid' AND comments = '' ") or die(mysql_error());  
		mysql_query("UPDATE vicidial_agent_comments SET rate01 = '$rate01' WHERE uniqueid = '$uniqueid' AND comments = '$comments' ") or die(mysql_error());  
		mysql_query("UPDATE vicidial_agent_comments SET rate02 = '$rate02' WHERE uniqueid = '$uniqueid' AND comments = '$comments' ") or die(mysql_error());  
		mysql_query("UPDATE vicidial_agent_comments SET rate03 = '$rate03' WHERE uniqueid = '$uniqueid' AND comments = '$comments' ") or die(mysql_error());  
		mysql_query("UPDATE vicidial_agent_comments SET rate04 = '$rate04' WHERE uniqueid = '$uniqueid' AND comments = '$comments' ") or die(mysql_error());  
		mysql_query("UPDATE vicidial_agent_comments SET rate05 = '$rate05' WHERE uniqueid = '$uniqueid' AND comments = '$comments' ") or die(mysql_error());  
		mysql_query("UPDATE vicidial_agent_comments SET rate06 = '$rate06' WHERE uniqueid = '$uniqueid' AND comments = '$comments' ") or die(mysql_error());  
		mysql_query("UPDATE vicidial_agent_comments SET rate07 = '$rate07' WHERE uniqueid = '$uniqueid' AND comments = '$comments' ") or die(mysql_error());  
		mysql_query("UPDATE vicidial_agent_comments SET rate08 = '$rate08' WHERE uniqueid = '$uniqueid' AND comments = '$comments' ") or die(mysql_error());  
		mysql_query("UPDATE vicidial_agent_comments SET rate09 = '$rate09' WHERE uniqueid = '$uniqueid' AND comments = '$comments' ") or die(mysql_error());  
		mysql_query("UPDATE vicidial_agent_comments SET rate010 = '$rate010' WHERE uniqueid = '$uniqueid' AND comments = '$comments' ") or die(mysql_error());  
		mysql_query("UPDATE vicidial_agent_comments SET fscore = '$fscorename' WHERE uniqueid = '$uniqueid' AND comments = '$comments' ") or die(mysql_error());  
		mysql_query("UPDATE vicidial_agent_comments SET created_by = '$supid' WHERE uniqueid = '$uniqueid' AND comments = '$comments' ") or die(mysql_error());  
		
		echo "Evaluation saved! You may now close this window.<br> \n";
		
		echo "</td> \n";
	echo "</tr> \n";
	echo "<TR BGCOLOR=\"#015B91\"> \n";
		echo "<td align=\"center\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Close Window\" onClick=\"window.opener.location.href='AST_comments_search.php';window.close();\"> \n";
		echo "</td> \n";
	echo "</tr> \n";
echo "</table> \n";


		
				

		?>
		
		
