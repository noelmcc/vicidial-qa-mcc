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
	
	/* echo "sent_datefrom: ".$sent_datefrom."<br>";
	echo "sent_dateto: ".$sent_dateto."<br>";
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $( function() {
    var dateFormat = "Y-m-d",
      from = $( "#from" )
        .datepicker({
          defaultDate: "+0w",
          changeMonth: true,
          numberOfMonths: 1
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( "#to" ).datepicker({
        defaultDate: "+0w",
        changeMonth: true,
        numberOfMonths: 1
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });
 
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
 
      return date;
    }
  } );
  </script>
<style>
body {
	font-family: Arial, Helvetica;
}

</style>

</head>
<BODY BGCOLOR="white" marginheight="5" marginwidth="0" leftmargin="0" topmargin="5">
<CENTER>
<TABLE WIDTH="520" BGCOLOR="#D9E6FE" cellpadding="10" cellspacing="0" border="0">
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

	$currdt = date("Y-m-d");
	$mindt = date("Y-m-d", strtotime('-30 days'));			//set min date to 30 days from current date
	
	echo "<TR BGCOLOR=\"#F0F5FE\">\n";
		echo "<TD ALIGN=\"LEFT\" COLSPAN=\"2\">\n";
		
		$chkcid = mysql_query("SELECT campaign_id FROM vicidial_campaign_statuses WHERE status = '$sent_status' ");
			$rowcid = mysql_fetch_array($chkcid);
			$list_campaign = $rowcid['campaign_id'];
		
		$chkcount = mysql_query("SELECT COUNT(*) AS count FROM vicidial_agent_comments WHERE campaign_id = '$list_campaign' ");
			$rowcount = mysql_fetch_array($chkcount);
			$call_count = $rowcount['count'];
			
			echo "<FONT COLOR=\"BLACK\"><B>No. of Calls Found: ".number_format($call_count)."</B> <p>\n";
		
		if ($call_count > 0) {
			echo "<FONT COLOR=\"BLACK\"><B>Select a Date Range to Narrow Down Search:</B>\n";
		echo "</TD>\n";
	echo "</TR>\n";
	echo "<TR>\n";
		echo "<TD ALIGN=LEFT COLSPAN=2>\n";
			
			echo "<form action=\"AST_comments_campstats_list4.php\" method=\"post\"> \n";
			echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" border = \"0\">\n";
				echo "<tr>\n";
					echo "<td align=center>\n";
						echo "<label for=\"from\">From: </label> \n";
						echo "<input type=\"text\" id=\"from\" name=\"datefrom\" value = \"".$mindt."\"> \n";
						echo "<label for=\"to\"> To: </label> \n";
						echo "<input type=\"text\" id=\"to\" name=\"dateto\" value = \"".$currdt."\"> \n";
						echo "<input type=\"hidden\" name=\"search\" value=\"".$sent_search."\" /> \n";
						echo "<input type=\"hidden\" name=\"status\" value=\"".$sent_status."\" /> \n";
						echo "<input type=\"hidden\" name=\"statusname\" value=\"".$sent_statusname."\" /> \n";
						echo "<input type=\"hidden\" name=\"campaign\" value=\"".$sent_campaign."\" /> \n";
						echo "<input type=\"hidden\" name=\"count\" value=\"".$sent_count."\" /> \n";
						echo "<br> \n";
						echo "<i>30 days recommended search</i> \n";
					echo "</td>\n";
				echo "</tr>\n";
				echo "<tr BGCOLOR=\"#015B91\">\n";
					echo "<td align=\"center\" colspan = \"6\">\n";
						echo "<input type=\"submit\" value=\"Begin Records Search\" /> \n";
						echo "<input type =\"button\" onclick=\"location.href='AST_comments_search_reset.php'\" value=\"Back to Search\" />\n";
					echo "</td>\n";
				echo "</tr>\n";
			echo "</TABLE>\n";
			echo "</form> \n";
		} else {
			echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" border = \"0\">\n";
				echo "<tr BGCOLOR=\"#015B91\">\n";
					echo "<td align=\"center\" colspan = \"6\">\n";
						echo "<input type =\"button\" onclick=\"location.href='AST_comments_search_reset.php'\" value=\"Back to Search\" />\n";
					echo "</td>\n";
				echo "</tr>\n";
			echo "</TABLE>\n";
		}
				
			echo "</td>\n";
		echo "</tr>\n";
	echo "</TABLE>\n";
	
	
	
?>


