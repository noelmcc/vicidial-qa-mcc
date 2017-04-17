<?php
### Ast_comments_per_campaign.php
### 
### Copyright (C) 2008  Yiannos Katsirintakis <janokary@gmail.com>    www.publicissue.gr LICENSE: GPLv2
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

if (isset($_GET["campaign_id"]))				{$campaign_id=$_GET["campaign_id"];}
	elseif (isset($_POST["campaign_id"]))		{$campaign_id=$_POST["campaign_id"];}


	
	
	
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
<html>
<head>
<title>VICIDIAL ADMIN: </title>
<?
echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=utf-8\">\n";
?>
</head>
<BODY BGCOLOR=white marginheight=0 marginwidth=0 leftmargin=0 topmargin=0>
<CENTER>
<TABLE WIDTH=620 BGCOLOR=#D9E6FE cellpadding=2 cellspacing=0><TR BGCOLOR=#015B91><TD ALIGN=LEFT><? echo "<a href=\"./admin.php\">" ?><FONT FACE="ARIAL,HELVETICA" COLOR=WHITE SIZE=2><B> &nbsp; VICIDIAL ADMIN</a>: Comments Statistics for Campaign<? echo $campaign_id ?></TD><TD ALIGN=RIGHT><FONT FACE="ARIAL,HELVETICA" COLOR=WHITE SIZE=2><B><? echo date("l F j, Y G:i:s A") ?> &nbsp; </TD></TR>




<?	
	
echo "<TR BGCOLOR=\"#F0F5FE\"><TD ALIGN=LEFT COLSPAN=2><FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2><B> &nbsp; \n";
echo "</B></TD></TR>\n";
echo "<TR><TD ALIGN=LEFT COLSPAN=2>\n";

	
	
echo "<B>Comments per Status</B>\n";
echo "<TABLE width=650 cellspacing=0 cellpadding=1>\n";
echo "<tr><td><font size=1># </td><td><font size=2>Campaign Id </td><td align=left><font size=2>Campaign Name</td><td align=left><font size=2> Succeded</td><td align=left><font size=2> Commented</td><td align=right><font size=2> percentage</td></tr>\n";

	$stmt=("select vicidial_log.campaign_id,vicidial_log.status,count(*) counts,count(vicidial_agent_comments.comments) commented, 100*count(vicidial_agent_comments.comments)/count(*) percentage from vicidial_log left outer join vicidial_agent_comments on vicidial_log.uniqueid = vicidial_agent_comments.uniqueid where campaign_id='$campaign_id' group by campaign_id,status having sum(case when comments2 is null then 0 else 1 end) >0");

	
	$rslt=mysql_query($stmt);
	$logs_to_print = mysql_num_rows($rslt);

	$u=0;
	while ($logs_to_print > $u) {
		$row=mysql_fetch_row($rslt);
		if (eregi("1$|3$|5$|7$|9$", $u))
			{$bgcolor='bgcolor="#B9CBFD"';} 
		else
			{$bgcolor='bgcolor="#9BB9FB"';}

			$u++;
			echo "<tr $bgcolor>";
			echo "<td><font size=1>$u</td>";
			echo "<td><font size=2>$row[0]</td>";
			echo "<td align=left><font size=2> $row[1]</td>\n";
			echo "<td align=left><font size=2> $row[2]</td>\n";
			echo "<td align=left><font size=2> $row[3] </td>\n";
			echo "<td align=right><font size=2> $row[4] </td>\n";
	}
echo "</TABLE></center><BR><BR>\n";
// -----------------------------------
echo "<B>Comments per User</B>\n";
echo "<TABLE width=650 cellspacing=0 cellpadding=1>\n";
echo "<tr><td><font size=1># </td><td><font size=2>Campaign Id </td><td align=left><font size=2>User</td><td align=center><font size=2> Succeded</td><td align=center><font size=2> Commented</td><td align=right><font size=2> percentage</td></tr>\n";

	$stmt=("select vicidial_log.campaign_id campaign_id,	vicidial_log.user user,	count(*) successed,	count(vicidial_agent_comments.comments) commented ,	100*count(vicidial_agent_comments.comments)/count(*) percentage from vicidial_log left outer join vicidial_agent_comments on vicidial_agent_comments.uniqueid = vicidial_log.uniqueid where vicidial_log.campaign_id='$campaign_id' and vicidial_log.status='00' group by vicidial_log.campaign_id, vicidial_log.user ") ;  

	$rslt=mysql_query($stmt);
	$logs_to_print = mysql_num_rows($rslt);

	$u=0;
	while ($logs_to_print > $u) {
		$row=mysql_fetch_row($rslt);
		if (eregi("1$|3$|5$|7$|9$", $u))
			{$bgcolor='bgcolor="#B9CBFD"';} 
		else
			{$bgcolor='bgcolor="#9BB9FB"';}

			$u++;
			echo "<tr $bgcolor>";
			echo "<td><font size=1>$u</td>";
			echo "<td><font size=2>$row[0]</td>";
			echo "<td align=left><font size=2> $row[1]</td>\n";
			echo "<td align=center><font size=2> $row[2]</td>\n";
			echo "<td align=center><font size=2> $row[3] </td>\n";
			echo "<td align=right><font size=2> $row[4] </td>\n";
			echo "<td align=right><font size=2> <A HREF=\"AST_comments_per_agent.php?user=$row[1]\">$row[1]</A> </td></tr>\n";
			}
echo "</TABLE></center><BR><BR>\n";

echo "<a href=\"./AST_comments_list.php\">Comments List"


?>
