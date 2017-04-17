<?php
### Ast_comments_sup.php
### 
### Special thanks to Yiannos Katsirintakis <janokary@gmail.com> for the base code.    www.publicissue.gr LICENSE: GPLv2
#### Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#
# additional page for introduction to qa questions functionalities
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
<TABLE WIDTH="520" BGCOLOR="#D9E6FE" cellpadding="10" cellspacing="0">
	<TR BGCOLOR="#015B91">
		<TD ALIGN="LEFT">
			<a href="./admin.php"><FONT FACE="ARIAL,HELVETICA" COLOR=WHITE SIZE=2><B>VICIDIAL ADMIN</a>: QA Utilities
		</TD>
		<TD ALIGN="RIGHT">
			<FONT FACE="ARIAL,HELVETICA" COLOR="WHITE"><B><?php echo date("l F j, Y G:i:s A") ?></font>
		</TD>
	</TR> 

	<?php

	echo "<TR BGCOLOR=\"#F0F5FE\">\n";
		echo "<TD ALIGN=LEFT COLSPAN=2>\n";
			echo "<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK ><B>Utilities Description:</B>\n";
		echo "</TD>\n";
	echo "</TR>\n";
	echo "<TR>\n";
		echo "<TD ALIGN=LEFT COLSPAN=2>\n";

			echo "<TABLE width=\"600\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" border = \"0\">\n";
				echo "<tr>\n";
					echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
						echo "<b>QA Specialists: Review User Utility</b>\n";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
						echo "&nbsp; \n";
					echo "</td>";
					echo "<td align=\"justify\" bgcolor=\"#B9CBFD\">\n";
						echo "Use this utility to display Agent calls per campaign, per date range, per Agent, per User Group, or per Specialist. Provides ability to listen to recorded calls, score calls, and provide comment / feeback. QA Specialists are not allowed to edit or change scores and comments / feedback once submitted.<p>\n";
						echo "Activities are logged. \n";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<tr>\n";
					echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
						echo "<b>Supervisors Only: Questionnaire Utilities</b>\n";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
						echo "&nbsp; \n";
					echo "</td>";
					echo "<td align=\"justify\" bgcolor=\"#B9CBFD\">\n";
						echo "Use this utility to display Agent calls per campaign, per date range, per Agent, per User Group, or per Specialist. Provides ability to listen to recorded calls, edit scores, and provide additional or edit submitted comment / feeback.<p> \n";
						echo "You may also use this utility to:\n";
							echo "<ul> \n";
								echo "<li>View questions per Campaign</li>\n";
								echo "<li>View questions history per Campaign</li>\n";
								echo "<li>Create questions per Campaign</li>\n";
								echo "<li>Add questions per Campaign</li>\n";
								echo "<li>Edit questions per Campaign</li>\n";
								echo "<li>Disable / Enable questions per Campaign NO DELETE</li>\n";
								echo "<li>Set / Unset AUTOFAIL questions per Campaign</li>\n";
							echo "</ul> \n";
						echo "Activities are logged. \n";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td align=\"right\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
						echo "<a href=\"AST_comments_search.php\"><b>PROCEED >>></b></a> \n";
					echo "</td> \n";
				echo "</tr>";
			echo "</table>";

?>

