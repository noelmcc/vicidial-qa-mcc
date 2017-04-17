<?php
### AST_comments_campstats_list.php
### 
### Special thanks to Yiannos Katsirintakis <janokary@gmail.com> for the base code.    www.publicissue.gr LICENSE: GPLv2
#### Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#
# edited to GET POST from AST_comments_search.php to process chosen data
# created 01-13-2017 noel cruz noel@mycallcloud.com

session_start();

header ("Content-type: text/html; charset=utf-8");

require("dbconnect.php");

include("recurl.php");		//update this with servers connected to this account

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];

$PHP_AUTH_USER = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_USER);
$PHP_AUTH_PW = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_PW);

$STARTtime = date("U");
$TODAY = date("Y-m-d");

if (!isset($begin_date)) {$begin_date = $TODAY;}
if (!isset($end_date)) {$end_date = $TODAY;}

	$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 6 and view_reports='1';";
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
	
	$currdt = date("Y-m-d");

	//start getting POST data from AST_comments_search.php mcc_edits_start_01_05_2017
	
	if (isset($_GET["datefrom"]))				{$sent_datefrom=$_GET["datefrom"];}
	elseif (isset($_POST["datefrom"]))		{$sent_datefrom=$_POST["datefrom"];}
	
	if (isset($_GET["dateto"]))				{$sent_dateto=$_GET["dateto"];}
	elseif (isset($_POST["dateto"]))		{$sent_dateto=$_POST["dateto"];}
	
	if (isset($_GET["search"]))				{$sent_search=$_GET["search"];}
	elseif (isset($_POST["search"]))		{$sent_search=$_POST["search"];}
	
	if (isset($_GET["status"]))				{$sent_status=$_GET["status"];}
	elseif (isset($_POST["status"]))		{$sent_status=$_POST["status"];}
	
	if (isset($_GET["statusname"]))				{$sent_statusname=$_GET["statusname"];}
	elseif (isset($_POST["statusname"]))		{$sent_statusname=$_POST["statusname"];}
	
	if (isset($_GET["campaign"]))				{$sent_campaign=$_GET["campaign"];}
	elseif (isset($_POST["campaign"]))		{$sent_campaign=$_POST["campaign"];}
	
	if (isset($_GET["count"]))				{$sent_count=$_GET["count"];}
	elseif (isset($_POST["count"]))		{$sent_count=$_POST["count"];}

	//test outputs
	
	if (empty($sent_campaign)) {
		$chkcid = mysql_query("SELECT campaign_id FROM vicidial_campaign_statuses WHERE status = '$sent_status' ");
		$rowcid = mysql_fetch_array($chkcid);
		$sent_campaign = $rowcid['campaign_id'];
	} else {}
	
	/* echo "sent_datefrom: ".$sent_datefrom."<br>";
	echo "sent_datefrom (conv): ".str_replace('/', '-', date('Y-m-d', strtotime($sent_datefrom)))."<br>";
	echo "sent_dateto: ".$sent_dateto."<br>";
	echo "sent_dateto (conv): ".str_replace('/', '-', date('Y-m-d', strtotime($sent_dateto)))."<br>";
	echo "sent_search: ".$sent_search."<br>";
	echo "sent_status: ".$sent_status."<br>";
	echo "sent_statusname: ".$sent_statusname."<br>";
	echo "sent_campaign: ".$sent_campaign."<br>";
	echo "sent_count: ".$sent_count."<br>"; */
	
	//end getting POST data from AST_comments_search.php mcc_edits_end_01_05_2017
		
?>
<html>
<head>
<title>VICIDIAL ADMIN: Campaign Status</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">

<style>
body {
	font-family: Arial, Helvetica;
}

</style>

