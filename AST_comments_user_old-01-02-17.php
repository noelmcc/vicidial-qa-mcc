<?php
### AST_comments_user.php
### 
### Special thanks to Yiannos Katsirintakis <janokary@gmail.com> for the base code.    www.publicissue.gr LICENSE: GPLv2
###
# based off AST_comments_list.php
session_start();

header ("Content-type: text/html; charset=utf-8");

require("dbconnect.php");

$recurl = "/var/spool/asterisk/monitorDONE/MP3/";			//change this to the path of the recording files - make sure for file searching, absolute path is used!!!

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];

if (isset($_GET["agent"]))				{$agent=$_GET["agent"];}
	elseif (isset($_POST["agent"]))		{$agent=$_POST["agent"];}

if (isset($_GET["fullname"]))				{$post_fullname=$_GET["fullname"];}
	elseif (isset($_POST["fullname"]))		{$post_fullname=$_POST["fullname"];}

if (isset($_GET["campaigns"]))				{$post_camp=$_GET["campaigns"];}
	elseif (isset($_POST["campaigns"]))		{$post_camp=$_POST["campaigns"];}
	
if (isset($_GET["search"]))				{$post_search=$_GET["search"];}
	elseif (isset($_POST["search"]))		{$post_search=$_POST["search"];}

if (isset($_GET["usergroups"]))				{$post_usergroups=$_GET["usergroups"];}
	elseif (isset($_POST["usergroups"]))		{$post_usergroups=$_POST["usergroups"];}
	
if (isset($_GET["call_date"]))				{$sent_call_date=$_GET["call_date"];}
	elseif (isset($_POST["call_date"]))		{$sent_call_date=$_POST["call_date"];}
	
if (isset($_GET["comment_date"]))				{$sent_comment_date=$_GET["comment_date"];}
	elseif (isset($_POST["comment_date"]))		{$sent_comment_date=$_POST["comment_date"];}
	
if (isset($_GET["specialist"]))				{$post_specialist=$_GET["specialist"];}
	elseif (isset($_POST["specialist"]))		{$post_specialist=$_POST["specialist"];}
	
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

	//start getting POST data from AST_comments_search.php mcc_edits_start_12_12_2016
	$datefrom = $_SESSION['datefrom'];
	$datefrommin = $_SESSION['datefrommin'];
	$defdatefrom = $_SESSION['defdatefrom'];
	$dateto = $_SESSION['dateto'];
	$currdt = $_SESSION['currdt'];
	$datetomin = $_SESSION['datetomin'];
	
	if (empty($post_camp)) {
		$campaigns = $_SESSION['campaigns'];
	} else {
		$campaigns = $post_camp;
	}
	
	$agents = $_SESSION['agents'];
	$usergroups = $_SESSION['usergroups'];
	$specialist = $_SESSION['specialist'];
	
	//test outputs
	
	echo "datefrom: ".$datefrom."<br>";
	echo "datefrommin: ".$datefrommin."<br>";
	echo "defdatefrom: ".$defdatefrom."<br>";
	echo "dateto: ".$dateto."<br>";
	echo "currdt: ".$currdt."<br>";
	echo "datetomin: ".$datetomin."<br>";
	echo "campaigns: ".$campaigns."<br>";
	echo "agent: ".$agent."<br>";
	echo "fullname: ".$post_fullname."<br>";
	echo "agents: ".$agents."<br>";
	echo "usergroups: ".$usergroups."<br>";
	echo "specialist: ".$specialist."<br>";
	echo "post_search: ".$post_search."<br>";
	echo "sent_call_date: ".$sent_call_date."<br>";
	echo "sent_comment_date: ".$sent_comment_date."<br>";
	
	//end getting POST data from AST_comments_search.php mcc_edits_end_12_12_2016
	
	
	
	
	
?>
<html>
<head>
<title>VICIDIAL ADMIN</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">

<style>
body {
	font-family: Arial, Helvetica;
}
</style>

