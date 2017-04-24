<?php
### AST_comments_supedit_p3.php
### 
### Special thanks to Yiannos Katsirintakis <janokary@gmail.com> for the base code.    www.publicissue.gr LICENSE: GPLv2
###
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

function addq_pop() {
    var win = window.open("AST_comments_addq.php?campaigns=<?php echo $sent_cid; ?>","","screenX=0,screenY=0,toolbar=0,location=0,status=0,menubar=0,scrollbars=1,resizable=0,width=520,height=220,top='+screen.availTop+',left='+screen.availLeft");
}


</script>

</head>
<BODY BGCOLOR="white" marginheight="5" marginwidth="0" leftmargin="0" topmargin="5">
<CENTER>
<TABLE WIDTH="1024" BGCOLOR="#D9E6FE" cellpadding="10" cellspacing="0" border = "0">
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

		if ($_POST['campaigns'] == "nocamp") {
				
			echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" border = \"0\">\n";
				echo "<tr>\n";
					echo "<td align=\"left\" bgcolor=\"#9BB9FB\">\n";
						echo "<b>Select A Campaign</b>\n";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
						echo "<font color = \"red\" size = \"+3\"><b>You have not selected any campaigns</b></font> \n";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td align=\"center\" bgcolor=\"#9BB9FB\">\n";
						echo "<input type =\"button\" onclick=\"location.href='AST_comments_supedit.php'\" value=\"Back to Search\" />\n";
					echo "</td> \n";
				echo "</tr>";
			echo "</table>";
			
		} else {
			
		$case_cid = strtolower($sent_cid);
		
		echo "<form name=\"editqna\" action=\"AST_comments_supedit_p4.php\" method=\"post\"> \n";
			echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" border = \"0\">\n";
				echo "<tr>\n";
					echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
						echo "<b>Campaign Record</b>\n";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td align=\"center\" bgcolor=\"#B9CBFD\" colspan = \"2\">\n";
						
						$tchk = mysql_query("SELECT count(*) AS count FROM information_schema.tables WHERE table_schema = 'wasiasterisk' AND table_name = 'questions_cid_".$case_cid."'")  or die(mysql_error());
							$rowchk = mysql_fetch_array($tchk);
							$tcount = $rowchk['count'];
							
						if ($tcount == 0) {
							
				echo "<tr>";
					echo "<td align=\"center\" bgcolor=\"#B9CBFD\" colspan = \"2\">\n";
						echo "Selected Campaign: <b>".$sent_cid."</b><p> \n";
						
						mysql_query("CREATE TABLE questions_cid_".$case_cid."(
							qid INT NOT NULL AUTO_INCREMENT, 
							PRIMARY KEY(qid),
							question VARCHAR(255), 
							autofail ENUM('Y','N') NOT NULL DEFAULT 'N',
							status VARCHAR(50) NOT NULL DEFAULT 'ACTIVE',
							seq_num INT(2) NOT NULL,
							created_by VARCHAR(50) NOT NULL,
							edited_by VARCHAR(50) NOT NULL,
							create_date VARCHAR(50) NOT NULL,
							edit_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)")
						or die(mysql_error()); 
						
						$result = mysql_query("SELECT * FROM questions_cid_default");
							$n = 1;
							while ($row = mysql_fetch_array($result)) {
								$defqid = $row['qid'];
								$defquestion = $row['question'];
								$defautofail = $row['autofail'];
								$defstatus = $row['status'];
								$defseq_num = $row['seq_num'];
								$defcreated_by = $row['created_by'];
								$defcreate_date = $row['create_date'];
								
								mysql_query("INSERT INTO questions_cid_".$case_cid." (qid, question, autofail, status, seq_num, created_by, edited_by, create_date, edit_date) VALUES('".$defqid."', '".$defquestion."', '".$defautofail."', '".$defstatus."', '".$defseq_num."', '".$defcreated_by."', '', '".$defcreate_date."', '' ) ") or die(mysql_error());  
							$n++;
							}

						echo "<meta http-equiv='REFRESH' content='0;url=AST_comments_supedit_p2.php?campaigns=".$case_cid."'> \n";
						
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td align=\"center\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
						echo "<input type =\"button\" onclick=\"location.href='AST_comments_supedit.php'\" value=\"Back to Search\" />\n";;
					echo "</td> \n";
				echo "</tr>";
			echo "</table>";
							
						} else {
						
						$result1 = mysql_query("SELECT campaign_id, campaign_name FROM vicidial_campaigns WHERE campaign_id = '$sent_cid'") or die(mysql_error());
							$row1 = mysql_fetch_array($result1);
							$camp_name = $row1['campaign_name'];
					
						echo "Selected Campaign: <b>".$sent_cid." - ".$camp_name."</b> \n";
					echo "</td>";
				echo "</tr>";
				
				echo "<tr>\n";
					echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
						echo "<b>Available Questions</b><i> <font color = \"red\"><br>NOTE: Add New Question Field(s) or Change Scoring System first BEFORE filling up the form. Editing a question renders the Current Question INACTIVE. Please check your  edits for accuracy before submitting.</font></i>\n";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td align=\"center\" bgcolor=\"#B9CBFD\" colspan = \"2\">\n";
							echo "<table width=\"100%\" cellpadding = \"5\" cellspacing = \"0\" align = \"center\" border = \"0\"> \n";
								echo "<tr bgcolor =\"#00468C\"> \n";
									echo "<td align = \"center\" valign = \"top\" width = \"25\"> \n";
										echo "<font size = \"-1\" color = \"white\"><b>No.</b></font> \n";
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo "<font size = \"-1\" color = \"white\"><b>Current Question</b></font> \n";
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo "<font size = \"-1\" color = \"white\"><b>New Question</b></font> \n";
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo "<font size = \"-1\" color = \"white\"><b>Active?</b></font> \n";
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo "<font size = \"-1\" color = \"white\"><b>Autofail?</b></font> \n";
									echo "</td> \n";
								echo "</tr> \n";
								
						$result2 = mysql_query("SELECT * FROM questions_cid_".$case_cid." WHERE status = 'ACTIVE' ORDER BY seq_num ASC") or die(mysql_error());
						$c = 1;
						while ($row2 = mysql_fetch_array($result2)) {
							
							if ($c % 2 == 0) {
								$bgcolor = "#9BB9FB";
							} else {
								$bgcolor = "#B9CBFD";
							}

							$qid = $row2['qid'];
							$seq_num = $row2['seq_num'];
							$question = $row2['question'];
							$autofail = $row2['autofail'];
							$status = $row2['status'];
								if ($status == "ACTIVE") {
									$stat = "Y";
								} else {
									$stat = "N";
								}
					
								echo "<tr bgcolor = ".$bgcolor."> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo $c." \n";
										echo "<input type=\"hidden\" value=\"".$seq_num."\" name=\"seq_num".$c."\" /> \n";
										echo "<input type=\"hidden\" value=\"".$qid."\" name=\"qid".$c."\" /> \n";
									echo "</td> \n";
									echo "<td align = \"justify\" valign = \"top\"> \n";
								
										echo $question." \n";
										echo "<input type=\"hidden\" value=\"".$question."\" name=\"question".$c."\" />\n";
									echo "</td> \n";
									echo "<td align = \"left\" valign = \"top\"> \n";
										echo "<TEXTAREA name=\"newq".$c."\" rows=\"1\" cols=\"55\" placeholder=\"Enter new question or leave blank for current question.\"></TEXTAREA> \n";
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo "<select name=\"newstat".$c."\"> \n";
											echo "<option value=\"".$stat."\" selected=\"selected\">".$stat."</option> \n";
											if ($stat == "Y") {
												echo "<option value=\"N\">N</option> \n";
											} else {
												echo "<option value=\"Y\">Y</option> \n";
											}
										echo "</select> \n";
									echo "</td> \n";
									echo "<td align = \"center\" valign = \"top\"> \n";
										echo "<select name=\"newaf".$c."\"> \n";
											echo "<option value=\"".$autofail."\" selected=\"selected\">".$autofail."</option> \n";
											if ($autofail == "Y") {
												echo "<option value=\"N\">N</option> \n";
											} else {
												echo "<option value=\"Y\">Y</option> \n";
											}
										echo "</select> \n";
									echo "</td> \n";
								echo "</tr> \n";
								
						$c++;
						}
						
						echo "</table> \n";

					echo "</td>";
				echo "</tr>";
				
				echo "<tr>";
					echo "<td align=\"left\" bgcolor =\"#00468C\">\n";
					
					$chkqs = mysql_query("SELECT MAX(seq_num) AS maxqs FROM questions_cid_".$case_cid."");
						$rowqs = mysql_fetch_array($chkqs);
						$maxqs = $rowqs['maxqs'];
						
						if ($maxqs < 10) {
							echo "<input type =\"button\" onclick=\"location.href='javascript:addq_pop()'\" value=\"Add New Question(s)\" /> \n";
						} else {
							echo "<input type =\"button\" onclick=\"location.href='javascript:addq_pop()'\" value=\"Add New Question(s)\" / disabled> \n";
						}
					
						//echo "<input type =\"button\" onclick=\"location.href='javascript:addq_pop()'\" value=\"Add New Question(s)\" /> \n";
						echo "<input type =\"button\" onclick=\"location.href='AST_comments_supedit_p5.php?campaigns=".$case_cid."'\" value=\"Edit Disabled Question(s)\" /> \n";
					echo "</td> \n";
					echo "<td align=\"left\" bgcolor =\"#00468C\">\n";
						echo "<input type=\"hidden\" value=\"".$case_cid."\" name=\"case_cid\" />\n";
						echo "<input type =\"button\" onclick=\"location.href='AST_comments_supedit_p2.php?campaigns=".$sent_cid."'\" value=\"Cancel\" />\n";
						echo "<input type =\"submit\" value=\"Submit\" />\n";
						echo "<input type =\"button\" onclick=\"location.href='AST_comments_supedit.php'\" value=\"Back to Search\" />\n";
						echo "<input type =\"reset\" value=\"Reset\" />\n";
					echo "</td> \n";
				echo "</tr>";
			echo "</table>";
		
		echo "<table width=\"100%\" cellpadding = \"5\" cellspacing = \"0\" align = \"center\" border = \"0\"> \n";
			echo "<tr>\n";
				echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan= \"2\">\n";
				echo "<font size = \"-1\"><b>NOTE:</b> If the \"Add New Question(s)\" button is disabled, you may have reached the maximum number to add new questions. Use the \"Edit Disabled Question(s)\" button to select an INACTIVE question and edit it instead. </font> \n";
				echo "<hr width = \"99%\"> \n";
				$result3 = mysql_query("SELECT scoring FROM vicidial_campaigns WHERE campaign_id = '$sent_cid'");
					$row3 = mysql_fetch_array($result3);
					$scoring = $row3['scoring'];
				
					if ($scoring == "PS") {
						$camp_scoring = "Point System (PS)";
						echo "<b>Scoring System Used: <a href=\"javascript:desc_pop()\">".$camp_scoring."</a></b>\n";
						echo "<input type =\"button\" onclick=\"location.href='javascript:changescore_pop()'\" value=\"Change Scoring System\" />\n";
					} elseif ($scoring == "AV") {
						$camp_scoring = "Averaging (AV)";
						echo "<b>Scoring System Used: <a href=\"javascript:desc2_pop()\">".$camp_scoring."</a></b>\n";
						echo "<input type =\"button\" onclick=\"location.href='javascript:changescore_pop()'\" value=\"Change Scoring System\" />\n";
					} else {
						$camp_scoring = "Percentile (PC)";
						echo "<b>Scoring System Used: <a href=\"javascript:desc3_pop()\">".$camp_scoring."</a></b>\n";
						echo "<input type =\"button\" onclick=\"location.href='javascript:changescore_pop()'\" value=\"Change Scoring System\" />\n";
					}
					
				echo "</td>\n";
			echo "</tr>\n";
			
		echo "</table>";
		echo "</form> \n";
						}	
		}
?>

<script language="JavaScript">
function changescore_pop() {
    var win = window.open("AST_comments_changescore.php?scoring=<?php echo $scoring; ?>&campaigns=<?php echo $sent_cid; ?>","","screenX=0,screenY=0,toolbar=0,location=0,status=0,menubar=0,scrollbars=1,resizable=0,width=520,height=220,top='+screen.availTop+',left='+screen.availLeft");
}

</script>
</table>
		<br><br>

</body>
</html>
