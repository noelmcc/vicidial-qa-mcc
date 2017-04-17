<?php
### AST_comments_reports_list.php
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
	
	if (empty($_POST['dateffrom'])) {$datefrom = $_SESSION['datefrom'];
	} else {$dateto = $_POST['datefrom'];}
	
	if (empty($_POST['dateto'])) {$dateto = $_SESSION['dateto'];
	} else {$dateto = $_POST['dateto'];}

	if (empty($_POST['campaigns'])) {$campaigns = $_SESSION['campaigns'];
	} else {$campaigns = $_POST['campaigns'];}

	if (empty($_POST['agentid'])) {$agentid = $_SESSION['agentid'];
	} else {$agentid = $_POST['agentid'];}

	if (empty($_POST['agentname'])) {$agentname = $_SESSION['agentname'];
	} else {$agentname = $_POST['agentname'];}
	
	if (empty($_POST['usergroups'])) {$usergroups = $_SESSION['usergroups'];
	} else {$usergroups = $_POST['usergroups'];}

	if (empty($_POST['specialist'])) {$specialist = $_SESSION['specialist'];
	} else {$dateto = $_POST['specialist'];}

	//test outputs
	
	echo "<font size = \"+1\">post_search: ".$post_search."</font><br>";
	echo "<font size = \"+1\">datefrom: ".$datefrom."</font><br>";
	echo "<font size = \"+1\">dateto: ".$dateto."</font><br>";
	echo "<font size = \"+1\">campaigns: ".$campaigns."</font><br>";
	echo "<font size = \"+1\">agentid: ".$agentid."</font><br>";
	echo "<font size = \"+1\">agentname: ".$agentname."</font><br>";
	echo "<font size = \"+1\">usergroups: ".$usergroups."</font><br>";
	echo "<font size = \"+1\">specialist: ".$specialist."</font><br>";
	
	//end getting POST data from AST_comments_reports_pick.php
	
	//start start getting db details based on post
	
	if ($post_search == "all") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments ");
		
		
		
	} elseif ($post_search == "df") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ca") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ca_ag") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agentid' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ca_ag_an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agentid' AND full_name = '$agentname' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ca_ag_an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agentid' AND full_name = '$agentname' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ca_ag_an_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agentid' AND full_name = '$agentname' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ca_ag_an_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agentid' AND full_name = '$agentname' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ag") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ag_an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND full_name = '$agentname' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ag_an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND full_name = '$agentname' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ag_an_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND full_name = '$agentname' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ag_an_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND full_name = '$agentname' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ag_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ag_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND supervisor_id = '$specialist' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ag_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND call_date BETWEEN '$datefrom' AND supervisor_id = '$specialist' AND '$dateto' ");
	} elseif ($post_search == "df_dt_an_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_an_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_an_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_an_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ca_an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND full_name = '$agentname' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ca_an_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND full_name = '$agentname' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ca_an_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND full_name = '$agentname' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ca_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ca_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ca_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ca_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_dt_ca_an_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND full_name = '$agentname' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
	} elseif ($post_search == "df_ca") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND call_date = '$datefrom' ");
	} elseif ($post_search == "df_ca_ag") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agentid' AND call_date = '$datefrom' ");
	} elseif ($post_search == "df_ca_ag_an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agentid' AND full_name = '$agentname' AND call_date = '$datefrom' ");
	} elseif ($post_search == "df_ca_ag_an_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agentid' AND full_name = '$agentname' AND user_group = '$usergroups' AND call_date = '$datefrom' ");
	} elseif ($post_search == "df_ca_ag_an_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agentid' AND full_name = '$agentname' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date = '$datefrom' ");
	} elseif ($post_search == "df_ag") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND call_date = '$datefrom' ");
	} elseif ($post_search == "df_ag_an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND full_name = '$agentname' AND call_date = '$datefrom' ");
	} elseif ($post_search == "df_ag_an_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND full_name = '$agentname' AND user_group = '$usergroups' AND call_date = '$datefrom' ");
	} elseif ($post_search == "df_ag_an_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND full_name = '$agentname' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date = '$datefrom' ");
	} elseif ($post_search == "df_ag_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND user_group = '$usergroups' AND call_date = '$datefrom' ");
	} elseif ($post_search == "df_ag_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date = '$datefrom' ");
	} elseif ($post_search == "df_ag_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND supervisor_id = '$specialist' AND call_date = '$datefrom' ");
	} elseif ($post_search == "df_an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' AND call_date = '$datefrom' ");
	} elseif ($post_search == "df_an_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' AND user_group = '$usergroups' AND call_date = '$datefrom' ");
	} elseif ($post_search == "df_an_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' AND user_group = '$usergroups' AND supervisor_id  = '$specialist' AND call_date = '$datefrom' ");
	} elseif ($post_search == "df_an_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' AND supervisor_id  = '$specialist' AND call_date = '$datefrom' ");
	} elseif ($post_search == "df_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND call_date = '$datefrom' ");
	} elseif ($post_search == "df_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date = '$datefrom' ");
	} elseif ($post_search == "df_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE supervisor_id = '$specialist' AND call_date = '$datefrom' ");
	} elseif ($post_search == "ca") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' ");
	} elseif ($post_search == "ca_ag") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agentid' ");
	} elseif ($post_search == "ca_ag_an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agentid' AND full_name = '$agentname' ");
	} elseif ($post_search == "ca_ag_an_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agentid' AND full_name = '$agentname' AND user_group = '$usergroups' ");
	} elseif ($post_search == "ca_ag_an_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agentid' AND full_name = '$agentname' AND user_group = '$usergroups' AND supervisor_id = '$specialist' ");
	} elseif ($post_search == "ca_an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND full_name = '$agentname' ");
	} elseif ($post_search == "ca_an_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND full_name = '$agentname' AND user_group = '$usergroups' ");
	} elseif ($post_search == "ca_an_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND full_name = '$agentname' AND user_group = '$usergroups' AND supervisor_id = '$specialist' ");
	} elseif ($post_search == "ca_an_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND full_name = '$agentname' AND supervisor_id = '$specialist' ");
	} elseif ($post_search == "ca_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND supervisor_id = '$specialist' ");
	} elseif ($post_search == "ca_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND supervisor_id = '$specialist' ");
	} elseif ($post_search == "ag") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' ");
	} elseif ($post_search == "ag_an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND full_name = '$agentname' ");
	} elseif ($post_search == "ag_an_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND full_name = '$agentname' AND user_group = '$usergroups' ");
	} elseif ($post_search == "ag_an_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND full_name = '$agentname' AND user_group = '$usergroups' AND supervisor_id = '$specialist' ");
	} elseif ($post_search == "ag_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND user_group = '$usergroups' ");
	} elseif ($post_search == "ag_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND user_group = '$usergroups' AND supervisor_id = '$specialist' ");
	} elseif ($post_search == "ag_an_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND full_name = '$agentname' AND supervisor_id = '$specialist' ");
	} elseif ($post_search == "ag_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user = '$agentid' AND supervisor_id = '$specialist' ");
	} elseif ($post_search == "an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' ");
	} elseif ($post_search == "an") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' ");
	} elseif ($post_search == "an_us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' AND user_group = '$usergroups' ");
	} elseif ($post_search == "an_us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' AND user_group = '$usergroups' AND supervisor_id = '$specialist' ");
	} elseif ($post_search == "an_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentname' AND supervisor_id = '$specialist' ");
	} elseif ($post_search == "us") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' ");
	} elseif ($post_search == "us_sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND supervisor_id = '$specialist' ");
	} elseif ($post_search == "sp") {
		$chkps = mysql_query("SELECT * FROM vicidial_agent_comments WHERE supervisor_id = '$specialist' ");
	}
	
	
	
		
