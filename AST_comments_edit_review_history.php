<?php

# Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#

session_start();

header ("Content-type: text/html; charset=utf-8");

require("dbconnect.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
	
if (isset($_GET["uniqueid"]))				{$uniqueid=$_GET["uniqueid"];}
	elseif (isset($_POST["uniqueid"]))		{$uniqueid=$_POST["uniqueid"];}	
	
//test submissions

//echo "uniqueid: ".$uniqueid."<br>";

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

<htmL>
<head>
<title>VICIDIAL ADMIN: Admin Edits History</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">

<style>
body {
	font-family: Arial, Helvetica;
}

</style>


</head>

<BODY BGCOLOR="white" marginheight="5" marginwidth="5" leftmargin="5" topmargin="5">
<TABLE WIDTH="1024" BGCOLOR="#D9E6FE" cellpadding="10" cellspacing="0" border = "0" align="center">
	<TR BGCOLOR="#015B91">
		<TD ALIGN="LEFT" colspan = "6">
			<FONT COLOR="WHITE" SIZE="2"><B>VICIDIAL ADMIN: Admin Edits History</font>
		</TD>
	</TR> 
	<TR BGCOLOR="#F0F5FE">
		<td align = "CENTER" valign="top"> 
			<b><font size ="2">No.</font></b>
		</td>
		<td align = "CENTER" valign="top"> 
			<b><font size ="2">Comment Date</font></b>
		</td>
		<td align = "CENTER" valign="top"> 
			<b><font size ="2">Unique ID</font></b>
		</td>
		<td align = "CENTER" valign="top"> 
			<b><font size ="2">Note</font></b>
		</td>
		<td align = "CENTER" valign="top"> 
			<b><font size ="2">Notated By</font></b>
		</td>
		<td align = "CENTER" valign="top"> 
			<b><font size ="2">Note Date</font></b>
		</td>
	</tr>
		
			<?php
		
	$result = mysql_query("SELECT * FROM vicidial_admin_notes WHERE uniqueid = '$uniqueid' ") or die(mysql_error());
		$i=1;
		while ($row = mysql_fetch_array($result)) {
			$his_comment_date = $row['comment_date'];
			$his_uniqueid = $row['uniqueid'];
			$his_note = $row['note'];
			$his_created_by = $row['created_by'];
			$his_create_date = $row['create_date'];
					
			if ($i % 2 == 0) {
				$bgcolor = "#9BB9FB";
			} else {
				$bgcolor = "#B9CBFD";
			}

			echo "<tr bgcolor = \"".$bgcolor."\">\n";
				echo "<td align = \"center\" valign = \"top\" width = \"15\"> \n";
					echo $i." \n";
				echo "</td> \n";
				echo "<td align = \"center\" valign = \"top\"> \n";
					echo $his_comment_date." \n";
				echo "</td> \n";
				echo "<td align = \"center\" valign = \"top\"> \n";
					echo $his_uniqueid." \n";
				echo "</td> \n";
				echo "<td align = \"justify\" valign = \"top\"> \n";
					echo $his_note." \n";
				echo "</td> \n";
				echo "<td align = \"center\" valign = \"top\"> \n";
					echo $his_created_by." \n";
				echo "</td> \n";
				echo "<td align = \"center\" valign = \"top\"> \n";
					echo $his_create_date." \n";
				echo "</td> \n";
			echo "</tr> \n";
				
		$i++;			
		}
				

		//echo "</td> \n";
	//echo "</tr> \n";
	
	echo "<tr bgcolor = \"#015B91\"> \n";
		echo "<td align = \"center\" valign=\"top\" colspan = \"6\"> \n";
			echo "<INPUT TYPE=\"button\" VALUE=\"Click to Close\" onClick=\"window.close()\"> \n";
		echo "</td> \n";
	echo "</tr> \n";
	
echo "</table> \n";


			?>

</body>
</html>	
		
