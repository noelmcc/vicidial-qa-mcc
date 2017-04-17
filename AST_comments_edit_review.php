<?php
### Ast_comments_edit.php
### 
### Copyright (C) 2008  Yiannos Katsirintakis <janokary@gmail.com>    www.publicissue.gr LICENSE: GPLv2
#### Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#
session_start();

header ("Content-type: text/html; charset=utf-8");

require("dbconnect.php");

include("recurl.php");		//update this with servers connected to this account


$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];

if (isset($_GET["agent"]))				{$sent_agent=$_GET["agent"];}
	elseif (isset($_POST["agent"]))		{$sent_agent=$_POST["agent"];}
	
if (isset($_GET["agentname"]))				{$sent_agentname=$_GET["agentname"];}
	elseif (isset($_POST["agentname"]))		{$sent_agentname=$_POST["agentname"];}

if (isset($_GET["campaigns"]))				{$sent_campaigns=$_GET["campaigns"];}
	elseif (isset($_POST["campaigns"]))		{$sent_campaigns=$_POST["campaigns"];}
	
if (isset($_GET["call_date"]))				{$sent_call_date=$_GET["call_date"];}
	elseif (isset($_POST["call_date"]))		{$sent_call_date=$_POST["call_date"];}
	
if (isset($_GET["comment_date"]))				{$sent_comment_date=$_GET["comment_date"];}
	elseif (isset($_POST["comment_date"]))		{$sent_comment_date=$_POST["comment_date"];}

if (isset($_GET["search"]))				{$post_search=$_GET["search"];}
	elseif (isset($_POST["search"]))		{$post_search=$_POST["search"];}
	
if (isset($_GET["usergroups"]))				{$post_usergroups=$_GET["usergroups"];}
	elseif (isset($_POST["usergroups"]))		{$post_usergroups=$_POST["usergroups"];}
	
if (isset($_GET["specialist"]))				{$post_specialist=$_GET["specialist"];}
	elseif (isset($_POST["specialist"]))		{$post_specialist=$_POST["specialist"];}

if (isset($_GET["uniqueid"]))				{$post_uniqueid=$_GET["uniqueid"];}
	elseif (isset($_POST["uniqueid"]))		{$post_uniqueid=$_POST["uniqueid"];}	
	
if (isset($_GET["lead_id"]))				{$post_lead_id=$_GET["lead_id"];}
	elseif (isset($_POST["lead_id"]))		{$post_lead_id=$_POST["lead_id"];}	
	
$PHP_AUTH_USER = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_USER);
$PHP_AUTH_PW = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_PW);

$STARTtime = date("U");
$TODAY = date("Y-m-d");

if (!isset($begin_date)) {$begin_date = $TODAY;}
if (!isset($end_date)) {$end_date = $TODAY;}
	
	//mcc_edits_start_12_07_2016 - changed access level from >7 to >6 to allow user level 7 (QA) to access scoresheet
	//$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 7 and view_reports='1';";
	$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 6 and view_reports='1';";
	//mcc_edits_end_12_07_2016 - changed access level from >7 to >6 to allow user level 7 (QA) to access scoresheet
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

	//start getting POST data from AST_comments_search.php mcc_edits_start_12_12_2016
	$datefrom = $_SESSION['datefrom'];
	$datefrommin = $_SESSION['datefrommin'];
	$defdatefrom = $_SESSION['defdatefrom'];
	$dateto = $_SESSION['dateto'];
	$currdt = $_SESSION['currdt'];
	$datetomin = $_SESSION['datetomin'];
	$campaigns = $_SESSION['campaigns'];
	$agents = $_SESSION['agents'];
	$usergroups = $_SESSION['usergroups'];
	$specialist = $_SESSION['specialist'];
	
	//test outputs
	
	/* echo "datefrom: ".$datefrom."<br>";
	echo "dateto: ".$dateto."<br>";
	echo "sent agent: ".$sent_agent."<br>";
	echo "sent agentname: ".$sent_agentname."<br>";
	echo "sent campaigns: ".$sent_campaigns."<br>";
	echo "sent call date: ".$sent_call_date."<br>";
	echo "sent comment date: ".$sent_comment_date."<br>";
	echo "post search: ".$post_search."<br>";
	echo "post usergroups: ".$post_usergroups."<br>";
	echo "post specialist: ".$post_specialist."<br>";
	echo "post uniqueid: ".$post_uniqueid."<br>";
	echo "post lead id: ".$post_lead_id."<br>"; */
	
	//end getting POST data from AST_comments_search.php mcc_edits_end_12_12_2016
	
	