?>
<html>
<head>
<title>VICIDIAL ADMIN: QA Reports</title>
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

<!-- tabbed table start -->
<link rel="stylesheet" href="styles/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php
echo "<script> \n";
	echo "$( function() { \n";
		echo "$( \"#tabs\" ).tabs({ \n";
			echo "create: function( event, ui ) { \n";
				echo "drawStuff(); \n";
				echo "},	 \n";
			echo "activate: function( event, ui ) { \n";
			echo "var panelid = ui.newPanel.attr('id'); \n";
			
			echo "if (panelid == 'tabs-1') { \n";
				echo "drawStuff(); \n";
				
			echo "} else if (panelid == 'tabs-2') { \n";
				echo "drawStuff2(); \n";
			echo "} else if (panelid == 'tabs-3') { \n";
				echo "drawStuff3(); \n";
			echo "} \n";
			echo "} \n";
		echo "}); \n";
	echo "} ); \n";
echo "</script>   \n";
?>
<!-- tabbed table end -->

<!-- google charts start -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawStuff);
	  google.charts.setOnLoadCallback(drawStuff2);
	  google.charts.setOnLoadCallback(drawStuff3);

      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
          ['Move', 'Percentage'],
          ["King's pawn (e4)", 84],
          ["Queen's pawn (d4)", 31],
          ["Knight to King 3 (Nf3)", 12],
          ["Queen's bishop pawn (c4)", 10],
          ['Other', 3]
        ]);

        var options = {
          title: 'Chess opening moves 1',
          width: '100%',
          legend: { position: 'none' },
          chart: { subtitle: 'popularity by percentage' },
          axes: {
            x: {
              0: { side: 'top', label: 'White to move'} // Top x-axis.
            }
          },
          bar: { groupWidth: "90%" }
        };

        var chart = new google.charts.Bar(document.getElementById('top_x_div1'));
        // Convert the Classic options to Material options.
        chart.draw(data, google.charts.Bar.convertOptions(options));
      };
	  
	  function drawStuff2() {
        var data = new google.visualization.arrayToDataTable([
          ['Move', 'Percentage'],
          ["King's pawn (e4)", 4],
          ["Queen's pawn (d4)", 31],
          ["Knight to King 3 (Nf3)", 12],
          ["Queen's bishop pawn (c4)", 10],
          ['Other', 3]
        ]);

        var options = {
          title: 'Chess opening moves 2',
          width: '100%',
          legend: { position: 'none' },
          chart: { subtitle: 'popularity by percentage' },
          axes: {
            x: {
              0: { side: 'top', label: 'White to move'} // Top x-axis.
            }
          },
          bar: { groupWidth: "90%" }
        };

        var chart = new google.charts.Bar(document.getElementById('top_x_div2'));
        // Convert the Classic options to Material options.
        chart.draw(data, google.charts.Bar.convertOptions(options));
      };
	  
	  function drawStuff3() {
        var data = new google.visualization.arrayToDataTable([
          ['Move', 'Percentage'],
          ["King's pawn (e4)", 44],
          ["Queen's pawn (d4)", 31],
          ["Knight to King 3 (Nf3)", 12],
          ["Queen's bishop pawn (c4)", 10],
          ['Other', 3]
        ]);

        var options = {
          title: 'Chess opening moves 3',
          width: '100%',
          legend: { position: 'none' },
          chart: { subtitle: 'popularity by percentage' },
          axes: {
            x: {
              0: { side: 'top', label: 'White to move'} // Top x-axis.
            }
          },
          bar: { groupWidth: "90%" }
        };

        var chart = new google.charts.Bar(document.getElementById('top_x_div3'));
        // Convert the Classic options to Material options.
        chart.draw(data, google.charts.Bar.convertOptions(options));
      };
