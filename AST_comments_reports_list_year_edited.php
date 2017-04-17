<?php
### AST_comments_reports_list_year.php
### 
### Special thanks to Yiannos Katsirintakis <janokary@gmail.com> for the base code.    www.publicissue.gr LICENSE: GPLv2
#### Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#
# 
# edited 01-09-2017 noel cruz noel@mycallcloud.com

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

	//start getting POST data from AST_comments_reports_pick.php
	
	if (empty($_POST['post_search'])) {$post_search = $_SESSION['post_search'];
	} else {$post_search = $_POST['post_search'];}
	
	if (empty($_POST['year'])) {$year = $_SESSION['year'];
	} else {$year = $_POST['year'];}

	if (empty($_POST['campaigns'])) {$campaigns = $_SESSION['campaigns'];
	} else {$campaigns = $_POST['campaigns'];}

	if (empty($_POST['agentid'])) {$agentid = $_SESSION['agentid'];
	} else {$agentid = $_POST['agentid'];}

	if (empty($_POST['agentname'])) {$agentname = $_SESSION['agentname'];
	} else {$agentname = $_POST['agentname'];}
	
	if (empty($_POST['usergroups'])) {$usergroups = $_SESSION['usergroups'];
	} else {$usergroups = $_POST['usergroups'];}

	if (empty($_POST['specialist'])) {$specialist = $_SESSION['specialist'];
	} else {$specialist = $_POST['specialist'];}
	
	if (isset($_GET["campaign_choice"]))				{$campaign_selected=$_GET["campaign_choice"];}
	elseif (isset($_POST["campaign_choice"]))		{$campaign_selected=$_POST["campaign_choice"];}
	
	//compute for years
	
	$currdt = date("Y");
	//$currdt = "2016";
	$mindt1 = date("Y", strtotime('-1 year'));
	$mindt2 = date("Y", strtotime('-2 years'));
	$curryearstart = $currdt."-01-01 00:00:00 ";
	$curryearend = $currdt."-12-31 23:59:59 ";
	$year1start = $mindt1."-01-01 00:00:00 ";
	$year1end = $mindt1."-12-31 23:59:59 ";
	$year2start = $mindt2."-01-01 00:00:00 ";
	$year2end = $mindt2."-12-31 23:59:59 ";
	
	if ($year == "currentyear") {
		$datefrom = $curryearstart;
		$dateto = $curryearend;
	} elseif ($year == "1year") {
		$datefrom = $year1start;
		$dateto = $year1end;
		$currdt = $mindt1;
	} elseif ($year == "2year") {
		$datefrom = $year2start;
		$dateto = $year2end;
		$currdt = $mindt2;
	}

	//test outputs
	
	/* echo "<font size = \"+1\">post_search: ".$post_search."</font><br>";
	echo "<font size = \"+1\">datefrom: ".$datefrom."</font><br>";
	echo "<font size = \"+1\">dateto: ".$dateto."</font><br>";
	echo "<font size = \"+1\">campaigns: ".$campaigns."</font><br>";
	echo "<font size = \"+1\">agentid: ".$agentid."</font><br>";
	echo "<font size = \"+1\">agentname: ".$agentname."</font><br>";
	echo "<font size = \"+1\">usergroups: ".$usergroups."</font><br>";
	echo "<font size = \"+1\">specialist: ".$specialist."</font><br>";
	echo "<font size = \"+1\">currdt: ".$currdt."</font><br>";
	echo "<font size = \"+1\">campaign_selected: ".$campaign_selected."</font><br>"; */
	
	//end getting POST data from AST_comments_reports_pick.php
	
	//start start getting db details based on post
	
	/* search combination patterns */
	
	if ($post_search == "all") {
		$chkps = mysql_query("SELECT COUNT(DISTINCT campaign_id) as cidcount FROM vicidial_agent_comments WHERE call_date BETWEEN '$datefrom' AND '$dateto' ");
			$rowps = mysql_fetch_array($chkps);
			$panelcount = $rowps['cidcount'];
			//echo "<font size = \"+1\">panelcount: ".$panelcount."";
	} elseif ($post_search == "ca") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "ca_ag") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agentid' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "ca_ag_an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agentid' AND full_name = '$agentname' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "ca_ag_an_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agentid' AND full_name = '$agentname' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "ca_ag_an_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agentid' AND full_name = '$agentname' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "ag") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "ag_an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND full_name = '$agentname' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "ag_an_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND full_name = '$agentname' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "ag_an_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND full_name = '$agentname' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "ag_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "ag_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND supervisor_id = '$specialist' AND '$dateto' ");
	} elseif ($post_search == "ag_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND call_date BETWEEN '$datefrom' AND supervisor_id = '$specialist' AND '$dateto' ");
	} elseif ($post_search == "ag_an_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND full_name = '$agentname' AND call_date BETWEEN '$datefrom' AND supervisor_id = '$specialist' AND '$dateto' ");
	} elseif ($post_search == "an_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "an_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "an_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "ca_an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND full_name = '$agentname' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "ca_an_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND full_name = '$agentname' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "ca_an_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND full_name = '$agentname' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "ca_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "ca_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "ca_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "ca_an_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND full_name = '$agentname' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
}
	
	
	
		
?>
<html>
<head>
<title>VICIDIAL ADMIN: QA Reports - Yearly</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">

