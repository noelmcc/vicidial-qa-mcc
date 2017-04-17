<?php

# Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#

session_start();

header ("Content-type: text/html; charset=utf-8");

require("dbconnect.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
	
if (isset($_GET["comment_date"]))				{$comment_date=$_GET["comment_date"];}
	elseif (isset($_POST["comment_date"]))		{$comment_date=$_POST["comment_date"];}	
if (isset($_GET["call_date"]))				{$call_date=$_GET["call_date"];}
	elseif (isset($_POST["call_date"]))		{$call_date=$_POST["call_date"];}	
if (isset($_GET["uniqueid"]))				{$uniqueid=$_GET["uniqueid"];}
	elseif (isset($_POST["uniqueid"]))		{$uniqueid=$_POST["uniqueid"];}	
if (isset($_GET["user"]))				{$sent_user=$_GET["user"];}
	elseif (isset($_POST["user"]))		{$sent_user=$_POST["user"];}	
if (isset($_GET["user_group"]))				{$user_group=$_GET["user_group"];}
	elseif (isset($_POST["user_group"]))		{$user_group=$_POST["user_group"];}	
if (isset($_GET["campaigns"]))				{$campaign_id=$_GET["campaigns"];}
	elseif (isset($_POST["campaigns"]))		{$campaign_id=$_POST["campaigns"];}
if (isset($_GET["specialist"]))				{$supervisor_id=$_GET["specialist"];}
	elseif (isset($_POST["specialist"]))		{$supervisor_id=$_POST["specialist"];}
if (isset($_GET["rate1"]))				{$rate1=$_GET["rate1"];}
	elseif (isset($_POST["rate1"]))		{$rate1=$_POST["rate1"];}	
if (isset($_GET["rate2"]))				{$rate2=$_GET["rate2"];}
	elseif (isset($_POST["rate2"]))		{$rate2=$_POST["rate2"];}
if (isset($_GET["rate3"]))				{$rate3=$_GET["rate3"];}
	elseif (isset($_POST["rate3"]))		{$rate3=$_POST["rate3"];}
if (isset($_GET["rate4"]))				{$rate4=$_GET["rate4"];}
	elseif (isset($_POST["rate4"]))		{$rate4=$_POST["rate4"];}
if (isset($_GET["rate5"]))				{$rate5=$_GET["rate5"];}
	elseif (isset($_POST["rate5"]))		{$rate5=$_POST["rate5"];}
if (isset($_GET["rate6"]))				{$rate6=$_GET["rate6"];}
	elseif (isset($_POST["rate6"]))		{$rate6=$_POST["rate6"];}
if (isset($_GET["rate7"]))				{$rate7=$_GET["rate7"];}
	elseif (isset($_POST["rate7"]))		{$rate7=$_POST["rate7"];}
if (isset($_GET["rate8"]))				{$rate8=$_GET["rate8"];}
	elseif (isset($_POST["rate8"]))		{$rate8=$_POST["rate8"];}
if (isset($_GET["rate9"]))				{$rate9=$_GET["rate9"];}
	elseif (isset($_POST["rate9"]))		{$rate9=$_POST["rate9"];}
if (isset($_GET["rate10"]))				{$rate10=$_GET["rate10"];}
	elseif (isset($_POST["rate10"]))		{$rate10=$_POST["rate10"];}
if (isset($_GET["af1"]))				{$af1=$_GET["af1"];}
	elseif (isset($_POST["af1"]))		{$af1=$_POST["af1"];}
if (isset($_GET["af2"]))				{$af2=$_GET["af2"];}
	elseif (isset($_POST["af2"]))		{$af2=$_POST["af2"];}
if (isset($_GET["af3"]))				{$af3=$_GET["af3"];}
	elseif (isset($_POST["af3"]))		{$af3=$_POST["af3"];}
if (isset($_GET["af4"]))				{$af4=$_GET["af4"];}
	elseif (isset($_POST["af4"]))		{$af4=$_POST["af4"];}
if (isset($_GET["af5"]))				{$af5=$_GET["af5"];}
	elseif (isset($_POST["af5"]))		{$af5=$_POST["af5"];}
if (isset($_GET["af6"]))				{$af6=$_GET["af6"];}
	elseif (isset($_POST["af6"]))		{$af6=$_POST["af6"];}
if (isset($_GET["af7"]))				{$af7=$_GET["af7"];}
	elseif (isset($_POST["af7"]))		{$af7=$_POST["af7"];}
if (isset($_GET["af8"]))				{$af8=$_GET["af8"];}
	elseif (isset($_POST["af8"]))		{$af8=$_POST["af8"];}
if (isset($_GET["af9"]))				{$af9=$_GET["af9"];}
	elseif (isset($_POST["af9"]))		{$af9=$_POST["af9"];}
if (isset($_GET["af10"]))				{$af10=$_GET["af10"];}
	elseif (isset($_POST["af10"]))		{$af10=$_POST["af10"];}
if (isset($_GET["final_score"]))				{$sent_final_score=$_GET["final_score"];}
	elseif (isset($_POST["final_score"]))		{$sent_final_score=$_POST["final_score"];}
if (isset($_GET["comments"]))				{$sent_comments=$_GET["comments"];}
	elseif (isset($_POST["comments"]))		{$sent_comments=$_POST["comments"];}
if (isset($_GET["admin_note"]))				{$admin_note=$_GET["admin_note"];}
	elseif (isset($_POST["admin_note"]))		{$admin_note=$_POST["admin_note"];}
	
/* check if comments field is blank */
if (empty($sent_comments)){
	$sent_comments = $admin_note;
}
	
if (isset($_GET["scoring"]))				{$scoring=$_GET["scoring"];}
	elseif (isset($_POST["scoring"]))		{$scoring=$_POST["scoring"];}
if (isset($_GET["lead_id"]))				{$lead_id=$_GET["lead_id"];}
	elseif (isset($_POST["lead_id"]))		{$lead_id=$_POST["lead_id"];}
if (isset($_GET["call_status"]))				{$call_status=$_GET["call_status"];}
	elseif (isset($_POST["call_status"]))		{$call_status=$_POST["call_status"];}
if (isset($_GET["ingroup"]))				{$ingroup=$_GET["ingroup"];}
	elseif (isset($_POST["ingroup"]))		{$ingroup=$_POST["ingroup"];}
if (isset($_GET["search"]))				{$post_search=$_GET["search"];}
	elseif (isset($_POST["search"]))		{$post_search=$_POST["search"];}
	
//test submissions

/* echo "comment_date: ".$comment_date."<br>";
echo "uniqueid: ".$uniqueid."<br>";
echo "sent_user: ".$sent_user."<br>";
echo "user_group: ".$user_group."<br>";
echo "campaign_id: ".$campaign_id."<br>";
echo "supervisor_id: ".$supervisor_id."<br>";
echo "rate1: ".$rate1."<br>";
echo "rate2: ".$rate2."<br>";
echo "rate3: ".$rate3."<br>";
echo "rate4: ".$rate4."<br>";
echo "rate5: ".$rate5."<br>";
echo "rate6: ".$rate6."<br>";
echo "rate7: ".$rate7."<br>";
echo "rate8: ".$rate8."<br>";
echo "rate9: ".$rate9."<br>";
echo "rate10: ".$rate10."<br>";
echo "af1: ".$af1."<br>";
echo "af2: ".$af2."<br>";
echo "af3: ".$af3."<br>";
echo "af4: ".$af4."<br>";
echo "af5: ".$af5."<br>";
echo "af6: ".$af6."<br>";
echo "af7: ".$af7."<br>";
echo "af8: ".$af8."<br>";
echo "af9: ".$af9."<br>";
echo "af10: ".$af10."<br>";
echo "sent_final_score: ".$sent_final_score."<br>";
echo "sent_comments: ".$sent_comments."<br>";
echo "admin_note: ".$admin_note."<br>";
echo "lead_id: ".$lead_id."<br>";
echo "call_status: ".$call_status."<br>";
echo "ingroup: ".$ingroup."<br>";
echo "scoring: ".$scoring."<br>"; */

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

		
	
?>

<htmL>
<head>
<title>VICIDIAL ADMIN: Add Questions</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">

<style>
body {
	font-family: Arial, Helvetica;
}

</style>


</head>

<BODY BGCOLOR="white" marginheight="5" marginwidth="5" leftmargin="5" topmargin="5">
<TABLE WIDTH="100%" BGCOLOR="#D9E6FE" cellpadding="10" cellspacing="0" border = "0">
	<TR BGCOLOR="#015B91">
		<TD ALIGN="LEFT">
			<FONT FACE="ARIAL,HELVETICA" COLOR="WHITE" SIZE="2"><B>VICIDIAL ADMIN: Edit Scoresheet</font>
		</TD>
	</TR> 
	<TR BGCOLOR="#F0F5FE">
		<td align = "CENTER"> 
		
			<?php
		
			//determine if any autofail has been marked as failed
			$f = 0;	//start af counter
			if ($rate1 == "FAIL" && $af1 == "Y") {$f++;} 
			if ($rate2 == "FAIL" && $af2 == "Y") {$f++;}
			if ($rate3 == "FAIL" && $af3 == "Y") {$f++;}
			if ($rate4 == "FAIL" && $af4 == "Y") {$f++;}
			if ($rate5 == "FAIL" && $af5 == "Y") {$f++;}
			if ($rate6 == "FAIL" && $af6 == "Y") {$f++;}
			if ($rate7 == "FAIL" && $af7 == "Y") {$f++;}
			if ($rate8 == "FAIL" && $af8 == "Y") {$f++;}
			if ($rate9 == "FAIL" && $af9 == "Y") {$f++;}
			if ($rate10 == "FAIL" && $af10 == "Y") {$f++;}

			//determine number of answers
			$a = 0;		//start answer counter
			if (!empty($rate1)) {$a++;}
			if (!empty($rate2)) {$a++;}
			if (!empty($rate3)) {$a++;}
			if (!empty($rate4)) {$a++;}
			if (!empty($rate5)) {$a++;}
			if (!empty($rate6)) {$a++;}
			if (!empty($rate7)) {$a++;}
			if (!empty($rate8)) {$a++;}
			if (!empty($rate9)) {$a++;}
			if (!empty($rate10)) {$a++;}
	
			//check results
			$p = 0;		//check for PASS scores
			if ($rate1 == "PASS") {$p++;}
			if ($rate2 == "PASS") {$p++;}
			if ($rate3 == "PASS") {$p++;}
			if ($rate4 == "PASS") {$p++;}
			if ($rate5 == "PASS") {$p++;}
			if ($rate6 == "PASS") {$p++;}
			if ($rate7 == "PASS") {$p++;}
			if ($rate8 == "PASS") {$p++;}
			if ($rate9 == "PASS") {$p++;}
			if ($rate10 == "PASS") {$p++;}
	
			$failcount = $a-$p;
	
			//echo "af count: ".$f."<br> \n";
			//echo "ans count: ".$a."<br> \n";
			//echo "pass count: ".$p."<br> \n";
			//echo "fail count: ".$failcount."<br>";
	
			if ($scoring == "PS") {
				$midpoint = ($a / 2);
				$numfscore = "<b>".number_format((float)$sp, 2, '.', '')."</b> Points";
				if ($p > $midpoint) {
					$new_fscore = "<font size = \"+3\" color = \"green\"><b>PASSED</b></font>";
					$post_fscore = "PASS";
				} else {
					$new_fscore = "<font size = \"+3\" color = \"red\"><b>FAILED</b></font>";
					$post_fscore = "FAIL";
				}
		
			} elseif ($scoring == "AV") {
				$midpoint = "49.99";
				$avscore = (($p / $a)*100);
				$numfscore = "<b>".number_format((float)$avscore, 2, '.', '')."</b> Average Points";
				//echo round($avscore, 2);
				if ($avscore > $midpoint) {
					$new_fscore = "<font size = \"+3\" color = \"green\"><b>PASSED</b></font>";
					$post_fscore = "PASS";
				} else {
					$new_fscore = "<font size = \"+3\" color = \"red\"><b>FAILED</b></font>";
					$post_fscore = "FAIL";
				}
		
			} else {
				$midpoint = "49.99";
				$pcscore = (($p / $a)*100);
				$numfscore = "<b>".number_format((float)$pcscore, 2, '.', '')."</b> Percent";
				//echo round($avscore, 2);
				if ($pcscore > $midpoint) {
					$new_fscore = "<font size = \"+3\" color = \"green\"><b>PASSED</b></font>";
					$post_fscore = "PASS";
				} else {
					$new_fscore = "<font size = \"+3\" color = \"red\"><b>FAILED</b></font>";
					$post_fscore = "FAIL";
				}
		
			}
	
			if ($f > 0) {
				$autofail = "1";
				$new_fscore = "<font size = \"+3\" color = \"red\"><b>AUTOFAILED</b></font>";
				$post_fscore = "FAIL";
			} else {
			
			}
	
			if ($autofail == "1") {
				echo "Your new evaluation results: <br> \n";
				echo $new_fscore."<br>";
				echo "You may want to re-evaluate. \n";
			} else {
				echo "Your new evaluation results: <br> \n";
				echo $new_fscore."<br>";
				echo "With ".$numfscore.".<br> \n";
				//echo "Passing Score is <b>".number_format((float)$midpoint, 2, '.', '')."</b>. \n";
				echo "Passing Score is <b> 50.00</b>. \n";
			}

		echo "</td> \n";
	echo "</tr> \n";
	
	echo "<tr bgcolor = \"#015B91\"> \n";
		echo "<td align = \"center\" valign=\"top\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Click to Cancel or Re-evaluate\" onClick=\"window.close()\"> \n";
			echo "<input type =\"button\" onclick=\"location.href='AST_comments_edit_review_commit.php'\" value=\"Submit New Evaluation\" /> \n";
		echo "</td> \n";
	echo "</tr> \n";
	
echo "</table> \n";

$_SESSION['comment_date'] = $comment_date;
$_SESSION['call_date'] = $call_date;
$_SESSION['uniqueid'] = $uniqueid;
$_SESSION['sent_user'] = $sent_user;
$_SESSION['user_group'] = $user_group;
$_SESSION['campaign_id'] = $campaign_id;
$_SESSION['supervisor_id'] = $supervisor_id;
$_SESSION['rate1'] = $rate1;
$_SESSION['rate2'] = $rate2;
$_SESSION['rate3'] = $rate3;
$_SESSION['rate4'] = $rate4;
$_SESSION['rate5'] = $rate5;
$_SESSION['rate6'] = $rate6;
$_SESSION['rate7'] = $rate7;
$_SESSION['rate8'] = $rate8;
$_SESSION['rate9'] = $rate9;
$_SESSION['rate10'] = $rate10;
$_SESSION['sent_comments'] = $sent_comments;
$_SESSION['admin_note'] = $admin_note;
$_SESSION['post_fscore'] = $post_fscore;
$_SESSION['lead_id'] = $lead_id;
$_SESSION['call_status'] = $call_status;
$_SESSION['ingroup'] = $ingroup;
$_SESSION['post_search'] = $post_search;



	?>

</body>
</html>	
		
