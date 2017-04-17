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

$sent_cid = $_POST['campaign_id'];
$uniqueid = $_POST['uniqueid'];
$agent = $_POST['user'];
$usergroup = $_POST['usergroup'];
$supid = $_POST['supid'];
$comments = $_POST['comments'];
$rate01 = $_POST['rate01'];
$rate02 = $_POST['rate02'];
$rate03 = $_POST['rate03'];
$rate04 = $_POST['rate04'];
$rate05 = $_POST['rate05'];
$rate06 = $_POST['rate06'];
$rate07 = $_POST['rate07'];
$rate08 = $_POST['rate08'];
$rate09 = $_POST['rate09'];
$rate010 = $_POST['rate010'];
$scoring = $_POST['scoring'];
$af1 = $_POST['af1'];
$af2 = $_POST['af2'];
$af3 = $_POST['af3'];
$af4 = $_POST['af4'];
$af5 = $_POST['af5'];
$af6 = $_POST['af6'];
$af7 = $_POST['af7'];
$af8 = $_POST['af8'];
$af9 = $_POST['af9'];
$af10 = $_POST['af10'];

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
		<td align = "justify"> 
		
<?php
		
	if (($rate01 == "noscore" ) || ($rate02 == "noscore" ) || ($rate03 == "noscore" ) || ($rate04 == "noscore" ) || ($rate05 == "noscore" ) || ($rate05 == "noscore" ) || ($rate06 == "noscore" ) || ($rate07 == "noscore" ) || ($rate08 == "noscore" ) || ($rate09 == "noscore" ) || ($rate010 == "noscore" )) {
			
			echo "One or more of your scores is invalid. Please close this window and check your scores.<p> \n";
		echo "</td> \n";
	echo "</tr> \n";
	echo "<TR BGCOLOR=\"#015B91\"> \n";
		echo "<td align=\"center\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"CANCEL\" onClick=\"window.close()\"> \n";
		echo "</td> \n";
	echo "</tr> \n";
echo "</table> \n";
			
	} elseif ($comments == "") {
		
		echo "Comments field cannot be empty. Please close this window and enter your evaluation comments.<p> \n";
		echo "</td> \n";
	echo "</tr> \n";
	echo "<TR BGCOLOR=\"#015B91\"> \n";
		echo "<td align=\"center\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"CANCEL\" onClick=\"window.close()\"> \n";
		echo "</td> \n";
	echo "</tr> \n";
echo "</table> \n";

	} else {			//final score compute and check
		
		$passcount = 0;
		$failcount = 0;
		$afcount = 0;
		
		if ($rate01 == "PASS") {
			$passcount++;
		} elseif ($af1 == "Y") {
			$failcount++;
			$afcount++;
		} elseif ($rate01 == "") {
			//echo "rate01 not included<br> \n";
		} else {
			$failcount++;
		}
		
		if ($rate02 == "PASS") {
			$passcount++;
		} elseif ($af2 == "Y") {
			$failcount++;
			$afcount++;
		} elseif ($rate02 == "") {
			//echo "rate02 not included<br> \n";
		} else {
			$failcount++;
		}
		
		if ($rate03 == "PASS") {
			$passcount++;
		} elseif ($af3 == "Y") {
			$failcount++;
			$afcount++;
		} elseif ($rate03 == "") {
			//echo "rate03 not included<br> \n";
		} else {
			$failcount++;
		}
		
		if ($rate04 == "PASS") {
			$passcount++;
		} elseif ($af4 == "Y") {
			$failcount++;
			$afcount++;
		} elseif ($rate04 == "") {
			//echo "rate04 not included<br> \n";
		} else {
			$failcount++;
		}
		
		if ($rate05 == "PASS") {
			$passcount++;
		} elseif ($af5 == "Y") {
			$failcount++;
			$afcount++;
		} elseif ($rate05 == "") {
		} else {
			$failcount++;
		}
		
		if ($rate06 == "PASS") {
			$passcount++;
		} elseif ($af6 == "Y") {
			$failcount++;
			$afcount++;
		} elseif ($rate06 == "") {
			//echo "rate06 not included<br> \n";
		} else {
			$failcount++;
		}
		
		if ($rate07 == "PASS") {
			$passcount++;
		} elseif ($af7 == "Y") {
			$failcount++;
			$afcount++;
		} elseif ($rate07 == "") {
			//echo "rate07 not included<br> \n";
		} else {
			$failcount++;
		}
		
		if ($rate08 == "PASS") {
			$passcount++;
		} elseif ($af8 == "Y") {
			$failcount++;
			$afcount++;
		} elseif ($rate08 == "") {
			//echo "rate08 not included<br> \n";
		} else {
			$failcount++;
		}
		
		if ($rate09 == "PASS") {
			$passcount++;
		} elseif ($af9 == "Y") {
			$failcount++;
			$afcount++;
		} elseif ($rate09 == "") {
			//echo "rate09 not included<br> \n";
		} else {
			$failcount++;
		}
		
		if ($rate010 == "PASS") {
			$passcount++;
		} elseif ($af10 == "Y") {
			$failcount++;
			$afcount++;
		} elseif ($rate010 == "") {
			//echo "rate010 not included<br> \n";
		} else {
			$failcount++;
		}
		
	if ($afcount > 0) {
		$fscore = "FAILED";
			echo "<center> \n";
			echo "<b><font color = \"red\" size = \"+2\">Evaluation failed!</b></font> <br> \n";
			echo "The Agent did not pass one or more <b>AUTOFAIL</b> questions. \n";
			echo "</center> \n";
		echo "</td> \n";
	echo "</tr> \n";
	echo "<TR BGCOLOR=\"#015B91\"> \n";
		echo "<td align=\"center\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Record Evaluation\" onClick=\"#\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Re-evaluate\" onClick=\"window.close()\"> \n";
		echo "</td> \n";
	echo "</tr> \n";
echo "</table> \n";

	} elseif ($scoring == "PS") {
		$case_cid = strtolower($sent_cid);
		$result = mysql_query("SELECT COUNT(status) AS count FROM questions_cid_".$case_cid." WHERE status = 'ACTIVE' ") or die(mysql_error());
			$row = mysql_fetch_array($result);
			$count = $row['count'];
			
		if ($passcount == 0) {
			$fscore == "FAILED";
			echo "<center> \n";
			echo "<b><font color = \"red\" size = \"+2\">Evaluation failed!</b></font> <br> \n";
			echo "The Agent did not pass any criteria. \n";
			echo "</center> \n";
		echo "</td> \n";
	echo "</tr> \n";
	echo "<TR BGCOLOR=\"#015B91\"> \n";
		echo "<td align=\"center\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Record Evaluation\" onClick=\"location.href='AST_comments_edit_check2.php'\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Re-evaluate\" onClick=\"window.close()\"> \n";
		echo "</td> \n";
	echo "</tr> \n";
echo "</table> \n";

		} elseif ($passcount > 0) {
			$ps_ave = (($passcount / $count) * 100);
			$fscore = number_format((float)$ps_ave, 2, '.', '');
			
			if ($fscore > 50.00) {
				
			echo "<center> \n";
			echo "<b><font color = \"green\" size = \"+2\">Evaluation PASSED!!</b></font> <br> \n";
			echo "The Agent received a <b>FINAL SCORE</b> of <b>".$fscore." Points</b>. \n";
			echo "</center> \n";
		echo "</td> \n";
	echo "</tr> \n";
	echo "<TR BGCOLOR=\"#015B91\"> \n";
		echo "<td align=\"center\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Record Evaluation\" onClick=\"location.href='AST_comments_edit_check2.php'\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Re-evaluate\" onClick=\"window.close()\"> \n";
		echo "</td> \n";
	echo "</tr> \n";
echo "</table> \n";	
				
			} else {
				
			echo "<center> \n";
			echo "<b><font color = \"red\" size = \"+2\">Evaluation FAILED!!</b></font> <br> \n";
			echo "The Agent received a <b>FINAL SCORE</b> of <b>".$fscore." Points</b>. \n";
			echo "Passing score is <b>50.00 Points</b>. \n"; //change the value to db pull value
			echo "</center> \n";
		echo "</td> \n";
	echo "</tr> \n";
	echo "<TR BGCOLOR=\"#015B91\"> \n";
		echo "<td align=\"center\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Record Evaluation\" onClick=\"location.href='AST_comments_edit_check2.php'\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Re-evaluate\" onClick=\"window.close()\"> \n";
		echo "</td> \n";
	echo "</tr> \n";
echo "</table> \n";	
				
			}

		} else {
			//do nothing
		}
			
	} elseif ($scoring == "AV") {
		$case_cid = strtolower($sent_cid);
		$result = mysql_query("SELECT COUNT(status) AS count FROM questions_cid_".$case_cid." WHERE status = 'ACTIVE' ") or die(mysql_error());
			$row = mysql_fetch_array($result);
			$count = $row['count'];
			
		if ($passcount == 0) {
			$fscore == "FAILED";
			echo "<center> \n";
			echo "<b><font color = \"red\" size = \"+2\">Evaluation failed!</b></font> <br> \n";
			echo "The Agent did not pass any criteria. \n";
			echo "</center> \n";
		echo "</td> \n";
	echo "</tr> \n";
	echo "<TR BGCOLOR=\"#015B91\"> \n";
		echo "<td align=\"center\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Record Evaluation\" onClick=\"location.href='AST_comments_edit_check2.php'\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Re-evaluate\" onClick=\"window.close()\"> \n";
		echo "</td> \n";
	echo "</tr> \n";
echo "</table> \n";

		} elseif ($passcount > 0) {
			$ps_ave = (($passcount / $count) * 100);
			$fscore = number_format((float)$ps_ave, 2, '.', '');

			echo "<center> \n";
			echo "<b><font color = \"green\" size = \"+2\">Evaluation PASSED!!</b></font> <br> \n";
			echo "The Agent received a <b>FINAL SCORE</b> of <b>".$fscore." Average Points.</b>. \n";
			echo "</center> \n";
		echo "</td> \n";
	echo "</tr> \n";
	echo "<TR BGCOLOR=\"#015B91\"> \n";
		echo "<td align=\"center\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Record Evaluation\" onClick=\"location.href='AST_comments_edit_check2.php'\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Re-evaluate\" onClick=\"window.close()\"> \n";
		echo "</td> \n";
	echo "</tr> \n";
echo "</table> \n";
		}

	} elseif ($scoring == "PC") {
		$case_cid = strtolower($sent_cid);
		$result = mysql_query("SELECT COUNT(status) AS count FROM questions_cid_".$case_cid." WHERE status = 'ACTIVE' ") or die(mysql_error());
			$row = mysql_fetch_array($result);
			$count = $row['count'];
			
		if ($passcount == 0) {
			$fscore == "FAILED";
			echo "<center> \n";
			echo "<b><font color = \"red\" size = \"+2\">Evaluation failed!</b></font> <br> \n";
			echo "The Agent did not pass any criteria. \n";
			echo "</center> \n";
		echo "</td> \n";
	echo "</tr> \n";
	echo "<TR BGCOLOR=\"#015B91\"> \n";
		echo "<td align=\"center\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Record Evaluation\" onClick=\"location.href='AST_comments_edit_check2.php'\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Re-evaluate\" onClick=\"window.close()\"> \n";
		echo "</td> \n";
	echo "</tr> \n";
echo "</table> \n";

		} elseif ($passcount > 0) {
			$ps_ave = (($passcount / $count) * 100);
			$fscore = number_format((float)$ps_ave, 2, '.', '');

			echo "<center> \n";
			echo "<b><font color = \"green\" size = \"+3\">Evaluation PASSED!!</b></font> <br> \n";
			echo "The Agent received a <b>FINAL SCORE</b> of <b>".$fscore."%.</b>. \n";
			echo "</center> \n";
		echo "</td> \n";
	echo "</tr> \n";
	echo "<TR BGCOLOR=\"#015B91\"> \n";
		echo "<td align=\"center\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Record Evaluation\" onClick=\"location.href='AST_comments_edit_check2.php'\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Re-evaluate\" onClick=\"window.close()\"> \n";
		echo "</td> \n";
	echo "</tr> \n";
echo "</table> \n";
		}
	} 
		
	}	
	
