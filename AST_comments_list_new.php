<?php
### Ast_comments_list.php
### 
### Special thanks to Yiannos Katsirintakis <janokary@gmail.com> for the base code.    www.publicissue.gr LICENSE: GPLv2
#### Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#
# Write comments for an agent convertation with a client 
#
#
#
#
#
#

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

	//mcc_edits_start_12_07_2016 - changed access level from >7 to >6 to allow user level 7 (QA) to access scoresheet
	//$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 7 and view_reports='1';";
	$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 6 and view_reports='1';";
	//mcc_edits_end_12_07_2016 - changed access level from >7 to >6 to allow user level 7 (QA) to access scoresheet
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
<html>
<head>
<title>VICIDIAL ADMIN</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
</head>
<BODY BGCOLOR=white marginheight=0 marginwidth=0 leftmargin=0 topmargin=0>
<CENTER>
<TABLE WIDTH=720 BGCOLOR=#D9E6FE cellpadding=2 cellspacing=0>
	<TR BGCOLOR=#015B91>
		<TD ALIGN=LEFT>
			<?php echo "<a href=\"./admin.php\">" ?><FONT FACE="ARIAL,HELVETICA" COLOR=WHITE SIZE=2><B> &nbsp; VICIDIAL ADMIN</a>: List Comments 
		</TD>
		<TD ALIGN=RIGHT>
			<FONT FACE="ARIAL,HELVETICA" COLOR=WHITE SIZE=2><B><?php echo date("l F j, Y G:i:s A") ?> &nbsp; </font>
		</TD>
	</TR> 

	<?php

	echo "<TR BGCOLOR=\"#F0F5FE\">\n";
		echo "<TD ALIGN=LEFT COLSPAN=2>\n";
			echo "<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2><B> &nbsp; </B>\n";
		echo "</TD>\n";
	echo "</TR>\n";
	echo "<TR>\n";
		echo "<TD ALIGN=LEFT COLSPAN=2>\n";

			//changed table width to 800 from 700; changed above table width to 720 from 620 mcc_edits_start_12_05_2016
			//removed unused table titles for list display mcc_edits_start_12_08_2016
			echo "<TABLE width=\"800\" cellspacing=\"0\" cellpadding=\"3\" align=\"center\" border = \"1\">\n";
				echo "<tr>\n";
					echo "<td align=center>\n";
						echo "<font size=2><b>No.</b>\n";
					echo "</td>\n";
					echo "<td align=center>\n";
						echo "<font size=2><b>User</b>\n";
					echo "</td>\n";
					echo "<td align=center>\n";
						echo "<font size=2><b>Supervisor</b>\n";
					echo "</td>\n";
					echo "<td align=center>\n";
						echo "<font size=2><b>Action</b>\n";
					echo "</td>\n";
				echo "</tr>\n";
	
				//commented out the following and removed where condition mcc_edits_start_12_05_2016
				//$stmt=("select 	vicidial_log.uniqueid,vicidial_campaigns.campaign_id,vicidial_campaigns.campaign_name,vicidial_log.user,vicidial_log.call_date,vicidial_log.phone_number,vicidial_agent_comments.supervisor_id,vicidial_agent_comments.comments from vicidial_log inner join vicidial_campaigns on vicidial_log.campaign_id = vicidial_campaigns.campaign_id left outer join vicidial_agent_comments on vicidial_log.uniqueid = vicidial_agent_comments.uniqueid where vicidial_log.status='INCALL'");
				//commented out the following and removed where condition mcc_edits_end_12_05_2016
	
				//changed select operation to distinct to display only unique entries mcc_edits_start_12_08_2016
				//$stmt=("select 	vicidial_log.uniqueid,vicidial_campaigns.campaign_id,vicidial_campaigns.campaign_name,vicidial_log.user,vicidial_log.call_date,vicidial_log.phone_number,vicidial_agent_comments.supervisor_id,vicidial_agent_comments.comments from vicidial_log inner join vicidial_campaigns on vicidial_log.campaign_id = vicidial_campaigns.campaign_id left outer join vicidial_agent_comments on vicidial_log.uniqueid = vicidial_agent_comments.uniqueid");
				//changed select operation to distinct to display only unique entries mcc_edits_end_12_08_2016
	
				$stmt2=("SELECT DISTINCT(uniqueid) AS uniqueid FROM vicidial_agent_comments ORDER BY uniqueid ASC");
					$rslt2=mysql_query($stmt2);
					$logs_to_print = mysql_num_rows($rslt2);

				$u=0;
				while ($logs_to_print > $u) {
					$row2=mysql_fetch_row($rslt2);
					//indicated columns to display mcc_edits_start_12_08_2016
					$supid = $row2['supervisor_id'];
					$uid = $row2['uniqueid'];

					if (eregi("1$|3$|5$|7$|9$", $u))
						{$bgcolor='bgcolor="#B9CBFD"';} 
					else
						{$bgcolor='bgcolor="#9BB9FB"';}

				//removed unused table entries for list display mcc_edits_start_12_08_2016
				echo "<tr".$bgcolor.">\n";
					echo "<td align=center>\n";
						echo "<font size=1>".$u."\n";
					echo "</td>";
					echo "<td align=center>\n";
						echo "<font size=2>".$uid."\n";
					echo "</td>\n";
					echo "<td align=center>\n";
						echo "<font size=2>".$supid."\n";
					echo "</td>\n";
					echo "<td align=center>\n";
						echo "<font size=2> <A HREF=\"AST_comments_edit.php?uniqueid=".$uid."\">EDIT</A> \n";
					echo "</td>\n";
				echo "</tr>\n";
				
				$u++;
						}
			echo "</TABLE>\n";
			echo "<BR><BR>\n";



?>