</head>
<BODY BGCOLOR="white" marginheight="5" marginwidth="0" leftmargin="0" topmargin="5">
<CENTER>
<TABLE WIDTH="1366" BGCOLOR="#D9E6FE" cellpadding="10" cellspacing="0" border="0">
	<TR BGCOLOR="#015B91">
		<TD ALIGN="LEFT">
		
		<?php
		
		echo "<a href=\"./admin.php\"><FONT COLOR=\"WHITE\"><B>VICIDIAL ADMIN</a>: Campaign Status Selected: ".$sent_status." - ".$sent_statusname." \n";

		?>
		</TD>
		<TD ALIGN="RIGHT">
			<FONT COLOR="WHITE"><B><?php echo date("l F j, Y G:i:s A") ?></font>
		</TD>
	</TR> 

	<?php

	echo "<TR BGCOLOR=\"#F0F5FE\">\n";
		echo "<TD ALIGN=\"LEFT\" COLSPAN=\"2\">\n";
		
			$chkttlcount = mysql_query("SELECT COUNT(*) AS count FROM vicidial_agent_comments WHERE campaign_id = '$sent_campaign' AND call_status = '$sent_status' ");
				$rowttlcount = mysql_fetch_array($chkttlcount);
				$ttlcall_count = $rowttlcount['count'];
			
			$startdate = str_replace('/', '-', date('Y-m-d', strtotime($sent_datefrom)))." 00:00:00";
			$enddate = str_replace('/', '-', date('Y-m-d', strtotime($sent_dateto)))." 23:59:59";
			
			$chk30count = mysql_query("SELECT COUNT(*) AS 30count FROM vicidial_agent_comments WHERE uniqueid IN (SELECT uniqueid FROM vicidial_agent_log WHERE campaign_id = '$sent_campaign' AND status = '$sent_status' ) AND call_date BETWEEN '$startdate' AND '$enddate' ");
				$row30count = mysql_fetch_array($chk30count);
				$rangecount = $row30count['30count'];
			
			
			echo "<FONT COLOR=\"BLACK\"><B>Total No. of Calls Found: ".number_format($ttlcall_count)." (updated as of this search).</B> <p>\n";
			
			echo "<FONT COLOR=\"BLACK\"><B>Available Calls from ".str_replace('/', '-', date('Y-m-d', strtotime($sent_datefrom)))." to ".str_replace('/', '-', date('Y-m-d', strtotime($sent_dateto)))." found: ".number_format($rangecount)." (30-day Default).</B>\n";
		echo "</TD>\n";
	echo "</TR>\n";
	echo "<TR>\n";

		echo "<TD ALIGN=LEFT COLSPAN=2>\n";
			
			echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" border = \"0\">\n";
				echo "<tr>\n";
					echo "<td align=center>\n";
						echo "<b>No.</b>\n";
					echo "</td>\n";
					echo "<td align=\"center\">\n";
						echo "<b>Call Date</b>\n";
					echo "</td>\n";
					echo "<td align=\"center\">\n";
						echo "<b>Call Length</b>\n";
					echo "</td>\n";
					echo "<td align=\"center\">\n";
						echo "<b>Campaign</b>\n";
					echo "</td>\n";
					echo "<td align=\"center\">\n";
						echo "<b>Agent</b>\n";
					echo "</td>\n";
					echo "<td align=\"center\">\n";
						echo "<b>User Group</b>\n";
					echo "</td>\n";
					echo "<td align=\"center\">\n";
						echo "<b>InGroup</b>\n";
					echo "</td>\n";
					echo "<td align=\"center\">\n";
						echo "<b>Specialist</b>\n";
					echo "</td>\n";
					echo "<td align=\"center\">\n";
						echo "<b>Action</b>\n";
					echo "</td>\n";
				echo "</tr>\n";
		
		$list_datefrom = str_replace('/', '-', date('Y-m-d', strtotime($sent_datefrom)));
		$list_dateto = str_replace('/', '-', date('Y-m-d', strtotime($sent_dateto)));
		
		$chkcalls = mysql_query("SELECT * FROM vicidial_agent_comments WHERE active = 'Y' AND campaign_id = '$sent_campaign' AND uniqueid IN (SELECT uniqueid FROM vicidial_agent_log WHERE campaign_id = '$sent_campaign' AND status = '$sent_status' ) AND call_date BETWEEN '$startdate' AND '$enddate' ");
					
			$u = 1;
			while ($rowcalls = mysql_fetch_array($chkcalls)) {
				$list_call_date = $rowcalls['call_date'];
				$list_comment_date = $rowcalls['comment_date'];
				$list_campaign_id = $rowcalls['campaign_id'];
				$list_lead_id = $rowcalls['lead_id'];
				$list_campaign_name = $rowcalls['campaign_name'];
				$list_user = $rowcalls['user'];
				$list_uniqueid = $rowcalls['uniqueid'];
				$list_user_fullname = $rowcalls['full_name'];
				$list_user_group = $rowcalls['user_group'];
				$list_ingroup = $rowcalls['ingroup'];
				$list_supervisor_id = $rowcalls['supervisor_id'];
				$list_supervisor_name = $rowcalls['supervisor_name'];
				$list_comments = $rowcalls['comments'];
					
				if ($u % 2 == 0) {
					$bgcolor = "#9BB9FB";
				} else {
					$bgcolor = "#B9CBFD";
				}

				echo "<tr bgcolor = \"".$bgcolor."\">\n";
						
					echo "<td align=\"center\">\n";
						echo "<b>".$u."</b> \n";
					echo "</td> \n";	
						
					echo "<td align=\"center\">\n";
						echo $list_call_date." \n";
					echo "</td> \n";	
						
					echo "<td align=\"center\">\n";
					
					$chkcl = mysql_query("SELECT length_in_sec FROM recording_log WHERE lead_id = '$list_lead_id' AND user = '$list_user' ");
						$rowcl = mysql_fetch_array($chkcl);
						$found = $rowcl['length_in_sec'];
						$call_length = gmdate("H:i:s", $found);
						
						echo $call_length." \n";
					echo "</td> \n";	
					
					echo "<td align=\"center\">\n";
						echo $list_campaign_id." \n";
					echo "</td> \n";	
					
					echo "<td align=\"center\">\n";
						echo $list_user." - ".$list_user_fullname." \n";
					echo "</td> \n";	
					
					echo "<td align=\"center\">\n";
						echo $list_user_group." \n";
					echo "</td> \n";	
					
					echo "<td align=\"center\">\n";
						echo $list_ingroup." \n";			//use ingroup
					echo "</td> \n";	
					
					echo "<td align=\"center\">\n";
						echo $list_supervisor_id." - ".$list_supervisor_name." \n";
					echo "</td> \n";	
					
					echo "<td align=\"center\">\n";
					
					$chkfile = mysql_query("SELECT filename,location FROM recording_log where lead_id = '$list_lead_id' AND user = '$list_user'  ");
						$rowfile = mysql_fetch_array($chkfile);
						$list_filename = $rowfile['filename'];
						$list_location = $rowfile['location'];
						
						if (!empty($list_comments)) { 
								echo " <A HREF=\"AST_comments_edit_review.php?campaigns=".$list_campaign_id."&lead_id=".$list_lead_id."&agent=".$list_user."&agentname=".$list_user_fullname."&usergroups=".$list_user_group."&specialist=".$list_supervisor_id."&uniqueid=".$list_uniqueid."&call_date=".$list_call_date."&comment_date=".$list_comment_date."&search=".$post_search."\">Review Record</A> \n";
						echo "</td>\n";
					echo "</tr>\n";
						} elseif (empty($list_comments)) { 
								echo " <A HREF=\"AST_comments_edit_review.php?campaigns=".$list_campaign_id."&lead_id=".$list_lead_id."&agent=".$list_user."&agentname=".$list_user_fullname."&usergroups=".$list_user_group."&specialist=".$list_supervisor_id."&uniqueid=".$list_uniqueid."&call_date=".$list_call_date."&comment_date=".$list_comment_date."&search=".$post_search."\">Evaluate</A> \n";
						echo "</td>\n";
					echo "</tr>\n";
						} else {
								echo "Audio File Not Found \n";
						echo "</td>\n";
					echo "</tr>\n";
						}
						
					echo "</td> \n";	
						
				echo "</tr> \n";
					
			$u++;	

			}
					
				echo "<tr BGCOLOR=\"#015B91\">\n";
					echo "<td align=\"center\" colspan = \"9\">\n";
							echo "<input type =\"button\" onclick=\"location.href='AST_comments_search_reset.php'\" value=\"Back to Search\" />\n";
					echo "</td>\n";
				echo "</tr>\n";
			echo "</TABLE>\n";
				
			echo "</td>\n";
		echo "</tr>\n";
	echo "</TABLE>\n";
	
$_SESSION['datefrom'] = $startdate;
$_SESSION['dateto'] = $enddate;
	
?>