<style>
body {
	font-family: Arial, Helvetica;
	font-size: 8px;
}

</style>
<style type="text/css" media="print">
	.NonPrintable
	{
	display: none;
	}
</style>
<style type="text/css">
	table.tblText tr td
	{
		font-size: 12px;
	}
</style>

<link rel="stylesheet" href="styles/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- google charts start -->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	google.charts.load('current', {'packages':['bar']});
	google.charts.setOnLoadCallback(drawChart);
	
<?php

		echo "function drawChart() { \n";
			echo "var data = new google.visualization.arrayToDataTable([ \n";
				echo "['Month', 'Evals', 'Passes', 'Fails'], \n";
		
		/* selecting data for bar graph count display */
		
		$chkagent1 = mysql_query("select count(distinct user) as agcount1 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-01%' ");
			$rowagent1 = mysql_fetch_array($chkagent1);
			$agcount1 = $rowagent1['agcount1'];
		$chkevals1 = mysql_query("select count(fscore) as evcount1 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-01%' and fscore != '' ");
			$rowevals1 = mysql_fetch_array($chkevals1);
			$evcount1 = $rowevals1['evcount1'];
		$chkpass1 = mysql_query("select count(fscore) as pscount1 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-01%' and fscore = 'PASS' ");
			$rowpass1 = mysql_fetch_array($chkpass1);
			$pscount1 = $rowpass1['pscount1'];
		$chkfail1 = mysql_query("select count(fscore) as flcount1 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-01%' and fscore = 'FAIL' ");
			$rowfail1 = mysql_fetch_array($chkfail1);
			$flcount1 = $rowfail1['flcount1'];
		  
          echo "['January', ".$evcount1.", ".$pscount1.", ".$flcount1."], \n";
		  
		  $chkagent2 = mysql_query("select count(distinct user) as agcount2 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-02%' ");
			$rowagent2 = mysql_fetch_array($chkagent2);
			$agcount2 = $rowagent2['agcount2'];
		$chkevals2 = mysql_query("select count(fscore) as evcount2 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-02%' and fscore != '' ");
			$rowevals2 = mysql_fetch_array($chkevals2);
			$evcount2 = $rowevals2['evcount2'];
		$chkpass2 = mysql_query("select count(fscore) as pscount2 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-02%' and fscore = 'PASS' ");
			$rowpass2 = mysql_fetch_array($chkpass2);
			$pscount2 = $rowpass2['pscount2'];
		$chkfail2 = mysql_query("select count(fscore) as flcount2 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-02%' and fscore = 'FAIL' ");
			$rowfail2 = mysql_fetch_array($chkfail2);
			$flcount2 = $rowfail2['flcount2'];
		  
          echo "['February', ".$evcount2.", ".$pscount2.", ".$flcount2."], \n";
          
		  $chkagent3 = mysql_query("select count(distinct user) as agcount3 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-03%' ");
			$rowagent3 = mysql_fetch_array($chkagent3);
			$agcount3 = $rowagent3['agcount3'];
		$chkevals3 = mysql_query("select count(fscore) as evcount3 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-03%' and fscore != '' ");
			$rowevals3 = mysql_fetch_array($chkevals3);
			$evcount3 = $rowevals3['evcount3'];
		$chkpass3 = mysql_query("select count(fscore) as pscount3 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-03%' and fscore = 'PASS' ");
			$rowpass3 = mysql_fetch_array($chkpass3);
			$pscount3 = $rowpass3['pscount3'];
		$chkfail3 = mysql_query("select count(fscore) as flcount3 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-03%' and fscore = 'FAIL' ");
			$rowfail3 = mysql_fetch_array($chkfail3);
			$flcount3 = $rowfail3['flcount3'];
		  
          echo "['March', ".$evcount3.", ".$pscount3.", ".$flcount3."], \n";
          
		  $chkagent4 = mysql_query("select count(distinct user) as agcount4 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-04%' ");
			$rowagent4 = mysql_fetch_array($chkagent4);
			$agcount4 = $rowagent4['agcount4'];
		$chkevals4 = mysql_query("select count(fscore) as evcount4 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-04%' and fscore != '' ");
			$rowevals4 = mysql_fetch_array($chkevals4);
			$evcount4 = $rowevals4['evcount4'];
		$chkpass4 = mysql_query("select count(fscore) as pscount4 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-04%' and fscore = 'PASS' ");
			$rowpass4 = mysql_fetch_array($chkpass4);
			$pscount4 = $rowpass4['pscount4'];
		$chkfail4 = mysql_query("select count(fscore) as flcount4 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-04%' and fscore = 'FAIL' ");
			$rowfail4 = mysql_fetch_array($chkfail4);
			$flcount4 = $rowfail4['flcount4'];
		  
          echo "['April', ".$evcount4.", ".$pscount4.", ".$flcount4."], \n";
          
		  $chkagent5 = mysql_query("select count(distinct user) as agcount5 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-05%' ");
			$rowagent5 = mysql_fetch_array($chkagent5);
			$agcount5 = $rowagent5['agcount5'];
		$chkevals5 = mysql_query("select count(fscore) as evcount5 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-05%' and fscore != '' ");
			$rowevals5 = mysql_fetch_array($chkevals5);
			$evcount5 = $rowevals5['evcount5'];
		$chkpass5 = mysql_query("select count(fscore) as pscount5 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-05%' and fscore = 'PASS' ");
			$rowpass5 = mysql_fetch_array($chkpass5);
			$pscount5 = $rowpass5['pscount5'];
		$chkfail5 = mysql_query("select count(fscore) as flcount5 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-05%' and fscore = 'FAIL' ");
			$rowfail5 = mysql_fetch_array($chkfail5);
			$flcount5 = $rowfail5['flcount5'];
		  
          echo "['May', ".$evcount5.", ".$pscount5.", ".$flcount5."], \n";
		  
		  $chkagent6 = mysql_query("select count(distinct user) as agcount6 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-06%' ");
			$rowagent6 = mysql_fetch_array($chkagent6);
			$agcount6 = $rowagent6['agcount6'];
		$chkevals6 = mysql_query("select count(fscore) as evcount6 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-06%' and fscore != '' ");
			$rowevals6 = mysql_fetch_array($chkevals6);
			$evcount6 = $rowevals6['evcount6'];
		$chkpass6 = mysql_query("select count(fscore) as pscount6 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-06%' and fscore = 'PASS' ");
			$rowpass6 = mysql_fetch_array($chkpass6);
			$pscount6 = $rowpass6['pscount6'];
		$chkfail6 = mysql_query("select count(fscore) as flcount6 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-06%' and fscore = 'FAIL' ");
			$rowfail6 = mysql_fetch_array($chkfail6);
			$flcount6 = $rowfail6['flcount6'];
		  
          echo "['June', ".$evcount6.", ".$pscount6.", ".$flcount6."], \n";
          
		  $chkagent7 = mysql_query("select count(distinct user) as agcount7 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-07%' ");
			$rowagent7 = mysql_fetch_array($chkagent7);
			$agcount7 = $rowagent7['agcount7'];
		$chkevals7 = mysql_query("select count(fscore) as evcount7 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-07%' and fscore != '' ");
			$rowevals7 = mysql_fetch_array($chkevals7);
			$evcount7 = $rowevals7['evcount7'];
		$chkpass7 = mysql_query("select count(fscore) as pscount7 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-07%' and fscore = 'PASS' ");
			$rowpass7 = mysql_fetch_array($chkpass7);
			$pscount7 = $rowpass7['pscount7'];
		$chkfail7 = mysql_query("select count(fscore) as flcount7 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-07%' and fscore = 'FAIL' ");
			$rowfail7 = mysql_fetch_array($chkfail7);
			$flcount7 = $rowfail7['flcount7'];
		  
          echo "['July', ".$evcount7.", ".$pscount7.", ".$flcount7."], \n";
          
		  $chkagent8 = mysql_query("select count(distinct user) as agcount8 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-08%' ");
			$rowagent8 = mysql_fetch_array($chkagent8);
			$agcount8 = $rowagent8['agcount8'];
		$chkevals8 = mysql_query("select count(fscore) as evcount8 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-08%' and fscore != '' ");
			$rowevals8 = mysql_fetch_array($chkevals8);
			$evcount8 = $rowevals8['evcount8'];
		$chkpass8 = mysql_query("select count(fscore) as pscount8 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-08%' and fscore = 'PASS' ");
			$rowpass8 = mysql_fetch_array($chkpass8);
			$pscount8 = $rowpass8['pscount8'];
		$chkfail8 = mysql_query("select count(fscore) as flcount8 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-08%' and fscore = 'FAIL' ");
			$rowfail8 = mysql_fetch_array($chkfail8);
			$flcount8 = $rowfail8['flcount8'];
		  
          echo "['August', ".$evcount8.", ".$pscount8.", ".$flcount8."], \n";
          
		  $chkagent9 = mysql_query("select count(distinct user) as agcount9 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-09%' ");
			$rowagent9 = mysql_fetch_array($chkagent9);
			$agcount9 = $rowagent9['agcount9'];
		$chkevals9 = mysql_query("select count(fscore) as evcount9 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-09%' and fscore != '' ");
			$rowevals9 = mysql_fetch_array($chkevals9);
			$evcount9 = $rowevals9['evcount9'];
		$chkpass9 = mysql_query("select count(fscore) as pscount9 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-09%' and fscore = 'PASS' ");
			$rowpass9 = mysql_fetch_array($chkpass9);
			$pscount9 = $rowpass9['pscount9'];
		$chkfail9 = mysql_query("select count(fscore) as flcount9 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-09%' and fscore = 'FAIL' ");
			$rowfail9 = mysql_fetch_array($chkfail9);
			$flcount9 = $rowfail9['flcount9'];
		  
          echo "['September', ".$evcount9.", ".$pscount9.", ".$flcount9."], \n";
          
		  $chkagent10 = mysql_query("select count(distinct user) as agcount10 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-10%' ");
			$rowagent10 = mysql_fetch_array($chkagent10);
			$agcount10 = $rowagent10['agcount10'];
		$chkevals10 = mysql_query("select count(fscore) as evcount10 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-10%' and fscore != '' ");
			$rowevals10 = mysql_fetch_array($chkevals10);
			$evcount10 = $rowevals10['evcount10'];
		$chkpass10 = mysql_query("select count(fscore) as pscount10 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-10%' and fscore = 'PASS' ");
			$rowpass10 = mysql_fetch_array($chkpass10);
			$pscount10 = $rowpass10['pscount10'];
		$chkfail10 = mysql_query("select count(fscore) as flcount10 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-10%' and fscore = 'FAIL' ");
			$rowfail10 = mysql_fetch_array($chkfail10);
			$flcount10 = $rowfail10['flcount10'];
		  
          echo "['October', ".$evcount10.", ".$pscount10.", ".$flcount10."], \n";
		  
		  $chkagent11 = mysql_query("select count(distinct user) as agcount11 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-11%' ");
			$rowagent11 = mysql_fetch_array($chkagent11);
			$agcount11 = $rowagent11['agcount11'];
		$chkevals11 = mysql_query("select count(fscore) as evcount11 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-11%' and fscore != '' ");
			$rowevals11 = mysql_fetch_array($chkevals11);
			$evcount11 = $rowevals11['evcount11'];
		$chkpass11 = mysql_query("select count(fscore) as pscount11 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-11%' and fscore = 'PASS' ");
			$rowpass11 = mysql_fetch_array($chkpass11);
			$pscount11 = $rowpass11['pscount11'];
		$chkfail11 = mysql_query("select count(fscore) as flcount11 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-11%' and fscore = 'FAIL' ");
			$rowfail11 = mysql_fetch_array($chkfail11);
			$flcount11 = $rowfail11['flcount11'];
		  
          echo "['November', ".$evcount11.", ".$pscount11.", ".$flcount11."], \n";
          
		  $chkagent12 = mysql_query("select count(distinct user) as agcount12 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-12%' ");
			$rowagent12 = mysql_fetch_array($chkagent12);
			$agcount12 = $rowagent12['agcount12'];
		$chkevals12 = mysql_query("select count(fscore) as evcount12 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-12%' and fscore != '' ");
			$rowevals12 = mysql_fetch_array($chkevals12);
			$evcount12 = $rowevals12['evcount12'];
		$chkpass12 = mysql_query("select count(fscore) as pscount12 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-12%' and fscore = 'PASS' ");
			$rowpass12 = mysql_fetch_array($chkpass12);
			$pscount12 = $rowpass12['pscount12'];
		$chkfail12 = mysql_query("select count(fscore) as flcount12 from vicidial_agent_comments where campaign_id = '$campaign_selected' and call_date like '".$currdt."-12%' and fscore = 'FAIL' ");
			$rowfail12 = mysql_fetch_array($chkfail12);
			$flcount12 = $rowfail12['flcount12'];
		  
		echo "['December', ".$evcount12.", ".$pscount12.", ".$flcount12."], \n";
		echo "]); \n";

		echo "var options = { \n";
			echo "width: '100%', \n";
			echo "legend: { position: 'none' }, \n";
			echo "chart: { \n";
			echo "title: '  QA Performance', \n";
		echo "}, \n";
			echo "colors: ['#ff4000', '#008c00', '#d90036'] \n";
		echo "}; \n";
		
		/* additional table jquery inserted into chart table jquery start */
		echo "google.charts.load('current', {'packages':['table']}); \n";
		echo "google.charts.setOnLoadCallback(drawTable); \n";
		/* additional table jquery inserted into chart table jquery end */
		
		echo "var chart = new google.charts.Bar(document.getElementById('bar_chart')); \n";
		echo "chart.draw(data, options); \n";
		echo "} \n";
	  
	  /* additional table jquery function */
		echo"function drawTable() { \n";
			echo"var data = new google.visualization.DataTable(); \n";
			echo"data.addColumn('string', 'Overview'); \n";
			echo"data.addColumn('number', 'Jan'); \n";
			echo"data.addColumn('number', 'Feb'); \n";
			echo"data.addColumn('number', 'Mar'); \n";
			echo"data.addColumn('number', 'Apr'); \n";
			echo"data.addColumn('number', 'May'); \n";
			echo"data.addColumn('number', 'June'); \n";
			echo"data.addColumn('number', 'July'); \n";
			echo"data.addColumn('number', 'Aug'); \n";
			echo"data.addColumn('number', 'Sep'); \n";
			echo"data.addColumn('number', 'Oct'); \n";
			echo"data.addColumn('number', 'Nov'); \n";
			echo"data.addColumn('number', 'Dec'); \n";
			
			echo"data.addRows([ \n";
				echo"['Agents', {v: ".$agcount1."}, {v: ".$agcount2."}, {v: ".$agcount3."}, {v: ".$agcount4."}, {v: ".$agcount5."}, {v: ".$agcount6."}, {v: ".$agcount7."}, {v: ".$agcount8."}, {v: ".$agcount9."}, {v: ".$agcount10."}, {v: ".$agcount11."}, {v: ".$agcount12."}, ], \n";
				echo"['Evaluations', {v: ".$evcount1."}, {v: ".$evcount2."}, {v: ".$evcount3."}, {v: ".$evcount4."}, {v: ".$evcount5."}, {v: ".$evcount6."}, {v: ".$evcount7."}, {v: ".$evcount8."}, {v: ".$evcount9."}, {v: ".$evcount10."}, {v: ".$evcount11."}, {v: ".$evcount12."}, ], \n";
				echo"['Passes', {v: ".$pscount1."}, {v: ".$pscount2."}, {v: ".$pscount3."}, {v: ".$pscount4."}, {v: ".$pscount5."}, {v: ".$pscount6."}, {v: ".$pscount7."}, {v: ".$pscount8."}, {v: ".$pscount9."}, {v: ".$pscount10."}, {v: ".$pscount11."}, {v: ".$pscount12."}, ], \n";
				echo"['Fails', {v: ".$flcount1."}, {v: ".$flcount2."}, {v: ".$flcount3."}, {v: ".$flcount4."}, {v: ".$flcount5."}, {v: ".$flcount6."}, {v: ".$flcount7."}, {v: ".$flcount8."}, {v: ".$flcount9."}, {v: ".$flcount10."}, {v: ".$flcount11."}, {v: ".$flcount12."}, ], \n";
			echo"]); \n";
		
		echo"var table = new google.visualization.Table(document.getElementById('table_div')); \n";
		echo"var formatter = new google.visualization.ColorFormat(); \n";

		echo"table.draw(data, {showRowNumber: false, sort: 'disable', width: '100%', height: '100%'}); \n";
		echo"} \n";

