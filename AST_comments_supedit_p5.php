<?php
### AST_comments_supedit_p5.php
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

if (isset($_GET["campaigns"]))				{$sent_cid=$_GET["campaigns"];}
	elseif (isset($_POST["campaigns"]))		{$sent_cid=$_POST["campaigns"];}

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

<script language="JavaScript">
function desc_pop(){
popup = window.open("AST_comments_desc.html","","screenX=0,screenY=0,toolbar=0,location=0,status=0,menubar=0,scrollbars=1,resizable=0,width=800,height=450,top='+screen.availTop+',left='+screen.availLeft");
}

function desc2_pop(){
popup = window.open("AST_comments_desc2.html","","screenX=0,screenY=0,toolbar=0,location=0,status=0,menubar=0,scrollbars=1,resizable=0,width=800,height=450,top='+screen.availTop+',left='+screen.availLeft");
}

function desc3_pop(){
popup = window.open("AST_comments_desc3.html","","screenX=0,screenY=0,toolbar=0,location=0,status=0,menubar=0,scrollbars=1,resizable=0,width=800,height=450,top='+screen.availTop+',left='+screen.availLeft");
}

</script>

</head>
<BODY BGCOLOR="white" marginheight="5" marginwidth="0" leftmargin="0" topmargin="5">
<CENTER>
<TABLE WIDTH="1024" BGCOLOR="#D9E6FE" cellpadding="10" cellspacing="0">
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

			echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" border = \"0\">\n";
				echo "<tr>\n";
					echo "<td align=\"left\" bgcolor=\"#9BB9FB\">\n";
						echo "<b>Campaign Record</b>\n";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";

						$case_cid = strtolower($sent_cid);
						$result1 = mysql_query("SELECT campaign_id, campaign_name FROM vicidial_campaigns WHERE campaign_id = '$sent_cid'") or die(mysql_error());
							$row1 = mysql_fetch_array($result1);
							$camp_name = $row1['campaign_name'];
					
						echo "Selected Campaign: <b>".$sent_cid." - ".$camp_name."</b> \n";
					echo "</td>";
				echo "</tr>";
				
				echo "<tr>\n";
					echo "<td align=\"left\" bgcolor=\"#9BB9FB\">\n";
						echo "<b>Edit Disabled Questions</b> - Click on a question to select.\n";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
							echo "<table width=\"100%\" cellpadding = \"5\" cellspacing = \"0\" align = \"center\" border = \"0\"> \n";
								echo "<tr bgcolor =\"#00468C\"> \n";
									echo "<td align = \"center\" valign = \"top\" width = \"25\"> \n";
										echo "<font size = \"-1\" color = \"white\"><b>No.</b></font> \n";
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo "<font size = \"-1\" color = \"white\"><b>Question</b></font> \n";
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo "<font size = \"-1\" color = \"white\"><b>Autofail</b></font> \n";
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo "<font size = \"-1\" color = \"white\"><b>Active?</b></font> \n";
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo "<font size = \"-1\" color = \"white\"><b>Created By</b></font> \n";
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo "<font size = \"-1\" color = \"white\"><b>Create Date</b></font> \n";
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo "<font size = \"-1\" color = \"white\"><b>Edited By</b></font> \n";
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo "<font size = \"-1\" color = \"white\"><b>Edit Date</b></font> \n";
									echo "</td> \n";
								echo "</tr> \n";
								
						$result2 = mysql_query("SELECT * FROM questions_cid_".$case_cid." WHERE status = 'INACTIVE' ORDER BY seq_num ASC") or die(mysql_error());
						$c = 1;
						while ($row2 = mysql_fetch_array($result2)) {
							
							if ($c % 2 == 0) {
								$bgcolor = "#9BB9FB";
							} else {
								$bgcolor = "#B9CBFD";
							}
							
							
							$qid = $row2['seq_num'];
							$question = $row2['question'];
							$autofail = $row2['autofail'];
							$status = $row2['status'];
							$seq_num = $row2['seq_num'];
							$created_by = $row2['created_by'];
							$create_date = $row2['create_date'];
							$edited_by = $row2['edited_by'];
							$edit_date = $row2['edit_date'];
					
								echo "<tr bgcolor = ".$bgcolor."> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo $qid." \n";
									echo "</td> \n";
									echo "<td align = \"justify\" valign = \"top\"> \n";
										echo "<a href = \"AST_comments_supedit_p6.php?seqnum=".$seq_num."&campaigns=".$case_cid."\">".$question."</a> \n";
										
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
									if ($autofail == "Y") {
										echo "<font color = \"red\"><b>".$autofail."</b></font> \n";
									} else {
										echo $autofail." \n";
									}
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo "NO \n";
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo $created_by." \n";
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo $create_date." \n";
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo $edited_by." \n";
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo $edit_date." \n";
									echo "</td> \n";
								echo "</tr> \n";
								
						$c++;
						}
						
						echo "</table> \n";

					echo "</td>";
				echo "</tr>";
				
				echo "<tr>";
					echo "<td align=\"center\" bgcolor =\"#00468C\">\n";
						echo "<input type =\"button\" onclick=\"location.href='AST_comments_supedit.php'\" value=\"Back to Search\" />\n";
					echo "</td> \n";
				echo "</tr>";
			echo "</table>";
						
		
?>

</table>
		<br><br>

	</body>
</html>
