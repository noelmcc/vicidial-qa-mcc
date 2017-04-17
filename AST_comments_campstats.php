<?php
### AST_comments_campstats.php
### 
### Special thanks to Yiannos Katsirintakis <janokary@gmail.com> for the base code.    www.publicissue.gr LICENSE: GPLv2
#### Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#
# edited to GET POST from AST_comments_search.php to process chosen data
# edited 01-05-2017 noel cruz noel@mycallcloud.com

session_start();

header ("Content-type: text/html; charset=utf-8");

require("dbconnect.php");

$recurl = "/var/spool/asterisk/monitorDONE/MP3/";			//change this to the path of the recording files - make sure for file searching, absolute path is used!!!

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
	
	if (empty($_POST['campaign_status'])) {$campaign_status = $_SESSION['campaign_status'];
	} else {$campaign_status = $_POST['campaign_status'];}

	//test outputs
	
	//echo "campaign_status: ".$campaign_status."<p>";
	
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

.row1 {
    padding: 5px 5px;
    width: 1337px;
    color: #000000;
    background-color: #9BB9FB;
    display: inline-block;
}

.row2 {
    padding: 5px 5px;
    width: 1337px;
    color: #000000;
    background-color: #B9CBFD;
    display: inline-block;
}

</style>

</head>
<BODY BGCOLOR="white" marginheight="5" marginwidth="0" leftmargin="0" topmargin="5">
<CENTER>
<TABLE WIDTH="800" BGCOLOR="#D9E6FE" cellpadding="10" cellspacing="0" border="0">
	<TR BGCOLOR="#015B91">
		<TD ALIGN="LEFT">
		
		<?php
		
		if ($campaign_status == "allcampstats") {
			echo "<a href=\"./admin.php\"><FONT COLOR=\"WHITE\"><B>VICIDIAL ADMIN</a>: Campaign Status Selected: (All Status) \n";
		} else {
			echo "<a href=\"./admin.php\"><FONT COLOR=\"WHITE\"><B>VICIDIAL ADMIN</a>: Campaign Status Selected: ".$campaign_status." \n";
		}
		?>
		</TD>
		<TD ALIGN="RIGHT">
			<FONT COLOR="WHITE"><B><?php echo date("l F j, Y G:i:s A") ?></font>
		</TD>
	</TR> 

	<?php

	echo "<TR BGCOLOR=\"#F0F5FE\">\n";
		echo "<TD ALIGN=\"LEFT\" COLSPAN=\"2\">\n";
			echo "<FONT COLOR=\"BLACK\"><B>Current Campaign Status:</B>\n";
		echo "</TD>\n";
	echo "</TR>\n";
	echo "<TR>\n";

	if ($campaign_status == "allcampstats") {
		
		echo "<TR>\n";
			echo "<TD ALIGN=LEFT COLSPAN=2>\n";
			
				echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" border = \"0\">\n";
					echo "<tr>\n";
						echo "<td align=center>\n";
							echo "<b>No.</b>\n";
						echo "</td>\n";
						echo "<td align=\"center\">\n";
							echo "<b>Status</b>\n";
						echo "</td>\n";
						echo "<td align=\"center\">\n";
							echo "<b>Status Name</b>\n";
						echo "</td>\n";
						echo "<td align=\"center\">\n";
							echo "<b>Selectable?</b>\n";
						echo "</td>\n";
						echo "<td align=\"center\">\n";
							echo "<b>No. of Campaigns</b>\n";
						echo "</td>\n";
					echo "</tr>\n";
				
			$cschk = mysql_query("SELECT DISTINCT status, status_name,selectable FROM vicidial_campaign_statuses ORDER BY status");
					
				$u = 1;
				while ($rowchk = mysql_fetch_array($cschk)) {
					$campstatus = $rowchk['status'];
					$campstatusname = $rowchk['status_name'];
					$selectable = $rowchk['selectable'];
					
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
							echo $campstatus." \n";
						echo "</td> \n";	
						
						echo "<td align=\"center\">\n";
							echo $campstatusname." \n";
						echo "</td> \n";	
						
						echo "<td align=\"center\">\n";
						
						if ($selectable == "Y") {
							$select = "YES";
						} else {
							$select = "NO";
						}
						
							echo $select." \n";
						echo "</td> \n";	
						
						echo "<td align=\"center\">\n";
						
						//$chkcampaigncount = mysql_query("SELECT COUNT(DISTINCT campaign_id ) AS campcount FROM vicidial_log WHERE status = '$campstatus' ");
						$chkcampaigncount = mysql_query("SELECT COUNT(DISTINCT campaign_id ) AS campcount FROM vicidial_agent_comments WHERE call_status = '$campstatus' ");
							$rowcampaigncount = mysql_fetch_array($chkcampaigncount);
							$campcount = $rowcampaigncount['campcount'];
						
						if ($campcount == 0)	{
							echo $campcount." \n";
						} else {
							echo "<a href = \"AST_comments_campstats_list.php?search=general&status=".$campstatus."&statusname=".$campstatusname."&count=".$campcount."\">".$campcount."</a> \n";
						}
							
						echo "</td> \n";	
						
					echo "</tr> \n";
					
				$u++;	

				}
				
				
		
	} else {
		
		echo "<TR>\n";
			echo "<TD ALIGN=LEFT COLSPAN=2>\n";
				echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" border = \"0\">\n";
					echo "<tr>\n";
						echo "<td align=center>\n";
							echo "<b>No.</b>\n";
						echo "</td>\n";
						echo "<td align=\"center\">\n";
							echo "<b>Status</b>\n";
						echo "</td>\n";
						echo "<td align=\"center\">\n";
							echo "<b>Status Name</b>\n";
						echo "</td>\n";
						echo "<td align=\"center\">\n";
							echo "<b>Selectable?</b>\n";
						echo "</td>\n";
						echo "<td align=\"center\">\n";
							echo "<b>Campaign ID</b>\n";
						echo "</td>\n";
						echo "<td align=\"center\">\n";
							echo "<b>No. of Calls</b>\n";
						echo "</td>\n";
					echo "</tr>\n";
					
			$cschk = mysql_query("SELECT status, status_name, selectable, campaign_id FROM vicidial_campaign_statuses WHERE status = '$campaign_status' ORDER BY campaign_id ");
					
				$u = 1;
				while ($rowchk = mysql_fetch_array($cschk)) {
					$campstatus = $rowchk['status'];
					$campstatusname = $rowchk['status_name'];
					$selectable = $rowchk['selectable'];
					$campaign_id = $rowchk['campaign_id'];
					
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
							echo $campstatus." \n";
						echo "</td> \n";	
						
						echo "<td align=\"center\">\n";
							echo $campstatusname." \n";
						echo "</td> \n";	
						
						echo "<td align=\"center\">\n";
						
						if ($selectable == "Y") {
							$select = "YES";
						} else {
							$select = "NO";
						}
						
							echo $select." \n";
						echo "</td> \n";	
						
						echo "<td align=\"center\">\n";
							echo $campaign_id." \n";
						echo "</td> \n";	
						
						echo "<td align=\"center\">\n";
						
						$chkcallcount = mysql_query("SELECT COUNT(call_date) as callcount FROM vicidial_agent_comments WHERE campaign_id = '$campaign_id' AND call_status = '$campstatus' ");
							$rowcallcount = mysql_fetch_array($chkcallcount);
							$callcount = $rowcallcount['callcount'];
						
						if ($callcount == 0){
							echo number_format($callcount)." \n";
						} else {
							echo "<a href=\"AST_comments_campstats_list.php?search=specific&status=".$campstatus."&statusname=".$campstatusname."&campaign=".$campaign_id."&count=".$callcount."\">".number_format($callcount)."</a> \n";
						}

						echo "</td> \n";	
						
					echo "</tr> \n";
					
				$u++;	

				}		
		
	}
					
					echo "<tr BGCOLOR=\"#015B91\">\n";
						echo "<td align=\"center\" colspan = \"6\">\n";
								echo "<input type =\"button\" onclick=\"location.href='AST_comments_search_reset.php'\" value=\"Back to Search\" />\n";
						echo "</td>\n";
					echo "</tr>\n";
				echo "</TABLE>\n";
				
			echo "</td>\n";
		echo "</tr>\n";
	echo "</TABLE>\n";
	
	
	
?>


