<?php
### AST_comments_reports_pick.php
### 
### Special thanks to Yiannos Katsirintakis <janokary@gmail.com> for the base code.    www.publicissue.gr LICENSE: GPLv2
#### Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#
# Choice page from reports selection AST_comments_reports.php
# edited 01-06-2017 noel cruz noel@mycallcloud.com

session_start();
session_destroy();
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

	//start getting POST data
	
	if (empty($_POST['datefrom'])) {$datefrom = str_replace('/', '-', date('Y-m-d', strtotime($_SESSION['datefrom'])));
	} else {$datefrom = str_replace('/', '-', date('Y-m-d', strtotime($_POST['datefrom'])));}
	
	if (empty($_POST['dateto'])) {$dateto = str_replace('/', '-', date('Y-m-d', strtotime($_SESSION['dateto'])));
	} else {$dateto = str_replace('/', '-', date('Y-m-d', strtotime($_POST['dateto'])));}
	
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

	if (empty($_POST['year'])) {$year = $_SESSION['year'];
	} else {$year = $_POST['year'];}

	if (empty($_POST['month'])) {$month = $_SESSION['month'];
	} else {$month = $_POST['month'];}

	if (empty($_POST['week'])) {$week = $_SESSION['week'];
	} else {$week = $_POST['week'];}

	//end getting POST data
	
	$currdate = date("Y-m-d");
	$mindefdate = date("Y-m-d", strtotime('-30 days'));			## def date is 30 days from current date
	
	//test outputs
	
	echo "datefrom: ".$datefrom."<br>";				## def value: date is 30 days from current date @ 00:00:00
	echo "mindefdate: ".$mindefdate."<br>";		## def date is 30 days from current date
	echo "dateto: ".$dateto."<br>";						## def value: current date
	echo "currdate: ".$currdate."<br>";					## current date
	echo "campaigns: ".$campaigns."<br>";			## def value: allcamps
	echo "agentid: ".$agentid."<br>";						## def value: allagentids
	echo "agentname: ".$agentname."<br>";		## def value: allagentnames
	echo "usergroups: ".$usergroups."<br>";			## def value: allgroups
	echo "specialist: ".$specialist."<br>";				## def value: allsups
	echo "year: ".$year."<br>";								## def value: currentyear
	echo "month: ".$month."<br>";						## def value: currentmonth
	echo "week: ".$week."<br>";								## def value: currentyear
	
	//start getting combinations
	
	if (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames") 
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "all";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns != "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ca";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns != "allcamps") && ($agentid != "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ca_ag";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns != "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ca_ag_an";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns != "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ca_ag_an_us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns != "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_ca_ag_an_us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ag";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ag_an";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ag_an_us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_ag_an_us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ag_us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_ag_us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_ag_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_an_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_an";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_an_us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_an_us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns != "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ca_an";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns != "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ca_an_us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns != "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ca_an_us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns != "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_ca_an_us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns != "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_dt_ca_us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns != "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_ca_us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns != "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_ca_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto != $currdate) && ($campaigns != "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_dt_ca_an_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns != "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_ca";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns != "allcamps") && ($agentid != "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_ca_ag";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns != "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_ca_ag_an";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns != "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_ca_ag_an_us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns != "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_ca_ag_an_us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_ag";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_ag_an";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_ag_an";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_ag_an_us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_ag_an_us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_ag_us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_ag_us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_ag_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_an";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_an_us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_an_us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_an_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "df_us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom != $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "df_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns != "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "ca";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns != "allcamps") && ($agentid != "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "ca_ag";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns != "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "ca_ag_an";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns != "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "ca_ag_an_us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns != "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "ca_ag_an_us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns != "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "ca_an";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns != "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "ca_an_us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns != "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "ca_an_us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns != "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "ca_an_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns != "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "ca_us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns != "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "ca_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "ag";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "ag_an";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "ag_an_us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "ag_an_us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "ag_us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "ag_us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "ag_an_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid != "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "ag_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist == "allsups")) {
		$post_search = "an";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "an_us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "an_us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname != "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "an_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist == "allsups")) {
		$post_search = "us";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups != "allgroups") && ($specialist != "allsups")) {
		$post_search = "us_sp";
		//echo "post search: ".$post_search."<br>";
	} elseif (($datefrom == $mindefdate) && ($dateto == $currdate) && ($campaigns == "allcamps") && ($agentid == "allagentids") && ($agentname == "allagentnames")
		&& ($usergroups == "allgroups") && ($specialist != "allsups")) {
		$post_search = "sp";
		//echo "post search: ".$post_search."<br>";
	}
	
	
	$_SESSION['post_search'] = $post_search;
	$_SESSION['datefrom'] = $datefrom;
	$_SESSION['dateto'] = $dateto;
	$_SESSION['campaigns'] = $campaigns;
	$_SESSION['agentid'] = $agentid;
	$_SESSION['agentname'] = $agentname;
	$_SESSION['usergroups'] = $usergroups;
	$_SESSION['specialist'] = $specialist;
	$_SESSION['year'] = $year;
	$_SESSION['month'] = $month;
	$_SESSION['week'] = $week;
	echo "post_search: ".$_SESSION['post_search']."<p> \n";
	
	if (!empty($year)) {
		//echo "<meta http-equiv='REFRESH' content='0;url=AST_comments_reports_list_year.php'>";					## list page for year
		echo "<input type =\"button\" onclick=\"location.href='AST_comments_reports_list_year.php'\" value=\"Go Yearly\" /> ";
	} elseif (!empty($month)) {
		//echo "<meta http-equiv='REFRESH' content='0;url=AST_comments_reports_list_month.php'>";				## list page for month
		echo "<input type =\"button\" onclick=\"location.href='AST_comments_reports_list_month.php'\" value=\"Go Monthly\" /> ";
	} elseif (!empty($week)) {
		//echo "<meta http-equiv='REFRESH' content='0;url=AST_comments_reports_list_week.php'>";					## list page for week
		echo "<input type =\"button\" onclick=\"location.href='AST_comments_reports_list_week.php'\" value=\"Go Weekly\" /> ";
	} else {
		//echo "<meta http-equiv='REFRESH' content='0;url=AST_comments_reports_list.php'>";								## list page for day
		echo "<input type =\"button\" onclick=\"location.href='AST_comments_reports_list.php'\" value=\"Go Daily\" /> ";
	}
	
	//echo "<br><br> \n";
	echo "<input type =\"button\" onclick=\"location.href='AST_comments_reports.php'\" value=\"Back to Search\" /> ";
	//echo "<input type =\"button\" onclick=\"location.href='AST_comments_reports_list.php'\" value=\"Go\" /> ";
	
	
	
?>