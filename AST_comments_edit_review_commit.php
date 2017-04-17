<?php

# Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#

session_start();

header ("Content-type: text/html; charset=utf-8");

require("dbconnect.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
	
$comment_date = $_SESSION['comment_date']; 
$call_date = $_SESSION['call_date']; 
$uniqueid = $_SESSION['uniqueid']; 
$sent_user = $_SESSION['sent_user']; 
$user_group = $_SESSION['user_group']; 
$campaign_id = $_SESSION['campaign_id']; 
$supervisor_id = $_SESSION['supervisor_id']; 
$rate1 = $_SESSION['rate1']; 
$rate2 = $_SESSION['rate2']; 
$rate3 = $_SESSION['rate3']; 
$rate4 = $_SESSION['rate4']; 
$rate5 = $_SESSION['rate5']; 
$rate6 = $_SESSION['rate6']; 
$rate7 = $_SESSION['rate7']; 
$rate8 = $_SESSION['rate8']; 
$rate9 = $_SESSION['rate9']; 
$rate10 = $_SESSION['rate10']; 
$sent_comments = $_SESSION['sent_comments']; 
$admin_note = $_SESSION['admin_note']; 
$post_fscore = $_SESSION['post_fscore']; 
$lead_id = $_SESSION['lead_id']; 
$call_status = $_SESSION['call_status']; 
$ingroup = $_SESSION['ingroup']; 
$post_search = $_SESSION['post_search']; 
	
//test submissions

/* echo "comment_date: ".$comment_date."<br>";
echo "call_date: ".$call_date."<br>";
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
echo "sent_comments: ".$sent_comments."<br>";
echo "admin_note: ".$admin_note."<br>";
echo "post_fscore: ".$post_fscore."<br>";
echo "lead_id: ".$lead_id."<br>";
echo "call_status: ".$call_status."<br>";
echo "ingroup: ".$ingroup."<br>";
echo "post_search: ".$post_search."<br>"; */


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
<title>VICIDIAL ADMIN: Save Evaluation</title>
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
			<FONT FACE="ARIAL,HELVETICA" COLOR=WHITE SIZE=2><B>VICIDIAL ADMIN</a>: Save Evaluation</font>
		</TD>
	</TR> 
	<TR BGCOLOR="#F0F5FE">
		<td align = "CENTER"> 
		
			<?php
		
			if (empty($admin_note)) {
				
				echo "You cannot submit an edit without any notes.<br> \n";
				echo "Please cancel and enter your notes. \n";

		echo "</td> \n";
	echo "</tr> \n";
	
	echo "<tr bgcolor = \"#015B91\"> \n";
		echo "<td align = \"center\" valign=\"top\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Click to Cancel or Re-evaluate\" onClick=\"window.close()\"> \n";
		echo "</td> \n";
	echo "</tr> \n";
	
echo "</table> \n";

			} else {
				
				$currdate = date("Y-m-d h:s:i");
				$new_comments = $sent_comments." ***notated by ".$PHP_AUTH_USER." on ".$currdate." *** \n";

			$chkcmnt = mysql_query("SELECT comments,full_name, active, campaign_name, supervisor_name FROM vicidial_agent_comments WHERE uniqueid = '$uniqueid' ");
				$rowcmnt = mysql_fetch_array($chkcmnt);
				$incmnt = $rowcmnt['comments'];
				$infullname = $rowcmnt['full_name'];
				$instatus = $rowcmnt['active'];
				$incampname = $rowcmnt['campaign_name'];
				$insupname = $rowcmnt['supervisor_name'];
				
			$chksupname = mysql_query("SELECT full_name FROM vicidial_users WHERE user = '$PHP_AUTH_USER' ");
				$rowsupname = mysql_fetch_array($chksupname);
				$supname = $rowsupname['full_name'];
					
			if (empty($incmnt)) {
				
				//echo "update with name:".$incmnt."<br>";
				
				mysql_query("INSERT INTO vicidial_admin_notes (notes_log_id, comment_date, uniqueid, note, created_by, create_date) VALUES('', '$currdate', '$uniqueid', '$admin_note', '$PHP_AUTH_USER', '$currdate') ") or die(mysql_error());
				
				mysql_query("UPDATE vicidial_agent_comments SET comment_date='$currdate' WHERE uniqueid='$uniqueid'");
				mysql_query("UPDATE vicidial_agent_comments SET supervisor_id='$PHP_AUTH_USER' WHERE uniqueid='$uniqueid'");
				mysql_query("UPDATE vicidial_agent_comments SET supervisor_name='$supname' WHERE uniqueid='$uniqueid'");
				mysql_query("UPDATE vicidial_agent_comments SET comments='$new_comments' WHERE uniqueid='$uniqueid'");
				mysql_query("UPDATE vicidial_agent_comments SET rate01='$rate1' WHERE uniqueid='$uniqueid'");
				mysql_query("UPDATE vicidial_agent_comments SET rate02='$rate2' WHERE uniqueid='$uniqueid'");
				mysql_query("UPDATE vicidial_agent_comments SET rate03='$rate3' WHERE uniqueid='$uniqueid'");
				mysql_query("UPDATE vicidial_agent_comments SET rate04='$rate4' WHERE uniqueid='$uniqueid'");
				mysql_query("UPDATE vicidial_agent_comments SET rate05='$rate5' WHERE uniqueid='$uniqueid'");
				mysql_query("UPDATE vicidial_agent_comments SET rate06='$rate6' WHERE uniqueid='$uniqueid'");
				mysql_query("UPDATE vicidial_agent_comments SET rate07='$rate7' WHERE uniqueid='$uniqueid'");
				mysql_query("UPDATE vicidial_agent_comments SET rate08='$rate8' WHERE uniqueid='$uniqueid'");
				mysql_query("UPDATE vicidial_agent_comments SET rate09='$rate9' WHERE uniqueid='$uniqueid'");
				mysql_query("UPDATE vicidial_agent_comments SET rate010='$rate10' WHERE uniqueid='$uniqueid'");
				mysql_query("UPDATE vicidial_agent_comments SET fscore='$post_fscore' WHERE uniqueid='$uniqueid'");
				mysql_query("UPDATE vicidial_agent_comments SET lead_id='$lead_id' WHERE uniqueid='$uniqueid'");
				mysql_query("UPDATE vicidial_agent_comments SET call_status='$call_status' WHERE uniqueid='$uniqueid'");
				mysql_query("UPDATE vicidial_agent_comments SET ingroup='$ingroup' WHERE uniqueid='$uniqueid'");
				mysql_query("UPDATE vicidial_agent_comments SET created_by='$PHP_AUTH_USER' WHERE uniqueid='$uniqueid'");
			} else {
				
				//echo "insert with name:".$incmnt."<br>";
				
				mysql_query("INSERT INTO vicidial_admin_notes (
					notes_log_id, 
					comment_date, 
					uniqueid, 
					note, 
					created_by, 
					create_date) 
				VALUES(
					'', 
					'$comment_date', 
					'$uniqueid', 
					'$admin_note', 
					'$PHP_AUTH_USER', 
					'$currdate') 
				") or die(mysql_error());
				
				mysql_query("INSERT INTO vicidial_agent_comments (
					comments_log_id, 
					comment_date, 
					call_date, 
					uniqueid, 
					lead_id, 
					user, 
					full_name, 
					active, 
					user_group, 
					ingroup, 
					call_status,
					campaign_id, 
					campaign_name, 
					supervisor_id, 
					supervisor_name, 
					comments, 
					rate01, 
					rate02, 
					rate03, 
					rate04, 
					rate05, 
					rate06, 
					rate07, 
					rate08, 
					rate09, 
					rate010, 
					fscore, 
					created_by, 
					edited_by ) 
				VALUES(
					'', 
					'$currdate', 
					'$call_date', 
					'$uniqueid', 
					'$lead_id', 
					'$sent_user', 
					'$infullname', 
					'$instatus', 
					'$user_group', 
					'$ingroup', 
					'$call_status', 
					'$campaign_id', 
					'$incampname', 
					'$supervisor_id', 
					'$insupname', 
					'$new_comments', 
					'$rate1', 
					'$rate2', 
					'$rate3', 
					'$rate4', 
					'$rate5', 
					'$rate6', 
					'$rate7', 
					'$rate8', 
					'$rate9', 
					'$rate10', 
					'$post_fscore', 
					'$PHP_AUTH_USER', 
					'$PHP_AUTH_USER') 
				") or die(mysql_error());  
			}
				
				echo "Evaluation successfully saved! \n";

		echo "</td> \n";
	echo "</tr> \n";
	
	echo "<tr bgcolor = \"#015B91\"> \n";
		echo "<td align = \"center\" valign=\"top\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Click to Close\" onClick=\"window.opener.location.href='AST_comments_user.php?agent=".$sent_user."&fullname=".$infullname."&campaigns=".$campaign_id."&usergroups=".$user_group."&call_date=".$call_date."&search=".$post_search."';window.close();\"> \n";
		echo "</td> \n";
	echo "</tr> \n";
	
echo "</table> \n";
				
			}
			
			
			
			

			?>
		
		