?>
<html>
<head>
<title>VICIDIAL ADMIN: </title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<!-- css added by noel for audio player mcc_edits_start_12_05_2016 -->
<script src="styles/audio.min.js"></script>
<!-- mcc_edits_end_12_05_2016 -->

<style>
body {
	font-family: Arial, Helvetica;
}
</style>

<script language="JavaScript">
function history_pop(){
popup = window.open("AST_comments_edit_review_history.php?uniqueid=<? echo $post_uniqueid; ?>","","screenX=0,screenY=0,toolbar=0,location=0,status=0,menubar=0,scrollbars=1,resizable=0,width=1100,height=500");
}

</script>


</head>
<BODY BGCOLOR="white" marginheight="5" marginwidth="0" leftmargin="0" topmargin="5">
<CENTER>

<?php
//$stmta=("select * from vicidial_agent_comments where comment_date = '$sent_comment_date' ");
$stmta=("select * from vicidial_agent_comments where uniqueid = '$post_uniqueid' ");
	$rslta=mysql_query($stmta, $link);
	$rowa=mysql_fetch_array($rslta);
	$uniqueid = $rowa['uniqueid'];
	
	//echo "uniqueid: ".$uniqueid."<br>";
?>

<TABLE WIDTH=1024 BGCOLOR=#D9E6FE cellpadding="10" cellspacing="0">
	<TR BGCOLOR=#015B91>
		<TD ALIGN=LEFT>
			<? echo "<a href=\"./admin.php\">" ?><FONT FACE="ARIAL,HELVETICA" COLOR=WHITE SIZE=2><B>VICIDIAL ADMIN</a>: Comment Record  <? echo $uniqueid ?>
		</TD>
		<TD ALIGN=RIGHT>
			<FONT FACE="ARIAL,HELVETICA" COLOR=WHITE SIZE=2><B><? echo date("l F j, Y G:i:s A") ?>
		</TD>
	</TR>
	
<?php	
//start select if user is specialist (score and read-only) or admin (full edit rights)

$stmta1 = ("SELECT * FROM vicidial_users WHERE user = '$PHP_AUTH_USER' ");
	$rslta1 = mysql_query($stmta1, $link);
	$rowa1 = mysql_fetch_array($rslta1);
	$auth_level = $rowa1['user_level'];
	
