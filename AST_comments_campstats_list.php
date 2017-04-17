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
	
	/* echo "sent_search: ".$sent_search."<br>";
	echo "sent_status: ".$sent_status."<br>";
	echo "sent_statusname: ".$sent_statusname."<br>";
	echo "sent_campaign: ".$sent_campaign."<br>";
	echo "sent_count: ".$sent_count."<br>"; */
	
	//end getting POST data from AST_comments_search.php mcc_edits_end_01_05_2017
	
	## redirect if count = 1 or search = specific
	if (($sent_count == 1) || ($sent_search == "specific")) {
		header ("Location: AST_comments_campstats_list2.php?search=".$sent_search."&status=".$sent_status."&statusname=".$sent_statusname."&campaign=".$sent_campaign."&count=".$sent_count."");
		//echo "<a href=\"AST_comments_campstats_list2.php?search=".$sent_search."&status=".$sent_status."&statusname=".$sent_statusname."&campaign=".$sent_campaign."&count=".$sent_count."\">next</a>";
	} else {}
	
		
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
		
		echo "<a href=\"./admin.php\"><FONT COLOR=\"WHITE\"><B>VICIDIAL ADMIN</a>: Campaign Status Selected: ".$sent_status." \n";

		?>
		</TD>
		<TD ALIGN="RIGHT">
			<FONT COLOR="WHITE"><B><?php echo date("l F j, Y G:i:s A") ?></font>
		</TD>
	</TR> 

	<?php

	echo "<TR BGCOLOR=\"#F0F5FE\">\n";
		echo "<TD ALIGN=\"LEFT\" COLSPAN=\"2\">\n";
			echo "<FONT COLOR=\"BLACK\"><B>Campaigns:</B>\n";
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
						echo "<b>Status</b>\n";
					echo "</td>\n";
					echo "<td align=\"center\">\n";
						echo "<b>Status Name</b>\n";
					echo "</td>\n";
					echo "<td align=\"center\">\n";
						echo "<b>Selectable?</b>\n";
					echo "</td>\n";
					echo "<td align=\"center\">\n";
						echo "<b>Campaign</b>\n";
					echo "</td>\n";
					echo "<td align=\"center\">\n";
						echo "<b>No. of Calls</b>\n";
					echo "</td>\n";
				echo "</tr>\n";
		
		$chkcid = mysql_query("SELECT DISTINCT campaign_id FROM vicidial_agent_log WHERE status = '$sent_status'");
					
			$u = 1;
			while ($rowcid = mysql_fetch_array($chkcid)) {
				$list_campaign_id = $rowcid['campaign_id'];
				
					
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
						echo $sent_status." \n";
					echo "</td> \n";	
						
					echo "<td align=\"center\">\n";
						echo $sent_statusname." \n";
					echo "</td> \n";	
						
					echo "<td align=\"center\">\n";
					
					$chksel = mysql_query("SELECT selectable FROM vicidial_campaign_statuses WHERE campaign_id = '$list_campaign_id' ");
						$rowsel = mysql_fetch_array($chksel);
						$selectable = $rowsel['selectable'];
						
					if ($selectable == "Y") {
						$select = "YES";
					} elseif ($selectable == "N") {
						$select = "NO";
					} else {
						$select = "<font color = \"red\"><i><b>N/A</b></i></font>";
					}
						
						echo $select." \n";
					echo "</td> \n";	
					
					echo "<td align=\"center\">\n";
						echo $list_campaign_id." \n";
					echo "</td> \n";	
					
					echo "<td align=\"center\">\n";
					
					$chkcalls = mysql_query("SELECT COUNT(call_date) as callcount FROM vicidial_agent_comments WHERE campaign_id = '$list_campaign_id' ");
						$rowcalls = mysql_fetch_array($chkcalls);
						$callcount = $rowcalls['callcount'];
					
					if ($callcount == 0){
						echo number_format($callcount)." \n";
					} else {
						echo "<a href = \"AST_comments_campstats_list2.php?search=specific&status=".$sent_status."&statusname=".$sent_statusname."&campaign=".$list_campaign_id."&count=".$callcount."\">".number_format($callcount)."</a> \n";

					}
					
						
					echo "</td> \n";	
						
				echo "</tr> \n";
					
			$u++;	

			}
					
				echo "<tr BGCOLOR=\"#015B91\">\n";
					echo "<td align=\"center\" colspan = \"6\">\n";
							echo "<input type =\"button\" onclick=\"location.href='AST_comments_search_reset.php'\" value=\"Back to Search\" />\n";
					echo "</td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
					echo "<td align=\"justify\" colspan = \"6\">\n";
						echo "<font size = \"-1\"><i><b>NOTE:</b> If Selectable? shows N/A, the Campaign is not listed in the Campaign Statuses table.</i></font> \n";
					echo "</td>\n";
				echo "</tr>\n";
			echo "</TABLE>\n";
				
			echo "</td>\n";
		echo "</tr>\n";
	echo "</TABLE>\n";
	
	
	
?>


