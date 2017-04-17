<?php
### AST_comments_supedit.php
### 
### Special thanks to Yiannos Katsirintakis <janokary@gmail.com> for the base code.    www.publicissue.gr LICENSE: GPLv2
#### Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#
# additional page for supervisor access to qa questions functionalities
# created 12-12-2016 noel cruz noel@mycallcloud.com

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
<title>VICIDIAL ADMIN: QA Editor</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">

<style>
body {
	font-family: Arial, Helvetica;
}

#opfield{
 width:365px;   
}


</style>

</head>
<BODY BGCOLOR="white" marginheight="5" marginwidth="0" leftmargin="0" topmargin="5">
<CENTER>
<TABLE WIDTH="520" BGCOLOR="#D9E6FE" cellpadding="10" cellspacing="0">
	<TR BGCOLOR="#015B91">
		<TD ALIGN="LEFT">
			<a href="./admin.php"><FONT FACE="ARIAL,HELVETICA" COLOR=WHITE SIZE=2><B>VICIDIAL ADMIN</a>: QA Editor
		</TD>
		<TD ALIGN="RIGHT">
			<FONT FACE="ARIAL,HELVETICA" COLOR="WHITE"><B><?php echo date("l F j, Y G:i:s A") ?></font>
		</TD>
	</TR> 

	<?php

	echo "<TR BGCOLOR=\"#F0F5FE\">\n";
		echo "<TD ALIGN=LEFT COLSPAN=2>\n";
			echo "<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK ><B>Admin Utilities</B>\n";
		echo "</TD>\n";
	echo "</TR>\n";
	echo "<TR>\n";
		echo "<TD ALIGN=LEFT COLSPAN=2>\n";
		
		echo "<form name=\"supsearchutility\" action=\"AST_comments_supedit_p2.php\" method=\"post\"> \n";
			echo "<TABLE width=\"600\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" border = \"0\">\n";
				echo "<tr>\n";
					echo "<td align=\"left\" bgcolor=\"#9BB9FB\">\n";
						echo "<b>Select A Campaign Question Set to Edit</b>\n";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
					
					echo "<select name=\"campaigns\" id = \"opfield\"> \n";
						echo "<option value=\"nocamp\" selected = \"selected\">--- SELECT A CAMPAIGN ---</option> \n";
					
						$result1 = mysql_query("SELECT campaign_id, campaign_name, active FROM vicidial_campaigns ORDER BY campaign_id ASC") or die(mysql_error());
							$c = 1;
							while ($row1 = mysql_fetch_array($result1)) {
								$campid = $row1['campaign_id'];
								$camp_name = $row1['campaign_name'];
								$camp_status = $row1['active'];
								
								if ($camp_status == "Y") {
									$cstatus = "(ACTIVE)";
								} else {
									$cstatus = "(NOT ACTIVE)";
								}
							
								echo "<option value=\"".$campid."\">".$campid." - ".$camp_name." ".$cstatus."</option> \n";
								
							$c++;	
							}
							
						echo "</select> \n";
					echo "</td>";
				echo "</tr>";
				
				echo "<tr>";
					echo "<td align=\"center\" bgcolor=\"#015B91\">\n";
						echo "<input type=\"submit\" value=\"Proceed\" /> \n";
						echo "<input type=\"reset\" value=\"Reset\" /> \n";
						echo "<input type =\"button\" value=\"Go to Search Records\" onclick=\"window.open('AST_comments_search.php');\" /> \n";
					echo "</td> \n";
				echo "</tr>";
				echo "<tr>";
					echo "<td align=\"justify\">\n";
						echo "<font size = \"-1\"><b><i>NOTE: </b>Go to Search Records may open in a new window if your browser is not set to open windows in tabs.</i></font> \n";
					echo "</td>";
				echo "</tr>";
			echo "</table>";
		echo "</form> \n";

?>