if ($auth_level == "7") {			//if user level is specialist (7)
	
/* need script for qa evaluators here */

} elseif ($auth_level >= "8") {			//if user level is admin (8 and above)

	$chkuname = mysql_query("select full_name from vicidial_agent_comments where user = '$sent_agent' ");
	$rowuname = mysql_fetch_array($chkuname);
	$full_name = $rowuname['full_name'];
	
	echo "<TR BGCOLOR=\"#F0F5FE\">\n";
			echo "<TD ALIGN=LEFT> \n";
				echo "<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK ><B>Comment and Feedback Form for ".$full_name."</b> \n";
			echo "</TD>\n";
			echo "<TD ALIGN=right> \n";
				echo "<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK ><B>(Admin Level)</b> \n";
			echo "</TD>\n";
		echo "</TR>\n";
		echo "<TR>\n";
			echo "<TD ALIGN=LEFT COLSPAN=2>\n";

	echo "<div align=\"center\">\n";
		echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\">\n";
			echo "<tr>\n";
				echo "<td>\n";
					echo "<b>Agent</b> \n";
				echo "</td>\n";
				echo "<td>\n";
					echo "<b>Campaign ID</b> \n";
				echo "</td>\n";
				echo "<td>\n";
					echo "<b>Lead ID</b> \n";
				echo "</td>\n";
				echo "<td>\n";
					echo "<b>Call Date</b> \n";
				echo "</td>\n";
				echo "<td>\n";
					echo "<b>Comment Date</b> \n";
				echo "</td>\n";
				echo "<td>\n";
					echo "<b>Phone Number</b> \n";
				echo "</td>\n";
				echo "<td>\n";
					echo "<b>Specialist</b> \n";
				echo "</td>\n";
			echo "</tr>\n";
	
		$stmtb=("select * from vicidial_agent_comments where lead_id = '$post_lead_id' ");
		$rsltb=mysql_query($stmtb, $link);
		$rowb=mysql_fetch_array($rsltb);
		$campid = $rowb['campaign_id'];
		$agent_user = $rowb['user'];
		$sup_id = $rowb['supervisor_id'];
		$sup_cdate = $rowb['comment_date'];
		$call_status = $rowb['call_status'];
		$ingroup = $rowb['ingroup'];
		
		$chkpn = mysql_query("select phone_number from vicidial_list where lead_id = '$post_lead_id' ");
		$rowpn = mysql_fetch_array($chkpn);
		$phone_number = $rowpn['phone_number'];
	
			echo "<tr bgcolor='#9BB9FB'>\n";
				echo "<td>\n";
					echo $agent_user." \n";
				echo "</td>\n";
				echo "<td>\n";
					echo $sent_campaigns." \n";
				echo "</td>\n";
				echo "<td>\n";
					echo $post_lead_id." \n";
				echo "</td>\n";
				echo "<td>\n";
					echo $sent_call_date." \n";
				echo "</td>\n";
				echo "<td>\n";
					if (($sup_cdate == "(NULL)") || ($sup_cdate == "")) {
					echo "Not Reviewed \n";
				} else {
					echo $sup_cdate." \n";
				}
				echo "</td>\n";
				echo "<td>\n";
					echo $phone_number." \n";
				echo "</td>\n";
				echo "<td>\n";
					echo $sup_id." \n";
				echo "</td>\n";
			echo "</tr> \n";
		//audio player controls added by noel mcc_edits_start_12_05_2016	
			echo "<tr> \n";
				echo "<td colspan = \"7\"> \n";
	
				//get recording location by uniqueid
				$stmt2 = ("select location, filename from recording_log where lead_id  = '$post_lead_id' AND user = '$sent_agent' ");
					$rslt2=mysql_query($stmt2);
					$row2=mysql_fetch_array($rslt2);
					$recloc = $row2['location'];
					$filename = $row2['filename'];
					
					$recstrip = strstr($recloc, '/RECORDINGS');
					//$fullrec = $recurl2."".$recstrip;
					
					/* echo "location: ".$recloc."<br>";
					echo "filename: ".$filename."<br>"; */
				
					if (empty($filename)){
						echo "Recording Filename: <font color = \"red\"><b>NO RECORDING FOUND. You are not allowed to Evaluate this Agent.</b></font><br> \n";
						$norec = "1";
					} else {
						
						function file_exists_remote($url) {
							$curl = curl_init($url);
							curl_setopt($curl, CURLOPT_NOBODY, true);
							//Check connection only
							$result = curl_exec($curl);
							//Actual request
							$ret = false;
							if ($result !== false) {
								$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
								//Check HTTP status code
								if ($statusCode == 200) {
									$ret = true;   
								}
							}
							curl_close($curl);
							return $ret;
						}
						
						$chkfullrec = $recurl1."".$recstrip;
						$exist = file_exists_remote($chkfullrec);
						if($exist) {
							$fullrec = $recurl1."".$recstrip;
						} else {
							$fullrec = $recurl2."".$recstrip;
						}
						
						echo "Recording Filename: <b><a href = \"".$fullrec."\" style=\"text-decoration:none\">".$filename."-all.mp3</b></a> <i>(Right-click to download)</i><br><br> \n";
						echo "<audio src=\"".$fullrec."\" preload=\"auto\" /> \n";		//change audio src to reference from recording table when availalbe
					}
					//audio player controls added by noel mcc_edits_end_12_07_2016	
				
				echo "</td> \n";
			echo "</tr> \n";
		echo "</TABLE>\n";
	echo "</div> \n";
	
	//start select from user table to determine if display/function will be for qa or sup mcc_edits_start_12_09_2016
	$stmt2a = ("SELECT user, user_level FROM vicidial_users WHERE user = '$PHP_AUTH_USER' ");
		$rslt2a=mysql_query($stmt2a);
		$row2a=mysql_fetch_row($rslt2a);
		$auth_user = $row2a[0];
		$ulevel = $row2a[1];
	
	
		echo "<form action=\"AST_comments_edit_review_check.php?call_date=".$sent_call_date."&comment_date=".$sent_comment_date."&uniqueid=".$post_uniqueid."&user=".$sent_agent."&user_group=".$post_usergroups."&campaigns=".$sent_campaigns."&specialist=".$PHP_AUTH_USER."&search=".$post_search."\" method = \"post\" name = \"checkEvalForm\"> \n"; 		//form start
		echo "<table width = \"100%\" cellspacing = \"0\" cellpadding = \"5\" align=\"center\" border=\"0\"> \n";
			echo "<tr bgcolor = \"#B9CBFD\"> \n";
				echo "<td width=\"15\" valign=\"top\"> \n";
					echo "<b>No.</b> \n";
				echo "</td> \n";
				echo "<td width=\"40\" valign=\"top\"> \n";
					echo "<b>Score</b> \n";
				echo "</td> \n";
				echo "<td align = \"center\" valign=\"top\"> \n";
					echo "<b>Question</b> \n";
				echo "</td> \n";
				echo "<td align = \"center\" valign=\"top\"> \n";
					echo "<b>Autofail?</b> \n";
				echo "</td> \n";
				echo "<td align = \"center\" valign=\"top\"> \n";
					echo "<b>Review Date</b> \n";
				echo "</td> \n";
			echo "</tr> \n";
			
			//start loop of questions and score records for editing
			
			$cpid = strtolower($sent_campaigns);
			
			$result1 = mysql_query("SELECT * FROM questions_cid_".$cpid." WHERE status = 'ACTIVE'") or die(mysql_error());
				$c = 1;
				while ($row1 = mysql_fetch_array($result1)) {
				
				if ($c % 2 == 0) {
					$bgcolor = "#B9CBFD";
				} else {
					$bgcolor = "#9BB9FB";
				}

			echo "<tr bgcolor = \"".$bgcolor."\">\n";
				echo "<td width=\"15\" align = \"center\" valign=\"top\"> \n";
					echo "<b>".$c."</b> \n";
				echo "</td> \n";
				echo "<td width=\"40\" align = \"center\" valign=\"top\"> \n";
					$stmt2b = ("SELECT comment_date,rate0".$c." FROM vicidial_agent_comments WHERE comment_date = '$sent_comment_date' ") or die(mysql_error());
						$rslt2b = mysql_query($stmt2b) or die(mysql_error());
						$row2b = mysql_fetch_row($rslt2b);
						$commdate = $row2b[0];
						$rate = $row2b[1];
						
					if ($rate == "") {
						echo "<select name=\"rate".$c."\"> \n";
							echo "<option value=\"\" selected=\"selected\"></option> \n";
							echo "<option value=\"PASS\">PASS</option> \n";
							echo "<option value=\"FAIL\">FAIL</option> \n";
							echo "<option value=\"NA\">N/A</option> \n";
						echo "</select> \n";
					} elseif ($rate == "FAIL") {
						echo "<select name=\"rate".$c."\"> \n";
							echo "<option value=\"FAIL\" selected=\"selected\">".$rate."</option> \n";
							echo "<option value=\"PASS\">PASS</option> \n";
							echo "<option value=\"NA\">N/A</option> \n";
						echo "</select> \n";
					} elseif ($rate == "NA") {
						echo "<select name=\"rate".$c."\"> \n";
							echo "<option value=\"NA\" selected=\"selected\">".$rate."</option> \n";
							echo "<option value=\"PASS\">PASS</option> \n";
							echo "<option value=\"FAIL\">FAIL</option> \n";
						echo "</select> \n";
					} else {
						echo "<select name=\"rate".$c."\"> \n";
							echo "<option value=\"PASS\" selected=\"selected\">".$rate."</option> \n";
							echo "<option value=\"FAIL\">FAIL</option> \n";
							echo "<option value=\"NA\">N/A</option> \n";
						echo "</select> \n";
					}
					
				echo "</td> \n";
				echo "<td align = \"justify\" valign=\"top\"> \n";
					$sqnchk = ("SELECT * FROM questions_cid_".$cpid." WHERE seq_num = '$c' AND status = 'ACTIVE' ");
						$rsltchk = mysql_query($sqnchk);
						$rowchk=mysql_fetch_array($rsltchk);
						$sqnstatus = $rowchk['status'];
						
						if (empty($sqnstatus)) {
							$sqn = ($c + 1);
							$stmt2c = ("SELECT * FROM questions_cid_".$cpid." WHERE seq_num = '$sqn' AND status = 'ACTIVE' ");
								$rslt2c=mysql_query($stmt2c);
								$row2c=mysql_fetch_array($rslt2c);
								$q = $row2c['question'];
								$af = $row2c['autofail'];
						} else {
							$stmt2c = ("SELECT * FROM questions_cid_".$cpid." WHERE seq_num = '$c' AND status = 'ACTIVE' ");
								$rslt2c=mysql_query($stmt2c);
								$row2c=mysql_fetch_array($rslt2c);
								$q = $row2c['question'];
								$af = $row2c['autofail'];
						}
						
					echo $q." \n";
				echo "</td> \n";
				echo "<td align = \"center\" valign=\"top\"> \n";
					if ($af == "Y") {
						echo "<font color = \"red\"><b>".$af."</b></font> \n";
						echo "<input type=\"hidden\" name=\"af".$c."\" value=\"".$af."\" > \n";
					} else {
						echo $af."\n";
						echo "<input type=\"hidden\" name=\"af".$c."\" value=\"".$af."\" > \n";
					}
					
				echo "</td> \n";
				echo "<td align = \"center\" valign=\"top\"> \n";
				
				if (($commdate == "") || ($commdate == "(NULL)")) {
					$commdate == "";
				} else {
					echo $commdate." \n";
				}
					
				echo "</td> \n";
			echo "</tr> \n";	
				
				$c++;
				
				}
			
			//end loop of questions and score records for editing
			
			echo "<tr> \n";
				echo "<td align = \"left\" valign=\"top\" colspan=\"3\"> \n";
					$stmt2j = ("SELECT fscore FROM vicidial_agent_comments WHERE comment_date = '$sent_comment_date' ");
						$rslt2j=mysql_query($stmt2j);
						$row2j=mysql_fetch_row($rslt2j);
						$fscore = $row2j[0];
					
					if ($fscore == "FAIL") {
						echo "<font size = \"4\"><b> FINAL SCORE: <font color = \"red\">".$fscore."</b></font></font> \n";
						echo "<input type=\"hidden\" name=\"final_score\" value=\"".$fscore."\" > \n";
					} else {
						echo "<font size = \"4\"><b> FINAL SCORE: <font color = \"green\">".$fscore."</b></font></font> \n";
						echo "<input type=\"hidden\" name=\"final_score\" value=\"".$fscore."\" > \n";
					}
					
				echo "</td>";
				echo "<td align = \"right\" valign=\"top\" colspan=\"2\"> \n";
					
					$stmt2j1 = ("SELECT scoring FROM vicidial_campaigns WHERE campaign_id = '".$sent_campaigns."' ");
						$rslt2j1=mysql_query($stmt2j1);
						$row2j1 = mysql_fetch_array($rslt2j1);
						$camp_score = $row2j1['scoring'];
						
					if ($camp_score == "PS")	 {
						$setscsys = "Point System (PS)";
					} elseif ($camp_score == "AV")	 {
						$setscsys = "Averaging System (AV)";
					} else {
						$setscsys = "Percentile System (PC)";
					}
						
					echo "<b>Scoring System: ".$setscsys."</b> \n";
					echo "<input type=\"hidden\" name=\"scoring\" value=\"".$camp_score."\" > \n";
					echo "<input type=\"hidden\" name=\"datefrom\" value=\"".$datefrom."\" > \n";
					echo "<input type=\"hidden\" name=\"dateto\" value=\"".$dateto."\" > \n";
					echo "<input type=\"hidden\" name=\"lead_id\" value=\"".$post_lead_id."\" > \n";
					echo "<input type=\"hidden\" name=\"call_status\" value=\"".$call_status."\" > \n";
					echo "<input type=\"hidden\" name=\"ingroup\" value=\"".$ingroup."\" > \n";
				
				echo "</td>";
			echo "</tr> \n";
			echo "<tr bgcolor = \"#B9CBFD\"> \n";
				echo "<td align = \"center\" valign=\"top\" colspan=\"5\"> \n";
					echo "<b>Comment / Feedback (Read-Only if Filled)</b> \n";
				echo "</td>\n";
			echo "</tr> \n";
			echo "<tr> \n";
				echo "<td align = \"center\" valign=\"top\" colspan=\"5\"> \n";
					$stmt2k = ("SELECT comments FROM vicidial_agent_comments WHERE comment_date = '$sent_comment_date' AND uniqueid = '$uniqueid' ");
						$rslt2k=mysql_query($stmt2k);
						$row2k=mysql_fetch_array($rslt2k);
						$commtext = $row2k['comments'];
						
				if (empty($commtext)) {
					echo "<TEXTAREA name=\"comments\" rows=\"3\" cols=\"122\" placeholder = \"Don't forget to enter your evaluation comments/feedback.\">".$commtext."</TEXTAREA> \n";
				echo "</td>\n";
			echo "</tr> \n";
				} else {
					echo "<TEXTAREA name=\"comments\" rows=\"3\" cols=\"122\" readonly>".$commtext."</TEXTAREA> \n";
				echo "</td>\n";
			echo "</tr> \n";
				}
			
			echo "<tr bgcolor = \"#B9CBFD\"> \n";
				echo "<td align = \"center\" valign=\"top\" colspan=\"5\"> \n";
					echo "<b>Admin Edit Notes <font color = \"red\"><i>(Required)</i></font></b> \n";
				echo "</td>\n";
			echo "</tr> \n";
			echo "<tr> \n";
				echo "<td align = \"center\" valign=\"top\" colspan=\"5\"> \n";
					$stmt2k2 = ("SELECT note FROM vicidial_admin_notes WHERE create_date = '$sent_comment_date' ");
						$rslt2k2 = mysql_query($stmt2k2);
						$row2k2 = mysql_fetch_array($rslt2k2);
						$note = $row2k2['note'];
				if ($norec == "1") {
					if (empty($note)) {
						echo "<TEXTAREA name=\"admin_note\" rows=\"3\" cols=\"122\" placeholder = \"No Admin Edit Notes entered.\" readonly></TEXTAREA> \n";
					} else {
						echo "<TEXTAREA name=\"admin_note\" rows=\"3\" cols=\"122\" readonly>".$note." *** notated on ".$sent_comment_date." ***</TEXTAREA> \n";
					}
				} else {
					if (empty($note)) {
						echo "<TEXTAREA name=\"admin_note\" rows=\"3\" cols=\"122\" placeholder = \"No Admin Edit Notes entered.\"></TEXTAREA> \n";
					} else {
						echo "<TEXTAREA name=\"admin_note\" rows=\"3\" cols=\"122\">".$note." *** notated on ".$sent_comment_date." ***</TEXTAREA> \n";
					}
				}
				echo "</td>\n";
			echo "</tr> \n";
			
			echo "<tr bgcolor = \"#015B91\"> \n";
				echo "<td align = \"center\" valign=\"top\" colspan=\"5\"> \n";
				
				if ($norec == "1") {
					echo "<input type =\"button\" onclick=\"location.href='javascript:history_pop()'\" value=\"View Edit History\" /> \n";
					
					//echo "<input type =\"button\" onclick=\"location.href='AST_comments_user.php?agentid=".$sent_agent."&campaigns=".$sent_campaigns."&usergroups=".$post_usergroups."&comment_date=".$sent_comment_date."&search=".$post_search."'\" value=\"Go to Review List\" /> \n";
					
					echo "<input type =\"button\" onclick=\"location.href='AST_comments_list.php?agentid=".$sent_agent."&campaigns=".$sent_campaigns."&usergroups=".$post_usergroups."&search=".$post_search."'\" value=\"Go to User List\" />\n";
					
					echo "<input type =\"button\" onclick=\"location.href='AST_comments_search_reset.php'\" value=\"Back to Search\" />\n";
				} else {
					echo "<input type=\"submit\" name=\"subpopup\" value=\"Submit Edits\" onclick=\"checkEvalForm.target='POPUPW'; POPUPW = window.open('','POPUPW','width=520,height=220');\"> \n";
					
					echo "<input type =\"reset\" value=\"Reset Scoresheet\" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; \n";
					
					echo "<input type =\"button\" onclick=\"location.href='javascript:history_pop()'\" value=\"View Edit History\" /> \n";
					
					//echo "<input type =\"button\" onclick=\"location.href='AST_comments_user.php?agent=".$sent_agent."&fullname=".$sent_agentname."&campaigns=".$sent_campaigns."&usergroups=".$post_usergroups."&comment_date=".$sent_comment_date."&call_date=".$sent_call_date."&search=".$post_search."'\" value=\"Go to Review List\" /> \n";
					
					echo "<input type =\"button\" onclick=\"location.href='AST_comments_list.php?agentid=".$agent_user."&agentname=".$sent_agentname."&campaigns=".$sent_campaigns."&usergroups=".$post_usergroups."&search=".$post_search."'\" value=\"Go to User List\" />\n";
					
					echo "<input type =\"button\" onclick=\"location.href='AST_comments_search_reset.php'\" value=\"Back to Search\" />\n";
				}
				echo "</td>\n";
			echo "</tr> \n";
		echo "</table> \n";
		echo "</form> \n";		//form end
		
}