?>
</script>
<!-- google charts end -->
<script>
function printSheet() {
		var printButton = document.getElementById("printpagebutton");
		printButton.style.visibility = 'hidden';
		window.print();
		printButton.style.visibility = 'visible';
	}
</script>


</head>
<BODY BGCOLOR="white" marginheight="5" marginwidth="5" leftmargin="5" topmargin="5">
<CENTER>
<TABLE WIDTH="1350" BGCOLOR="#D9E6FE" cellpadding="5" cellspacing="0" border="0"> <!-- change table size here -->
	<TR BGCOLOR="#015B91">
		<TD ALIGN="LEFT">
			<a href="./admin.php"><FONT FACE="ARIAL,HELVETICA" COLOR="WHITE"><B>VICIDIAL ADMIN</a>: QA Reports - Yearly
		</TD>
		<TD ALIGN="RIGHT">
			<FONT FACE="ARIAL,HELVETICA" COLOR="WHITE"><B><?php echo date("l F j, Y G:i:s A") ?></font>
		</TD>
	</TR>  

	<?php
	/* selecting the list of campaigns distinctly */
	$chkcampaign = mysql_query("SELECT DISTINCT(campaign_id) AS distcamp FROM vicidial_agent_comments WHERE call_date BETWEEN '$datefrom' AND '$dateto' ORDER BY campaign_id ");
	
	echo "<form action=\"".htmlentities($_SERVER['PHP_SELF'])."\" method=\"POST\"> \n";
	echo "<TR BGCOLOR=\"#F0F5FE\">\n";
		echo "<TD ALIGN=\"LEFT\" COLSPAN=\"2\">\n";
			echo "<FONT COLOR=\"BLACK\"><B>Select A Campaign: </B>";
			
			echo "<select name=\"campaign_choice\"> \n";
				echo "<option value=\"".$campaign_selected."\" elected = \"selected\">".$campaign_selected."</option> \n";
			
			while($rowcampaign = mysql_fetch_array($chkcampaign)) {
				$campaign_choice = $rowcampaign['distcamp'];
				echo "<option value=\"".$campaign_choice."\" >".$campaign_choice."</option> \n";
			}
				echo "<input type =\"submit\" value=\"Display Report\" class=\"NonPrintable\"/>\n";
			echo "</select> \n";
			echo "</form> \n";
			
		echo "</TD>\n";
	echo "</TR>\n";
	echo "<TR>\n";
		echo "<TD ALIGN=LEFT COLSPAN=2>\n";
			
			echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" align=\"center\" border = \"0\">\n";
				echo "<tr>\n";
					echo "<td align=left>\n";
					
					$chkcampname = mysql_query("SELECT campaign_name FROM vicidial_agent_comments WHERE campaign_id = '$campaign_selected' ");
						$rowcampname = mysql_fetch_array($chkcampname);
						$campaign_name_selected = $rowcampname['campaign_name'];
					
					if ($campaign_selected == "") {
						echo "<h3>Selected Campaign: <font color = \"red\">NONE. Please select a Campaign above.</font></h3> \n";
					} else {
						echo "<h3>Selected Campaign: ".$campaign_name_selected." (".$campaign_selected.")</h3> \n";
					}
					
					echo "</td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
					echo "<td align=\"center\">\n";
						
						/* div displays for chart and additional table */
						echo "<div id=\"bar_chart\" style=\"width: 100%; height: 200px;\"></div>\n";
						echo "<br> \n";
						echo "<div id=\"table_div\" style=\"width: 100%; height: 110px;\"></div>\n";

					echo "</td>\n";
				echo "</tr>\n";
				
				echo "<tr>\n";
					echo "<td align=\"center\">\n";
					
						echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" align=\"center\" border = \"1\" class=\"tblText\">\n";
							echo "<tr>\n";
								echo "<td align = \"center\" bgcolor = \"#004020\" colspan=\"25\">\n";
								
								$chkcount = mysql_query("SELECT COUNT(*) AS maxcount FROM vicidial_agent_comments WHERE campaign_id = '$campaign_selected' AND fscore != '' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
									$rowcount = mysql_fetch_array($chkcount);
									$maxcount = $rowcount['maxcount'];
									
									echo "<b><font color = \"white\">OVERALL AGENT DETAIL: ".number_format((float)$maxcount)." TOTAL EVALUATION(S)</font></b> \n";
								echo "</td> \n";
							echo "</tr>\n";
							echo "<tr>\n";
								echo "<td align = \"center\" valign = \"top\" width=\"25\">\n";
									echo "<b>No.</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"46\">\n";
									echo "<b>Score</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"55\">\n";
									echo "<b>P/F %</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"55\">\n";
									echo "<b>Passes</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"35\">\n";
									echo "<b>Fails</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"35\">\n";
									echo "<b>NAs</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"64\">\n";
									echo "<b>Agent</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"89\">\n";
									echo "<b>Ingroup</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"53\">\n";
									echo "<b>Status</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"78\">\n";
									echo "<b>Eval Date</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"78\">\n";
									echo "<b>Call Date</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"53\">\n";
									echo "<b>Sup</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"70\">\n";
									echo "<b>Lead ID</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"122\">\n";
									echo "<b>Comments</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"25\">\n";
									echo "<b>Q1</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"25\">\n";
									echo "<b>Q2</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"25\">\n";
									echo "<b>Q3</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"25\">\n";
									echo "<b>Q4</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"25\">\n";
									echo "<b>Q5</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"25\">\n";
									echo "<b>Q6</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"25\">\n";
									echo "<b>Q7</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"25\">\n";
									echo "<b>Q8</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"25\">\n";
									echo "<b>Q9</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"25\">\n";
									echo "<b>Q10</b> \n";
								echo "</td> \n";
								echo "<td align = \"center\" valign = \"top\" width=\"14\">\n";
									echo " \n";
								echo "</td> \n";
							echo "</tr>\n";
							
							echo "</tr>\n";
								echo "<td align = \"center\" colspan=\"25\" style=\"padding: 0px;\"> \n";
									
									echo "<iframe allowtransparency=\"true\" src=\"AST_comments_reports_list_year_template.php?campaign_choice=".$campaign_selected."\" width=\"100%\" height=\"250\" frameborder=\"0\" style=\"background-color: #D9E6FE display: block;\" scrolling=\"yes\"> \n";
									echo "</iframe> \n";

								echo "</td> \n";
							echo "</tr>\n";
							
						echo "</table> \n";
					
					echo "</td>\n";
				echo "</tr>\n";
				
				echo "<tr BGCOLOR=\"#015B91\">\n";
					echo "<td align=\"center\" colspan = \"5\">\n";
							echo "<input type =\"button\" onclick=\"location.href='AST_comments_reports.php'\" value=\"Back to Search\" class=\"NonPrintable\"/>\n";
							echo "<input id=\"printpagebutton\" type=\"button\" value=\"Print Report\" onclick=\"printSheet()\">\n";
					echo "</td>\n";
				echo "</tr>\n";
			echo "</TABLE>\n";
				
		echo "</td>\n";
	echo "</tr>\n";
echo "</TABLE>\n";
	
	
	
?>
<!-- main content ends here -->

</body>
</html>