</head>
<BODY BGCOLOR="white" marginheight="5" marginwidth="0" leftmargin="0" topmargin="5">
<CENTER>
<TABLE WIDTH="1024" BGCOLOR="#D9E6FE" cellpadding="10" cellspacing="0">
	<TR BGCOLOR="#015B91">
		<TD ALIGN="LEFT">
			<a href="./admin.php"><FONT FACE="ARIAL,HELVETICA" COLOR="WHITE" SIZE="2"><B>VICIDIAL ADMIN</a>: Review Record of Agent <?php echo $agent; ?>
		</TD>
		<TD ALIGN="RIGHT">
			<FONT FACE="ARIAL,HELVETICA" COLOR="WHITE" SIZE="2"><B><?php echo date("l F j, Y G:i:s A") ?></font>
		</TD>
	</TR> 

	<?php

	
	
	echo "<TR BGCOLOR=\"#F0F5FE\">\n";
		echo "<TD ALIGN=LEFT COLSPAN=2>\n";
			echo "<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2><B>Select A Record to View or Review Below:</B>\n";
		echo "</TD>\n";
	echo "</TR>\n";
	echo "<TR>\n";
		echo "<TD ALIGN=LEFT COLSPAN=2>\n";

			echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" border = \"0\">\n";
				echo "<tr>\n";
					echo "<td align=\"center\" width=\"25\">\n";
						echo "<b>No.</b>\n";
					echo "</td>\n";
					echo "<td align=\"center\" width=\"125\">\n";
						echo "<b>Campaign</b>\n";
					echo "</td>\n";
					echo "<td align=\"center\" width=\"155\">\n";
						echo "<b>Comment Date</b>\n";
					echo "</td>\n";
					echo "<td align=\"center\" width=\"155\">\n";
						echo "<b>Call Date</b>\n";
					echo "</td>\n";
					echo "<td align=\"center\">\n";
						echo "<b>Latest Comment / Feedback</b>\n";
					echo "</td>\n";
					echo "<td align=\"center\" width=\"150\">\n";
						echo "<b>Action</b>\n";
					echo "</td>\n";
				echo "</tr>\n";
				
			if (empty($sent_call_date)) {
				if ($post_search== "all") {			//post by all general items	
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns'", $link) or die(mysql_error());
				} elseif ($post_search == "agent") {			//post by agent				
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' ", $link) or die(mysql_error());
				} elseif ($post_search == "ugroup") {		//post by user_groups
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND user_group = '$post_usergroups' ", $link) or die(mysql_error());
				} elseif ($post_search == "campaign") {		//post by campaign 
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND campaign_id = '$campaigns' ", $link) or die(mysql_error());
				} elseif ($post_search == "specialist") {		//post by specialist
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				} elseif ($post_search == "daterange") {		//post by daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' ", $link) or die(mysql_error());
				} elseif ($post_search == "daterange_campaign") {		//post by daterange and campaign
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND campaign_id = '$campaigns' ", $link) or die(mysql_error());
				} elseif ($post_search == "agent_daterange_campaign") {		//post by agent and daterange and campaign
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND campaign_id = '$campaigns' ", $link) or die(mysql_error());
				} elseif ($post_search == "agent_usergroups_daterange") {		//post by agent and usergroup and daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND user_group = '$usergroups' ", $link) or die(mysql_error());
				} elseif ($post_search == "agent_specialist_daterange") {		//post by agent and specialist and daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				} elseif ($post_search == "campaign_usergroups_daterange") {		//post by campaign and usergroups and daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' ", $link) or die(mysql_error());
				} elseif ($post_search == "campaign_specialist_daterange") {		//post by campaign and specialist and daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				} elseif ($post_search == "usergroups_specialist_daterange") {		//post by usergroups and specialist and daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE supervisor_id = '$specialist' AND user_group = '$usergroups' ", $link) or die(mysql_error());
				} elseif ($post_search == "agent_campaign_usergroups_daterange") {		//post by agent and campaign and usergroup and daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND campaign_id = '$campaigns' AND user_group = '$usergroups' ", $link) or die(mysql_error());
				} elseif ($post_search == "agent_campaign_specialist_daterange") {		//post by agent and campaign and specialist and daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND campaign_id = '$campaigns' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				} elseif ($post_search == "agent_usergroups_specialist_daterange") {		//post by agent and campaign and specialist and daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND user_group = '$usergroups' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				} elseif ($post_search == "campaign_usergroups_specialist_daterange") {		//post by campaign and usergroups and daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				
				//added new search options	 start 12/29
					
				//search by datefrom and dateto and campaign and agent
				} elseif ($post_search == "df_dt_ca_ag") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND campaign_id = '$campaigns' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and and campaign and agent and usergroups
				} elseif ($post_search == "df_dt_ca_ag_us") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND campaign_id = '$campaigns' AND user_group = '$usergroups' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and and campaign and agent and specialist
				} elseif ($post_search == "df_dt_ca_ag_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agents' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and and campaign and specialist
				} elseif ($post_search == "df_dt_ca_sp") {
				$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and and usegroups
				} elseif ($post_search == "df_dt_ca_us") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and and usegroups and specialist
				} elseif ($post_search == "df_dt_ca_us_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and agent
				} elseif ($post_search == "df_dt_ag") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and agent and usergroups
				} elseif ($post_search == "df_dt_ag_us") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND user_group = '$usergroups' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and agent and usergroups and specialist
				} elseif ($post_search == "df_dt_ag_us_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND user_group = '$usergroups' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and agent and usergroups and specialist
				} elseif ($post_search == "df_dt_ag_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and usergroups
				} elseif ($post_search == "df_dt_us") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and agentname
				} elseif ($post_search == "df_dt_agn") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE comment_date = '$sent_comment_date' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and usergroups and specialist
				} elseif ($post_search == "df_dt_us_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and specialist
				} elseif ($post_search == "df_dt_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE supervisor_id = '$specialist' ", $link) or die(mysql_error());
				//search by datefrom and all defaults
				} elseif ($post_search == "df_all") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE comment_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom and campaign
				} elseif ($post_search == "df_ca") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' ", $link) or die(mysql_error());
				//search by datefrom and campaign and agent
				} elseif ($post_search == "df_ca_ag") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND campaign_id = '$campaigns' ", $link) or die(mysql_error());
				//search by datefrom and campaign and agent and usergroups
				} elseif ($post_search == "df_ca_ag_us") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND campaign_id = '$campaigns' AND user_group = '$usergroups' ", $link) or die(mysql_error());
				//search by datefrom and campaign and agent and usergroups
				} elseif ($post_search == "df_ca_ag_us_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND campaign_id = '$campaigns' AND user_group = '$usergroups' AND supervisor_id = $specialist' ", $link) or die(mysql_error());
				//search by datefrom and campaign and agent and specialist
				} elseif ($post_search == "df_ca_ag_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agents' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				//search by datefrom and campaign and specialist
				} elseif ($post_search == "df_ca_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				//search by datefrom and usegroups
				} elseif ($post_search == "df_ca_us") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' ", $link) or die(mysql_error());
				//search by datefrom and usegroups and specialist
				} elseif ($post_search == "df_ca_us_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				//search by datefrom and agent
				} elseif ($post_search == "df_ag") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' ", $link) or die(mysql_error());
				//search by datefrom and agent and usergroups
				} elseif ($post_search == "df_ag_us") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND user_group = '$usergroups' ", $link) or die(mysql_error());
				//search by datefrom and agent and usergroups and specialist
				} elseif ($post_search == "df_ag_us_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND user_group = '$usergroups' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				//search by datefrom and agent and usergroups and specialist
				} elseif ($post_search == "df_ag_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				//search by datefrom and usergroups
				} elseif ($post_search == "df_us") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' ", $link) or die(mysql_error());
			//search by datefrom and usergroups and specialist
				} elseif ($post_search == "df_us_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				//search by datefrom and specialist
				} elseif ($post_search == "df_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE supervisor_id = '$specialist' ", $link) or die(mysql_error());
				
				//added new search options end 12/29
					
				} elseif ($post_search == "filled") {		//post by all fields filled with choice
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND campaign_id = '$campaigns' AND supervisor_id = '$specialist' AND user_group = '$usergroups' ", $link) or die(mysql_error());
				}	
			} else {
				if ($post_search== "all") {			//post by all general items	
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns'", $link) or die(mysql_error());
				} elseif ($post_search == "agent") {			//post by agent				
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' ", $link) or die(mysql_error());
				} elseif ($post_search == "ugroup") {		//post by user_groups
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND user_group = '$post_usergroups' ", $link) or die(mysql_error());
				} elseif ($post_search == "campaign") {		//post by campaign 
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND campaign_id = '$campaigns' ", $link) or die(mysql_error());
				} elseif ($post_search == "specialist") {		//post by specialist
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND supervisor_id = '$specialist' ", $link) or die(mysql_error());
				} elseif ($post_search == "daterange") {		//post by daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				} elseif ($post_search == "daterange_campaign") {		//post by daterange and campaign
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND campaign_id = '$campaigns' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				} elseif ($post_search == "agent_daterange_campaign") {		//post by agent and daterange and campaign
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND campaign_id = '$campaigns' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				} elseif ($post_search == "agent_usergroups_daterange") {		//post by agent and usergroup and daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				} elseif ($post_search == "agent_specialist_daterange") {		//post by agent and specialist and daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				} elseif ($post_search == "campaign_usergroups_daterange") {		//post by campaign and usergroups and daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				} elseif ($post_search == "campaign_specialist_daterange") {		//post by campaign and specialist and daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				} elseif ($post_search == "usergroups_specialist_daterange") {		//post by usergroups and specialist and daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE supervisor_id = '$specialist' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				} elseif ($post_search == "agent_campaign_usergroups_daterange") {		//post by agent and campaign and usergroup and daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND campaign_id = '$campaigns' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				} elseif ($post_search == "agent_campaign_specialist_daterange") {		//post by agent and campaign and specialist and daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND campaign_id = '$campaigns' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				} elseif ($post_search == "agent_usergroups_specialist_daterange") {		//post by agent and campaign and specialist and daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				} elseif ($post_search == "campaign_usergroups_specialist_daterange") {		//post by campaign and usergroups and daterange
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				
				//added new search options	 start 12/29
					
				//search by datefrom and dateto and campaign and agent
				} elseif ($post_search == "df_dt_ca_ag") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND campaign_id = '$campaigns' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and and campaign and agent and usergroups
				} elseif ($post_search == "df_dt_ca_ag_us") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND campaign_id = '$campaigns' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and and campaign and agent and specialist
				} elseif ($post_search == "df_dt_ca_ag_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agents' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and and campaign and specialist
				} elseif ($post_search == "df_dt_ca_sp") {
				$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and and usegroups
				} elseif ($post_search == "df_dt_ca_us") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and and usegroups and specialist
				} elseif ($post_search == "df_dt_ca_us_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and agent
				} elseif ($post_search == "df_dt_ag") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and agent and usergroups
				} elseif ($post_search == "df_dt_ag_us") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and agent and usergroups and specialist
				} elseif ($post_search == "df_dt_ag_us_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and agent and usergroups and specialist
				} elseif ($post_search == "df_dt_ag_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and usergroups
				} elseif ($post_search == "df_dt_us") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and agentname
				} elseif ($post_search == "df_dt_agn") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$post_fullname' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and usergroups and specialist
				} elseif ($post_search == "df_dt_us_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom  and dateto and specialist
				} elseif ($post_search == "df_dt_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom and all defaults
				} elseif ($post_search == "df_all") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom and campaign
				} elseif ($post_search == "df_ca") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom and campaign and agent
				} elseif ($post_search == "df_ca_ag") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND campaign_id = '$campaigns' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom and campaign and agent and usergroups
				} elseif ($post_search == "df_ca_ag_us") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND campaign_id = '$campaigns' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom and campaign and agent and usergroups
				} elseif ($post_search == "df_ca_ag_us_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND campaign_id = '$campaigns' AND user_group = '$usergroups' AND supervisor_id = $specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom and campaign and agent and specialist
				} elseif ($post_search == "df_ca_ag_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agents' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom and campaign and specialist
				} elseif ($post_search == "df_ca_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom and usegroups
				} elseif ($post_search == "df_ca_us") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom and usegroups and specialist
				} elseif ($post_search == "df_ca_us_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom and agent
				} elseif ($post_search == "df_ag") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom and agent and usergroups
				} elseif ($post_search == "df_ag_us") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom and agent and usergroups and specialist
				} elseif ($post_search == "df_ag_us_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom and agent and usergroups and specialist
				} elseif ($post_search == "df_ag_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom and usergroups
				} elseif ($post_search == "df_us") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
			//search by datefrom and usergroups and specialist
				} elseif ($post_search == "df_us_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				//search by datefrom and specialist
				} elseif ($post_search == "df_sp") {
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				
				//added new search options end 12/29
					
				} elseif ($post_search == "filled") {		//post by all fields filled with choice
					$result = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agent' AND campaign_id = '$campaigns' AND supervisor_id = '$specialist' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
				}	
			}
			
			
			
			if ($sent_call_date == "") {
				$u=1;
				while ($row = mysql_fetch_array($result)) {
					$campaign_id = $row['campaign_id'];
					$comment_date = $row['comment_date'];
					$call_date = $row['call_date'];
					$agent = $row['user'];
					$supervisor_id = $row['supervisor_id'];
					$comments = $row['comments'];

					if ($u % 2 == 0) {
						$bgcolor = "#9BB9FB";
					} else {
						$bgcolor = "#B9CBFD";
					}

				echo "<tr bgcolor = \"".$bgcolor."\">\n";
					echo "<td align=\"center\" valign=\"top\">\n";
						echo "<b>".$u."</b>\n";
					echo "</td>";
					echo "<td align=\"center\" valign=\"top\">\n";
						echo $campaign_id."\n";
					echo "</td>\n";
					echo "<td align=\"center\" valign=\"top\">\n";
					
					if ($comment_date == "(NULL)") {
						echo "\n";
					} else {
						echo $comment_date."\n";
					}
						
					echo "</td>\n";
					echo "<td align=\"center\" valign=\"top\">\n";
						echo $call_date."\n";
					echo "</td>\n";
					echo "<td align=\"justify\" valign=\"top\">\n";
					
						$result3 = mysql_query("SELECT * FROM vicidial_agent_comments WHERE comment_date = '$sent_comment_date' ", $link) or die(mysql_error());
							$row3 = mysql_fetch_array($result3);
							$comments = $row3['comments'];
							$uniqueid = $row3['uniqueid'];
							$user_group = $row3['user_group'];

						$chkfile = ("SELECT filename FROM recording_log WHERE vicidial_id = '".$uniqueid."' ");
							$rsltfile = mysql_query($chkfile);
							$rowfile = mysql_fetch_array($rsltfile);
							$callfile = $rowfile['filename'];
							
							$callfname = $recurl."".$callfile."-all.mp3";
						
						if ((file_exists($callfname) && (empty($comments)))) { 
							echo "<i>No Comment/Feedback - Not Evaluated</i>\n";
						} elseif ((file_exists($callfname) && (!empty($comments)))) { 
							echo ucfirst($comments)."\n";
						} else {
							echo "<font color = \"red\"><i>No Audio File Found - Evaluation Not Allowed</i></font>\n";
						}
					echo "</td>\n";
					echo "<td align=\"center\" valign=\"top\">\n";
					
						if ((file_exists($callfname) && (!empty($comments)))) { 
							echo "<A HREF=\"AST_comments_edit_review.php?campaigns=".$campaign_id."&agent=".$agent."&usergroups=".$user_group."&specialist=".$supervisor_id."&uniqueid=".$uniqueid."&comment_date=".$comment_date."&search=".$post_search."\">View Record</A> \n";
						} elseif ((file_exists($callfname) && (empty($comments)))) { 
							echo "<A HREF=\"AST_comments_edit_review.php?campaigns=".$campaign_id."&agent=".$agent."&usergroups=".$user_group."&specialist=".$supervisor_id."&uniqueid=".$uniqueid."&comment_date=".$comment_date."&search=".$post_search."\">Evaluate</A> \n";
						} else {
							echo "<font color = \"red\"><i>Not Allowed</i></font>\n";
						}
					
					echo "</td>\n";
				echo "</tr>\n";
				
				$u++;
				}
				
			} else {
				$u=1;
				while ($row = mysql_fetch_array($result)) {
					$campaign_id = $row['campaign_id'];
					$comment_date = $row['comment_date'];
					$call_date = $row['call_date'];
					$agent = $row['user'];
					$supervisor_id = $row['supervisor_id'];
					$comments = $row['comments'];

					if ($u % 2 == 0) {
						$bgcolor = "#9BB9FB";
					} else {
						$bgcolor = "#B9CBFD";
					}

				echo "<tr bgcolor = \"".$bgcolor."\">\n";
					echo "<td align=\"center\" valign=\"top\">\n";
						echo "<b>".$u."</b>\n";
					echo "</td>";
					echo "<td align=\"center\" valign=\"top\">\n";
						echo $campaign_id."\n";
					echo "</td>\n";
					echo "<td align=\"center\" valign=\"top\">\n";
					
					if ($comment_date == "(NULL)") {
						echo "\n";
					} else {
						echo $comment_date."\n";
					}
						
					echo "</td>\n";
					echo "<td align=\"center\" valign=\"top\">\n";
						echo $call_date."\n";
					echo "</td>\n";
					echo "<td align=\"justify\" valign=\"top\">\n";
					
						$result3 = mysql_query("SELECT * FROM vicidial_agent_comments WHERE call_date = '$call_date' AND user = '$agent' AND call_date BETWEEN '$datefrom' AND '$dateto' ", $link) or die(mysql_error());
							$row3 = mysql_fetch_array($result3);
							$comments = $row3['comments'];
							$uniqueid = $row3['uniqueid'];
							$user_group = $row3['user_group'];
							
						$chkfile = ("SELECT filename FROM recording_log WHERE vicidial_id = '".$uniqueid."' ");
							$rsltfile = mysql_query($chkfile);
							$rowfile = mysql_fetch_array($rsltfile);
							$callfile = $rowfile['filename'];
							
							$callfname = $recurl."".$callfile."-all.mp3";
						
						if ((file_exists($callfname) && (empty($comments)))) { 
							echo "<i>No Comment/Feedback - Not Evaluated</i>\n";
						} elseif ((file_exists($callfname) && (!empty($comments)))) { 
							echo ucfirst($comments)."\n";
						} else {
							echo "<font color = \"red\"><i>No Audio File Found - Evaluation Not Allowed</i></font>\n";
						}
					echo "</td>\n";
					echo "<td align=\"center\" valign=\"top\">\n";
					
						if ((file_exists($callfname) && (!empty($comments)))) { 
							echo "<A HREF=\"AST_comments_edit_review.php?campaigns=".$campaign_id."&agent=".$agent."&usergroups=".$user_group."&specialist=".$supervisor_id."&uniqueid=".$uniqueid."&comment_date=".$comment_date."&search=".$post_search."\">View Record</A> \n";
						} elseif ((file_exists($callfname) && (empty($comments)))) { 
							echo "<A HREF=\"AST_comments_edit_review.php?campaigns=".$campaign_id."&agent=".$agent."&usergroups=".$user_group."&specialist=".$supervisor_id."&uniqueid=".$uniqueid."&comment_date=".$comment_date."&search=".$post_search."\">Evaluate</A> \n";
						} else {
							echo "<font color = \"red\"><i>Not Allowed</i></font>\n";
						}
					
					echo "</td>\n";
				echo "</tr>\n";
				
				$u++;
				}
			}
				
				
				
				echo "<tr bgcolor = \"#015B91\"> \n";
					echo "<td colspan = \"6\" align=\"center\">\n";
						echo "<input type =\"button\" onclick=\"location.href='AST_comments_list.php?search=".$post_search."'\" value=\"Back to User List\" />\n";
						echo "<input type =\"button\" onclick=\"location.href='AST_comments_search_reset.php'\" value=\"Back to Search\" />\n";
					echo "</td>\n";
				echo "</tr>\n";
			echo "</TABLE>\n";

	//start setting SESSION data
	$_SESSION['datefrom'] = $datefrom;
	$_SESSION['datefrommin'] = $datefrommin;
	$_SESSION['defdatefrom'] = $defdatefrom;
	$_SESSION['dateto'] = $dateto;
	$_SESSION['currdt'] = $currdt;
	$_SESSION['datetomin'] = $datetomin;
	$_SESSION['dateto'] = $dateto;
	if ($post_search == "all"){
		$_SESSION['campaigns'] = "allcamps";
	} else {
		$_SESSION['campaigns'] = $campaigns;
	}
	$_SESSION['agentid'] = $agent;
	$_SESSION['agentname'] = $post_fullname;
	$_SESSION['usergroups'] = $usergroups;
	$_SESSION['specialist'] = $specialist;
	$_SESSION['search'] = $post_search;
	//end setting SESSION data
	
	//echo "datefrom: ".$_SESSION['datefrom']."<br>";
	//echo "datefrommin: ".$_SESSION['datefrommin']."<br>";
	//echo "defdatefrom: ".$_SESSION['defdatefrom']."<br>";
	//echo "dateto: ".$_SESSION['dateto']."<br>";
	//echo "currdt: ".$_SESSION['currdt']."<br>";
	//echo "datetomin: ".$_SESSION['datetomin']."<br>";
	//echo "campaigns: ".$_SESSION['campaigns']."<br>";
	//echo "agent: ".$_SESSION['agentid']."<br>";
	//echo "post_fullname: ".$_SESSION['agentname']."<br>";
	//echo "usergroups: ".$_SESSION['usergroups']."<br>";
	//echo "specialist: ".$_SESSION['specialist']."<br>";
	//echo "post_search: ".$_SESSION['search']."<br>";
	
	
?>