</script>
<script>
function printSheet() {
		var printButton = document.getElementById("printpagebutton");
		printButton.style.visibility = 'hidden';
		window.print();
		printButton.style.visibility = 'visible';
	}
</script>
<!-- google charts end -->

</head>
<BODY BGCOLOR="white" marginheight="5" marginwidth="5" leftmargin="5" topmargin="5">
<CENTER>
<TABLE WIDTH="100%" BGCOLOR="#D9E6FE" cellpadding="10" cellspacing="0" border="0"> <!-- change table size here -->
	<TR BGCOLOR="#015B91">
		<TD ALIGN="LEFT">
			<a href="./admin.php"><FONT FACE="ARIAL,HELVETICA" COLOR="WHITE"><B>VICIDIAL ADMIN</a>: QA Reports
		</TD>
		<TD ALIGN="RIGHT">
			<FONT FACE="ARIAL,HELVETICA" COLOR="WHITE"><B><?php echo date("l F j, Y G:i:s A") ?></font>
		</TD>
	</TR>  

	<?php
	
	/* table blocks start here */

	echo "<TR BGCOLOR=\"#F0F5FE\">\n";
		echo "<TD ALIGN=\"LEFT\" COLSPAN=\"2\">\n";
			echo "<FONT COLOR=\"BLACK\"><B>QA Reports</B>\n";
		echo "</TD>\n";
	echo "</TR>\n";
	echo "<TR>\n";
		echo "<TD ALIGN=LEFT COLSPAN=2>\n";
			
			echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" border = \"0\">\n";
				echo "<tr>\n";
					echo "<td align=center>\n";
							
						echo "<div id=\"tabs\" style =\"-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;\">\n";
							echo "<ul>\n";
								echo "<li><a href=\"#tabs-1\">Campaign 1</a></li>\n";
								echo "<li><a href=\"#tabs-2\">Campaign 2</a></li>\n";
								echo "<li><a href=\"#tabs-3\">Campaign 3</a></li>\n";
							echo "</ul>\n";
							echo "<div id=\"tabs-1\">\n";
								echo "<div id=\"top_x_div1\" style=\"width: 100%; height: 300px;\"></div>\n";
							echo "</div>\n";
							echo "<div id=\"tabs-2\">\n";
								echo "<div id=\"top_x_div2\" style=\"width: 100%; height: 300px;\"></div>\n";
							echo "</div>\n";
							echo "<div id=\"tabs-3\">\n";
								echo "<div id=\"top_x_div3\" style=\"width: 100%; height: 300px;\"></div>\n";
							echo "</div>\n";
						echo "</div>\n";
							
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

