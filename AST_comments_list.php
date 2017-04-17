<?php
### AST_comments_list.php
### 
### Special thanks to Yiannos Katsirintakis <janokary@gmail.com> for the base code.    www.publicissue.gr LICENSE: GPLv2
#### Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#
# edited to GET POST from AST_comments_search.php to process chosen data
# edited 12-12-2016 noel cruz noel@mycallcloud.com

session_start();

header ("Content-type: text/html; charset=utf-8");

require("dbconnect.php");

//$recurl = "/var/spool/asterisk/monitorDONE/MP3/";			//change this to the path of the recording files - make sure for file searching, absolute path is used!!!
$recurl = "http://v17.mycallcloud.com/RECORDINGS/MP3/";
$recurl2 = "http://v18.mycallcloud.com/RECORDINGS/MP3/";

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

	//start getting POST data from AST_comments_search.php mcc_edits_start_12_12_2016
	
	if (empty($_POST['datefrom'])) {
		$datefrom = $_SESSION['datefrom'];
		$datefrommin = str_replace('/', '-', date('Y-m-d', strtotime($datefrom)));
		/* echo "1post datefrom: ".$_POST['datefrom']."<br>";
		echo "1session datefrom: ".$_SESSION['datefrom']."<br>"; */
	} else {
		$datefrom = str_replace('/', '-', date('Y-m-d', strtotime($_POST['datefrom'])))." 00:00:00";
		$_SESSION['datefrom'] = $datefrom;
		$datefrommin = str_replace('/', '-', date('Y-m-d', strtotime($_POST['datefrom'])));
		/* echo "2post datefrom: ".$_POST['datefrom']."<br>";
		echo "2session datefrom: ".$_SESSION['datefrom']."<br>"; */
	}
	
	$defdatefrom = date("Y-m-d", strtotime('-30 days'));
	
	if (empty($_POST['dateto'])) {
		$dateto = $_SESSION['dateto'];
		//$dateto = date('Y-m-d h:s:i');
		$datetomin = str_replace('/', '-', date('Y-m-d', strtotime($dateto)));
		/* echo "1post dateto: ".$_POST['dateto']."<br>";
		echo "1session dateto: ".$_SESSION['dateto']."<br>"; */
	} else {
		$dateto = str_replace('/', '-', date('Y-m-d', strtotime($_POST['dateto'])))." 23:59:59";
		$_SESSION['dateto'] = $dateto;
		$datetomin = str_replace('/', '-', date('Y-m-d', strtotime($_POST['dateto'])));
		/* echo "2post dateto: ".$_POST['dateto']."<br>";
		echo "2session dateto: ".$_SESSION['dateto']."<br>"; */
	}
	
	if (empty($_POST['campaigns'])) {$campaigns = $_SESSION['campaigns'];
	} else {$campaigns = $_POST['campaigns'];}
	if (empty($_POST['agentid'])) {$agents = $_SESSION['agentid'];
	} else {$agents = $_POST['agentid'];}
	if (empty($_POST['agentname'])) {$agentnames = $_SESSION['agentname'];
	} else {$agentnames = $_POST['agentname'];}
	if (empty($_POST['usergroups'])) {$usergroups = $_SESSION['usergroups'];
	} else {$usergroups = $_POST['usergroups'];}
	if (empty($_POST['specialist'])) {$specialist = $_SESSION['specialist'];
	} else {$specialist = $_POST['specialist'];}

	if (isset($_SESSION['search'])) {$post_search = $_SESSION['search'];
	} elseif (isset($_POST['search'])) {$post_search = $_POST['search'];
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($agentnames == "allagentnames") && ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "all";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($campaigns == "allcamps") && ($agents != "allagentids") && ($agentnames == "allagentnames") && ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "agent";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($agentnames != "allagentnames") && ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "agn";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($agentnames != "allagentnames") && ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "agn_us";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($agentnames != "allagentnames") && ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "agn_us_sp";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($agentnames != "allagentnames") && ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "agn_sp";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($agents == "allagentids") && ($agentnames == "allagentnames") && ($usergroups == "allgroups") && ($specialist == "allsups")) {	
		$post_search = "campaign";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($agents == "allagentids") && ($agentnames == "allagentnames") && ($campaigns == "allcamps") && ($specialist == "allsups")) {
		$post_search = "usergroups";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($agents == "allagentids") && ($agentnames == "allagentnames") && ($usergroups == "allgroups") && ($campaigns == "allcamps")) {
		$post_search = "specialist";
	} elseif (($agents == "allagentids") && ($agentnames == "allagentnames") && ($usergroups == "allgroups") && ($campaigns == "allcamps") && ($specialist == "allsups") && ($datefrommin != $defdatefrom) && ($datetomin != $currdt)) {	
		$post_search = "daterange";
	} elseif (($agents == "allagentids") && ($agentnames == "allagentnames") && ($usergroups == "allgroups") && ($specialist == "allsups") && ($datefrommin != $defdatefrom) && ($datetomin != $currdt)) {	
		$post_search = "daterange_campaign";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($agentnames == "allagentnames") && ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "agent_campaign_daterange";
	//} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($usergroups == "allgroups") && ($specialist == "allsups")) {
		//$post_search = "ca_ag_agn";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($agentnames == "allagentnames") && ($campaigns == "allcamps") && ($specialist == "allsups")) {
		$post_search = "agent_usergroups_daterange";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($agentnames == "allagentnames") && ($usergroups == "allgroups") && ($campaigns == "allcamps")) {
		$post_search = "agent_specialist_daterange";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($agentnames == "allagentnames") && ($agents == "allagentids") && ($specialist == "allsups")) {
		$post_search = "campaign_usergroups_daterange";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($agents == "allagentids") && ($agentnames == "allagentnames") && ($usergroups == "allgroups")) {
		$post_search = "campaign_specialist_daterange";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($agents == "allagentids") && ($agentnames == "allagentnames") && ($campaigns == "allcamps")) {	
		$post_search = "usergroups_specialist_daterange";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($agentnames == "allagentnames") && ($specialist == "allsups")) {	
		$post_search = "agent_campaign_usergroups_daterange";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($agentnames == "allagentnames") && ($usergroups == "allgroups")) {	
		$post_search = "agent_campaign_specialist_daterange";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($agentnames == "allagentnames") && ($campaigns == "allcamps")) {	
		$post_search = "agent_usergroups_specialist_daterange";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($agentnames == "allagentnames") && ($agents == "allagentids")) {
		$post_search = "campaign_usergroups_specialist_daterange";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($agentnames == "allagentnames")) {
		$post_search = "agent_campaign_usergroups_specialist_daterange";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($agents == "allagentids") && ($agentnames != "allagentnames") && ($usergroups == "allgroups") && ($campaigns != "allcamps") && ($specialist == "allsups")) {
		$post_search = "ca_agn";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($campaigns != "allcamps") && ($agents == "allagentids") && ($agentnames != "allagentnames") && ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "ca_agn_us";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($campaigns != "allcamps") && ($agents == "allagentids") && ($agentnames != "allagentnames") && ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "ca_agn_us_sp";
	} elseif (($datefrommin == $defdatefrom) && ($datetomin == $currdt) && ($campaigns != "allcamps") && ($agents == "allagentids") && ($agentnames != "allagentnames") && ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "ca_agn_sp";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($agents != "allagentids") && ($usergroups != "allgroups") && ($campaigns != "allcamps") && ($specialist != "allsups")) {
		$post_search = "filled";
		
	//new searches below start 12/28
		
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns != "allcamps") && ($agents != "allagentids") && ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ca_ag";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns != "allcamps") && ($agents == "allagentids") && ($agentnames != "allagentnames") && ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ca_agn";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns != "allcamps") && ($agents == "allagentids") && ($agentnames != "allagentnames") && ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ca_agn_us";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns != "allcamps") && ($agents == "allagentids") && ($agentnames != "allagentnames") && ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_ca_agn_us_sp";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns != "allcamps") && ($agents != "allagentids") && ($agentnames == "allagentnames") && ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ca_ag_us";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns != "allcamps") && ($agents != "allagentids") && ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_ca_ag_sp";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns != "allcamps") && ($agents == "allagentids") && ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_ca_sp";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns != "allcamps") && ($agents == "allagentids") && ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ca_us";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns != "allcamps") && ($agents == "allagentids") && ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_ca_us_sp";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns == "allcamps") && ($agents != "allagentids") && ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ag";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns == "allcamps") && ($agents != "allagentids") && ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ag_us";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns == "allcamps") && ($agents != "allagentids") && ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_ag_us_sp";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns == "allcamps") && ($agents != "allagentids") && ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_ag_sp";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($agentnames != "allagentnames") && ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_agn";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($agentnames != "allagentnames") && ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_agn_us";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($agentnames != "allagentnames") && ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_agn_us_sp";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_us";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_us_sp";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin != $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_sp";
		
	//new searches above end 12/28
	//new batch of searches start below 12/29
	
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_all";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns != "allcamps") && ($agents == "allagentids") && ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_ca";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns != "allcamps") && ($agents != "allagentids") && ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_ca_ag";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns != "allcamps") && ($agents != "allagentids") && ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_ca_ag_us";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns != "allcamps") && ($agents != "allagentids") && ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_ca_ag_us_sp";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns != "allcamps") && ($agents != "allagentids") && ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_ca_ag_sp";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns != "allcamps") && ($agents == "allagentids") && ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_ca_sp";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns != "allcamps") && ($agents == "allagentids") && ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_ca_us";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns != "allcamps") && ($agents == "allagentids") && ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_ca_us_sp";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns == "allcamps") && ($agents != "allagentids") && ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_ag";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns == "allcamps") && ($agents != "allagentids") && ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_ag_us";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns == "allcamps") && ($agents != "allagentids") && ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_ag_us_sp";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns == "allcamps") && ($agents != "allagentids") && ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_ag_sp";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($agentnames != "allagentnames") && ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_agn";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($agentnames != "allagentnames") && ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_agn_us";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($agentnames != "allagentnames") && ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_agn_us_sp";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($agentnames != "allagentnames") && ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_agn_sp";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_us";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_us_sp";
	} elseif (($datefrommin != $defdatefrom) && ($datetomin == $currdt) && ($campaigns == "allcamps") && ($agents == "allagentids") && ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_sp";
		
	//new batch of searches end above	12/29
		
	} else {
		$post_search = "";
	}
	
	//test outputs
	
	/* echo "datefrom: ".$datefrom."<br>";
	//echo "datefrommin: ".$datefrommin."<br>";
	//echo "defdatefrom: ".$defdatefrom."<br>";
	echo "dateto: ".$dateto."<br>";
	//echo "currdt: ".$currdt."<br>";
	//echo "datetomin: ".$datetomin."<br>";
	echo "campaigns: ".$campaigns."<br>";
	echo "agents: ".$agents."<br>";
	echo "agentnames: ".$agentnames."<br>";
	echo "usergroups: ".$usergroups."<br>";
	echo "specialist: ".$specialist."<br>";
	echo "post_search: ".$post_search."<p>"; */
	
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
<TABLE WIDTH="1366" BGCOLOR="#D9E6FE" cellpadding="10" cellspacing="0" border="0">
	<TR BGCOLOR="#015B91">
		<TD ALIGN="LEFT">
		
		<?php
		
		if (($agents == "allagentids") && ($agentnames == "allagentnames")) {
			echo "<a href=\"./admin.php\"><FONT COLOR=\"WHITE\"><B>VICIDIAL ADMIN</a>: List Agent Records (All Agents Selected) \n";
		} elseif (($agents == "allagentids") && ($agentnames != "allagentnames")) {
			echo "<a href=\"./admin.php\"><FONT COLOR=\"WHITE\"><B>VICIDIAL ADMIN</a>: List Agent Records For: ".$agentnames." \n";
		} elseif (($agents != "allagentids") && ($agentnames == "allagentnames")) {
			echo "<a href=\"./admin.php\"><FONT COLOR=\"WHITE\"><B>VICIDIAL ADMIN</a>: List Agent Records For: ".$agents." \n";
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
			echo "<FONT COLOR=\"BLACK\"><B>Select A Record to Review Below:</B>\n";
		echo "</TD>\n";
	echo "</TR>\n";
	echo "<TR>\n";
		echo "<TD ALIGN=\"LEFT\" COLSPAN=\"2\">\n";
			echo "<div class=\"row1\"><FONT COLOR=\"BLACK\" size= \"-1\"><B>Date Range - From:</b> ".$datefrom." <b>To:</b> ".$dateto."<br></font></div> \n";
			
			if ($campaigns == "allcamps") {
					echo "<div class=\"row2\"><FONT COLOR=\"BLACK\" size= \"-1\"><B>Campaign:</b> ALL CAMPAIGNS SELECTED</div><br> \n";
				} else {
					echo "<div class=\"row2\"><FONT COLOR=\"BLACK\" size= \"-1\"><B>Campaign:</b> ".$campaigns."</div><br> \n";
				}
				if (($agents == "allagentids") && ($agentnames != "allagentnames")) {
					
					$chkid = mysql_query("SELECT user FROM vicidial_agent_comments WHERE full_name = '$agentnames' ");
						$rowid = mysql_fetch_array($chkid);
						$chkid = $rowid['user'];
						
						echo "<div class=\"row1\"><FONT COLOR=\"BLACK\" size= \"-1\"><B>Agent:</b> ".$chkid." - ".$agentnames."</div><br> \n";
					
				} elseif (($agents != "allagentids") && ($agentnames != "allagentnames")) {
					
					$chkid = mysql_query("SELECT full_name FROM vicidial_users WHERE user = '$agents' ");
						$rowid = mysql_fetch_array($chkid);
						$chkfn = $rowid['full_name'];
						
						if ($chkfn == $agentnames) {
							echo "<div class=\"row1\"><FONT COLOR=\"BLACK\" size= \"-1\"><B>Agent:</b> ".$agents." - ".$agentnames."</div><br> \n";
						} else {
							echo "<div class=\"row1\"><FONT COLOR=\"BLACK\" size= \"-1\"><B>Agent: <font color = \"red\"> AGENT ID AND FULL NAME MISMATCH! Please make sure your search keys match or just select either Agent ID or Agent Name.</font></b></div><br> \n";
						}
					
				} elseif (($agents != "allagentids") && ($agentnames == "allagentnames")) {
					
					$chkid = mysql_query("SELECT full_name FROM vicidial_users WHERE user = '$agents' ");
						$rowid = mysql_fetch_array($chkid);
						$chkfn = $rowid['full_name'];

						echo "<div class=\"row1\"><FONT COLOR=\"BLACK\" size= \"-1\"><B>Agent:</b> ".$agents." - ".$chkfn."</div><br> \n";
					
				} else {
					echo "<div class=\"row1\"><FONT COLOR=\"BLACK\" size= \"-1\"><B>Agent:</b> ALL AGENTS SELECTED</div><br> \n";
				}
				if ($usergroups == "allgroups") {
					echo "<div class=\"row2\"><FONT COLOR=\"BLACK\"><B>User Group:</b> ALL USER GROUPS SELECTED</div><br> \n";
				} else {
					echo "<div class=\"row2\"><FONT COLOR=\"BLACK\"><B>User Group:</b> ".$usergroups."</div><br> \n";
				}
				if ($specialist == "allsups") {
					echo "<div class=\"row1\"><FONT COLOR=\"BLACK\"><B>Specialist:</b> ALL SPECIALISTS SELECTED<br></font></div> \n";
				} else {
					echo "<div class=\"row1\"><FONT COLOR=\"BLACK\"><B>Specialist:</b> ".$specialist."<br></font></div> \n";
				}

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
							echo "<b>Call Status</b>\n";
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
			
			//search by no specific detail (full search)
			if ($post_search == "all") {	
			
				$stmt2=("SELECT *  FROM vicidial_agent_comments WHERE call_date BETWEEN '$datefrom' AND '$dateto' AND user != 'VDAD' AND user != 'VDCL' ") or die(mysql_error());
					$rslt2=mysql_query($stmt2);
			
			//search by campaign
			} elseif ($post_search == "campaign") {

				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND call_date BETWEEN '$datefrom' AND '$dateto'") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
			
			//search by agent
			} elseif ($post_search == "agent") {
				
				$stmt2=("SELECT *  FROM vicidial_agent_comments WHERE user = '$agents' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2=mysql_query($stmt2);
			
			//search by agentname
			} elseif ($post_search == "agn") {
				
				$stmt2=("SELECT *  FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2=mysql_query($stmt2);
			
			//search by agentname and user groups
			} elseif ($post_search == "agn_us") {
				
				$stmt2=("SELECT *  FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2=mysql_query($stmt2);
			
			//search by agentname and user groups and specialist
			} elseif ($post_search == "agn_us_sp") {
				
				$stmt2=("SELECT *  FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2=mysql_query($stmt2);
			
			//search by agentname and specialist
			} elseif ($post_search == "agn_us") {
				
				$stmt2=("SELECT *  FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2=mysql_query($stmt2);
			
			//search by user groups
			} elseif ($post_search == "usergroups") {
				
				$stmt2=("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2=mysql_query($stmt2);
				
			//search by specialist
			} elseif ($post_search == "specialist") {
				
				$stmt2=("SELECT * FROM vicidial_agent_comments WHERE supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto'") or die(mysql_error());
					$rslt2=mysql_query($stmt2);
	
			//search by daterange
			} elseif ($post_search == "daterange") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE call_date BETWEEN '$datefrom' AND '$dateto'") or die(mysql_error());
					$rslt2=mysql_query($stmt2);
			
			//search by daterange and campaign
			} elseif ($post_search == "daterange_campaign") {	
			
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND call_date BETWEEN '$datefrom' AND '$dateto'") or die(mysql_error());
					$rslt2=mysql_query($stmt2);
					
			//search by agent and campaign and date range
			} elseif ($post_search == "agent_campaign_daterange") {
				
				$stmt2=("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND campaign_id = '$campaigns' AND call_date BETWEEN '$datefrom' AND '$dateto'") or die(mysql_error());
					$rslt2=mysql_query($stmt2);
					
			//search by agent and user group and date range
			} elseif ($post_search == "agent_usergroups_daterange") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto'") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by agent and specialist and date range
			} elseif ($post_search == "agent_specialist_daterange") {
				
				$stmt2=("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto'") or die(mysql_error());
					$rslt2=mysql_query($stmt2);
					
			//search by campaign and user group and date range
			} elseif ($post_search == "campaign_usergroups_daterange") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto'") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by campaign and specialist and date range
			} elseif ($post_search == "campaign_specialist_daterange") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto'") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
						
			//search by user groups and specialist and date range
			} elseif ($post_search == "usergroups_specialist_daterange") {	
			
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto'") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
						
			//search by agent and campaign and user group and date range	
			} elseif ($post_search == "agent_campaign_usergroups_daterange") {	
			
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND user_group = '$usergroups' AND campaign_id = '$campaigns' AND call_date BETWEEN '$datefrom' AND '$dateto'") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by agent and campaign and specialist and date range
			} elseif ($post_search == "agent_campaign_specialist_daterange") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND supervisor_id = '$specialist' AND campaign_id = '$campaigns' AND call_date BETWEEN '$datefrom' AND '$dateto'") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by agent and usergroups and specialist and date range
			} elseif ($post_search == "agent_usergroups_specialist_daterange") {	
			
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND supervisor_id = '$specialist' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto'") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by campaign and user group and specialist and date range
			} elseif ($post_search == "campaign_usergroups_specialist_daterange") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND campaign_id = '$campaigns' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto'") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by agent and campaign and user group and specialist and date range
			} elseif ($post_search == "agent_campaign_usergroups_specialist_daterange") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND user_group = '$usergroups' AND campaign_id = '$campaigns' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);

			//new searches 12/28
					
			//search by agentname and campaign
			} elseif ($post_search == "ca_agn") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND campaign_id = '$campaigns' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);

			//search by agentname and campaign and usergroup
			} elseif ($post_search == "ca_agn_us") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND campaign_id = '$campaigns' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);

			//search by agentname and campaign and usergroup and specialist
			} elseif ($post_search == "ca_agn_us_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND campaign_id = '$campaigns' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);

			//search by agentname and campaign and specialist
			} elseif ($post_search == "ca_agn_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND campaign_id = '$campaigns' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);

			//search by datefrom and dateto and campaign and agent
			} elseif ($post_search == "df_dt_ca_ag") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND campaign_id = '$campaigns' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
						
			//search by datefrom and dateto and campaign and agent name
			} elseif ($post_search == "df_dt_ca_agn") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND campaign_id = '$campaigns' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
						
			//search by datefrom and dateto and campaign and agent name and usergroups
			} elseif ($post_search == "df_dt_ca_agn_us") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND campaign_id = '$campaigns' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
						
			//search by datefrom and dateto and campaign and agent name and usergroups and specialist
			} elseif ($post_search == "df_dt_ca_agn_us") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND campaign_id = '$campaigns' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
						
			//search by datefrom and dateto and campaign and agent name and specialist
			} elseif ($post_search == "df_dt_ca_agn_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND campaign_id = '$campaigns' AND supervisor_d = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
						
			//search by datefrom  and dateto and and campaign and agent and usergroups
			} elseif ($post_search == "df_dt_ca_ag_us") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND campaign_id = '$campaigns' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom  and dateto and and campaign and agent and specialist
			} elseif ($post_search == "df_dt_ca_ag_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agents' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom  and dateto and and campaign and specialist
			} elseif ($post_search == "df_dt_ca_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom  and dateto and and usegroups
			} elseif ($post_search == "df_dt_ca_us") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom  and dateto and and usegroups and specialist
			} elseif ($post_search == "df_dt_ca_us_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom  and dateto and agent
			} elseif ($post_search == "df_dt_ag") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom  and dateto and agent and usergroups
			} elseif ($post_search == "df_dt_ag_us") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom  and dateto and agent and usergroups and specialist
			} elseif ($post_search == "df_dt_ag_us_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom  and dateto and agent and usergroups and specialist
			} elseif ($post_search == "df_dt_ag_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom  and dateto and agentname
			} elseif ($post_search == "df_dt_agn") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom  and dateto and agentname and usergroups
			} elseif ($post_search == "df_dt_agn_us") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom  and dateto and agentname and usergroups and specialist
			} elseif ($post_search == "df_dt_agn_us_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom  and dateto and agentname and specialist
			} elseif ($post_search == "df_dt_agn_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom  and dateto and usergroups
			} elseif ($post_search == "df_dt_us") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom  and dateto and usergroups and specialist
			} elseif ($post_search == "df_dt_us_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
						
			//search by datefrom  and dateto and specialist
			} elseif ($post_search == "df_dt_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
			
			//new searches 12/29
			
			//search by datefrom and all defaults
			} elseif ($post_search == "df_all") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
						
			//search by datefrom and campaign
			} elseif ($post_search == "df_ca") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
						
			//search by datefrom and campaign and agent
			} elseif ($post_search == "df_ca_ag") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND campaign_id = '$campaigns' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
						
			//search by datefrom and campaign and agent and usergroups
			} elseif ($post_search == "df_ca_ag_us") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND campaign_id = '$campaigns' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom and campaign and agent and usergroups
			} elseif ($post_search == "df_ca_ag_us_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND campaign_id = '$campaigns' AND user_group = '$usergroups' AND supervisor_id = $specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom and campaign and agent and specialist
			} elseif ($post_search == "df_ca_ag_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user = '$agents' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom and campaign and specialist
			} elseif ($post_search == "df_ca_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom and usegroups
			} elseif ($post_search == "df_ca_us") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom and usegroups and specialist
			} elseif ($post_search == "df_ca_us_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaigns' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom and agent
			} elseif ($post_search == "df_ag") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom and agent and usergroups
			} elseif ($post_search == "df_ag_us") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom and agent and usergroups and specialist
			} elseif ($post_search == "df_ag_us_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom and agent and usergroups and specialist
			} elseif ($post_search == "df_ag_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom and agentname
			} elseif ($post_search == "df_agn") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom and agentname and usergroup
			} elseif ($post_search == "df_agn_us") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom and agentname and usergroup and specialist
			} elseif ($post_search == "df_agn_us_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom and agentname and specialist
			} elseif ($post_search == "df_agn_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE full_name = '$agentnames' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom and usergroups
			} elseif ($post_search == "df_us") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by datefrom and usergroups and specialist
			} elseif ($post_search == "df_us_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user_group = '$usergroups' AND supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
						
			//search by datefrom and specialist
			} elseif ($post_search == "df_sp") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE supervisor_id = '$specialist' AND call_date BETWEEN '$datefrom' AND '$dateto' ") or die(mysql_error());
					$rslt2 = mysql_query($stmt2);
					
			//search by all details filled
			} elseif ($post_search == "filled") {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND supervisor_id = '$specialist' AND campaign_id = '$campaigns' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto'");
					$rslt2 = mysql_query($stmt2);
					
			} else {
				
				$stmt2 = ("SELECT * FROM vicidial_agent_comments WHERE user = '$agents' AND campaign_id = '$campaigns' AND user_group = '$usergroups' AND call_date BETWEEN '$datefrom' AND '$dateto'");
					$rslt2 = mysql_query($stmt2);
				
			}
				
				$u = 1;
					while ($row2 = mysql_fetch_array($rslt2)) {
						$call_date = $row2['call_date'];
						$comment_date = $row2['comment_date'];
						$uniqueid = $row2['uniqueid'];
						$lead_id = $row2['lead_id'];
						$agent = $row2['user'];
						$call_status = $row2['call_status'];
						$agent_fn = $row2['full_name'];
						$active = $row2['active'];
						$user_group = $row2['user_group'];
						$ingroup = $row2['ingroup'];
						$campaign_id = $row2['campaign_id'];
						$supervisor_id = $row2['supervisor_id'];
						$supervisor_name = $row2['supervisor_name'];

						if ($u % 2 == 0) {
							$bgcolor = "#9BB9FB";
						} else {
							$bgcolor = "#B9CBFD";
						}

					echo "<tr bgcolor = \"".$bgcolor."\">\n";
						echo "<td align=\"center\">\n";
							echo "<b>".$u."</b> \n";
						echo "</td>";
						
						echo "<td align=\"center\">\n";				
							echo $call_date." \n";
						echo "</td>\n";
						
						echo "<td align=\"center\">\n";				
						$rsltchktime = mysql_query("SELECT length_in_sec FROM recording_log WHERE lead_id = '$lead_id' AND user = '$agent' ");
							$rowchktime = mysql_fetch_array($rsltchktime);
							$callchktime = $rowchktime['length_in_sec'];
							$call_length = gmdate("H:i:s", $callchktime);
							
							echo $call_length." \n";
						echo "</td>\n";
						
						echo "<td align=\"center\">\n";
						$stmt2a1=("SELECT campaign_name FROM vicidial_agent_comments WHERE call_date = '$call_date' ") or die(mysql_error());
							$rslt2a1=mysql_query($stmt2a1);
							$row2a1 = mysql_fetch_array($rslt2a1);
							$campaign_name = $row2a1['campaign_name'];
							echo $campaign_id." (".$campaign_name.") \n";
						echo "</td>\n";
						
						echo "<td align=\"center\">\n";
						$stmt2a2=("SELECT user,full_name FROM vicidial_agent_comments WHERE call_date = '$call_date' ") or die(mysql_error());
							$rslt2a2=mysql_query($stmt2a2);
							$row2a2 = mysql_fetch_array($rslt2a2);
							$agent = $row2a2['user'];
							
							echo $agent." - ".$agent_fn." \n";
						echo "</td>\n";
						
						echo "<td align=\"center\">\n";				
							echo $call_status." \n";
						echo "</td>\n";
						
						echo "<td align=\"center\">\n";				
							echo $user_group." \n";
						echo "</td>\n";
						
						echo "<td align=\"center\">\n";				
							echo $ingroup." \n";			//change to ingroup
						echo "</td>\n";
						
						echo "<td align=\"center\">\n";
						$stmt2a3=("SELECT supervisor_id,supervisor_name,comments FROM vicidial_agent_comments WHERE call_date = '$call_date' ") or die(mysql_error());
							$rslt2a3=mysql_query($stmt2a3);
							$row2a3 = mysql_fetch_array($rslt2a3);
							$supervisor_id = $row2a3['supervisor_id'];
							$supervisor_fn = $row2a3['supervisor_name'];
							$eval_comments = $row2a3['comments'];
							
							echo $supervisor_id." - ".$supervisor_fn." \n";
						echo "</td>\n";
						
						echo "<td align=\"center\">\n";
						
						//start to look for recording if found in storage
						//$chkfile = ("SELECT filename FROM recording_log WHERE vicidial_id = '".$uniqueid."' ");
						$chkfile = mysql_query("SELECT filename, location FROM recording_log WHERE lead_id = '$lead_id' AND user = '$agent' ");
							$rowfile = mysql_fetch_array($chkfile);
							$callfile = $rowfile['filename'];
							$callurl = $rowfile['location'];
							
							//$callfname = $recurl."".$callfile."-all.mp3";
							
						if (!empty($eval_comments)) { 
								echo " <A HREF=\"AST_comments_edit_review.php?campaigns=".$campaign_id."&lead_id=".$lead_id."&agent=".$agent."&agentname=".$agentnames."&usergroups=".$user_group."&specialist=".$supervisor_id."&uniqueid=".$uniqueid."&call_date=".$call_date."&comment_date=".$comment_date."&search=".$post_search."\">Review Record</A> \n";
						echo "</td>\n";
					echo "</tr>\n";
						} elseif (empty($eval_comments)) { 
								echo " <A HREF=\"AST_comments_edit_review.php?campaigns=".$campaign_id."&lead_id=".$lead_id."&agent=".$agent."&agentname=".$agentnames."&usergroups=".$user_group."&specialist=".$supervisor_id."&uniqueid=".$uniqueid."&call_date=".$call_date."&comment_date=".$comment_date."&search=".$post_search."\">Evaluate</A> \n";
						echo "</td>\n";
					echo "</tr>\n";
						} else {
								echo "Audio File Not Found \n";
						echo "</td>\n";
					echo "</tr>\n";
						}
				
					$u++;
					}
					
					echo "<tr BGCOLOR=\"#015B91\">\n";
						echo "<td align=\"center\" colspan = \"10\">\n";
								echo "<input type =\"button\" onclick=\"location.href='AST_comments_search_reset.php'\" value=\"Back to Search\" />\n";
						echo "</td>\n";
					echo "</tr>\n";
				echo "</TABLE>\n";
	
	//start setting SESSION data
	//$_SESSION['datefrom'] = $datefrom;
	//$_SESSION['datefrommin'] = $datefrommin;
	//$_SESSION['defdatefrom'] = $defdatefrom;
	//$_SESSION['dateto'] = $dateto;
	//$_SESSION['currdt'] = $currdt;
	//$_SESSION['datetomin'] = $datetomin;
	//$_SESSION['dateto'] = $dateto;
	$_SESSION['campaigns'] = $campaigns;
	$_SESSION['agents'] = $agents;
	$_SESSION['agentname'] = $agentnames;
	$_SESSION['usergroups'] = $usergroups;
	$_SESSION['specialist'] = $specialist;
	$_SESSION['post_search'] = $post_search;
	//end setting SESSION data

	/* echo "datefrom: ".$datefrom."<br>";
	//echo "datefrommin: ".$_SESSION['datefrommin']."<br>";
	//echo "defdatefrom: ".$_SESSION['defdatefrom']."<br>";
	echo "dateto: ".$dateto."<br>";
	//echo "currdt: ".$_SESSION['currdt']."<br>";
	//echo "datetomin: ".$_SESSION['datetomin']."<br>";
	echo "campaigns: ".$_SESSION['campaigns']."<br>";
	echo "agents: ".$_SESSION['agents']."<br>";
	echo "agentname: ".$_SESSION['agentname']."<br>";
	echo "usergroups: ".$_SESSION['usergroups']."<br>";
	echo "specialist: ".$_SESSION['specialist']."<br>";
	echo "post_search: ".$_SESSION['post_search']."<br>"; */
	
?>