//start setting SESSION data
	//$_SESSION['datefrom'] = $datefrom;
	//$_SESSION['datefrommin'] = $datefrommin;
	//$_SESSION['defdatefrom'] = $defdatefrom;
	//$_SESSION['dateto'] = $dateto;
	//$_SESSION['currdt'] = $currdt;
	//$_SESSION['datetomin'] = $datetomin;
	$_SESSION['dateto'] = $dateto;
	$_SESSION['campaigns'] = $sent_campaigns;
	$_SESSION['agentid'] = $agent_user;
	$_SESSION['agentname'] = $sent_agentname;
	$_SESSION['usergroups'] = $post_usergroups;
	$_SESSION['specialist'] = $specialist;
	$_SESSION['call_date'] = $sent_call_date;
	$_SESSION['lead_id'] = $post_lead_id;
	$_SESSION['call_status'] = $call_status;
	$_SESSION['ingroup'] = $ingroup;
	$_SESSION['search'] = $post_search;
	
	//end setting SESSION data
	
	/* echo "datefrom: ".$datefrom."<br>";
	echo "dateto: ".$dateto."<br>";
	echo "campaigns: ".$_SESSION['campaigns']."<br>";
	echo "sent_agent: ".$_SESSION['agentid']."<br>";
	echo "sent_agentname: ".$_SESSION['agentname']."<br>";
	echo "post_usergroups: ".$_SESSION['usergroups']."<br>";
	echo "specialist: ".$_SESSION['specialist']."<br>";
	echo "comment_date: ".$_SESSION['comment_date']."<br>";
	echo "call_date: ".$_SESSION['call_date']."<br>";
	echo "lead_id: ".$_SESSION['lead_id']."<br>";
	echo "call_status: ".$_SESSION['call_status']."<br>";
	echo "ingroup: ".$_SESSION['ingroup']."<br>";
	echo "post_search: ".$_SESSION['search']."<br>"; */

	
?>
	
	<!-- javascript for audio player mcc_edits_start_12_05_2016 -->
	<script>
	audiojs.events.ready(function() {
		var as = audiojs.createAll();
	});
	</script>
	<!-- javascript for audio player mcc_edits_end_12_05_2016 -->
	
	</body>
</html>