$_SESSION['uniqueid'] = $uniqueid;
$_SESSION['agent'] = $agent;
$_SESSION['usergroup'] = $usergroup;
$_SESSION['campid'] = $sent_cid;
$_SESSION['supid'] = $supid;
$_SESSION['comments'] = $comments;
$_SESSION['rate01'] = $rate01;
$_SESSION['rate02'] = $rate02;
$_SESSION['rate03'] = $rate03;
$_SESSION['rate04'] = $rate04;
$_SESSION['rate05'] = $rate05;
$_SESSION['rate06'] = $rate06;
$_SESSION['rate07'] = $rate07;
$_SESSION['rate08'] = $rate08;
$_SESSION['rate09'] = $rate09;
$_SESSION['rate010'] = $rate010;
$_SESSION['fscore'] = $fscore;
$_SESSION['scoring'] = $scoring;
$_SESSION['af1'] = $af1;
$_SESSION['af2'] = $af2;
$_SESSION['af3'] = $af3;
$_SESSION['af4'] = $af4;
$_SESSION['af5'] = $af5;
$_SESSION['af6'] = $af6;
$_SESSION['af7'] = $af7;
$_SESSION['af8'] = $af8;
$_SESSION['af9'] = $af9;
$_SESSION['af10'] = $af10;
				

?>
		
		
