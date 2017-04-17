<?php
### Ast_comments_edit.php
### 
### Copyright (C) 2008  Yiannos Katsirintakis <janokary@gmail.com>    www.publicissue.gr LICENSE: GPLv2
#### Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#
session_start();

header ("Content-type: text/html; charset=utf-8");

require("dbconnect.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];

if (isset($_GET["uniqueid"]))				{$uniqueid=$_GET["uniqueid"];}
	elseif (isset($_POST["uniqueid"]))		{$uniqueid=$_POST["uniqueid"];}
	
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
	
	//echo "DF datefrom: ".$datefrom."<br>";
	//echo "DFm datefrommin: ".$datefrommin."<br>";
	//echo "dDF defdatefrom: ".$defdatefrom."<br>";
	//echo "DT dateto: ".$dateto."<br>";
	//echo "CD currdt: ".$currdt."<br>";
	//echo "DTm datetomin: ".$datetomin."<br>";
	//echo "CAMP campaigns: ".$campaigns."<br>";
	//echo "AG agents: ".$agents."<br>";
	//echo "UG usergroups: ".$usergroups."<br>";
	//echo "SP specialist: ".$specialist."<br>";
	
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
			<B><? echo "<a href=\"./admin.php\" style=\"text-decoration:none\">" ?><FONT COLOR="WHITE" >VICIDIAL ADMIN</a>: Comment Record  <? echo $uniqueid ?></b></font>
		</TD>
		<TD ALIGN="RIGHT">
			<FONT COLOR="WHITE"><B><? echo date("l F j, Y G:i:s A") ?></b>
		</TD>
	</TR>
	
<?php	
	
echo "<TR BGCOLOR=\"#F0F5FE\">\n";
		echo "<TD ALIGN=\"LEFT\" COLSPAN=\"2\"><FONT COLOR=\"BLACK\" ><B> &nbsp; Comment and Feedback Form (Evaluation)</b> \n";
		echo "</TD>\n";
	echo "</TR>\n";
	echo "<TR>\n";
		echo "<TD ALIGN=\"LEFT\" COLSPAN=\"2\">\n";

echo "<div align=\"center\">\n";
	echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\">\n";
		echo "<tr>\n";
			echo "<td>\n";
				echo "<b>Campaign ID</b> \n";
			echo "</td>\n";
			echo "<td align=center>\n";
				echo "<b>Lead ID</b> \n";
			echo "</td>\n";
			echo "<td align=center>\n";
				echo "<b>Call Date</b> \n";
			echo "</td>\n";
			echo "<td align=center>\n";
				echo "<b>Phone Number</b> \n";
			echo "</td>\n";
			echo "<td align=center>\n";
				echo "<b>Agent ID</b> \n";
			echo "</td>\n";
			echo "<td align=right>\n";
				echo "<b>User Group</b> \n";
			echo "</td>\n";
		echo "</tr>\n";

	$stmt=("select campaign_id,lead_id,call_date,phone_number,user,user_group from  vicidial_log where uniqueid = '$uniqueid' ");
	$rslt=mysql_query($stmt, $link);
	$row=mysql_fetch_row($rslt);
	$campid = $row[0];
	
	
		echo "<tr bgcolor='#9BB9FB'>\n";
			echo "<td>\n";
				echo $row[0]."</td>\n";
			echo "<td align=center>\n";
				echo $row[1]."</td>\n";
			echo "<td align=center>\n";
				echo $row[2]."</td>\n";
			echo "<td align=center>\n";
				echo $row[3]."</td>\n";
			echo "<td align=center>\n";
				echo $row[4]."</td>\n";
			echo "<td align=right>\n";
				echo $row[5]."</td>\n";
		echo "</tr> \n";
	//audio player controls added by noel mcc_edits_start_12_05_2016	
		echo "<tr> \n";
			echo "<td colspan = \"6\"> \n";
	
			//get recording location by uniqueid
			$stmt2 = ("select location, filename from recording_log where vicidial_id = '$uniqueid' ");
				$rslt2=mysql_query($stmt2);
				$row2=mysql_fetch_row($rslt2);
				$recloc = $row2[0];
				$filename = $row2[1];
				
				if (empty($filename)){
					echo "Recording Filename: <b>NO RECORDING</b><br> \n";
					echo "<center><font size = \"5\" color = \"red\"><b>WARNING!! No recorded call(s) found. Grading this Agent is not allowed.</b></font></center><br> \n";
				} else {
					echo "Recording Filename: <b><a href = \"http://v26.mycallcloud.com/RECORDINGS/MP3/".$filename."-all.mp3\" style=\"text-decoration:none\">".$filename."-all.mp3</b></a> <i>(Right-click to download)</i><br><br> \n";
					echo "<audio src=\"http://v26.mycallcloud.com/RECORDINGS/MP3/".$filename."-all.mp3\" preload=\"auto\" /> \n";		//change audio src to reference from recording table when availalbe
				}
				//audio player controls added by noel mcc_edits_end_12_07_2016	
				
			echo "</td> \n";
		echo "</tr> \n";
	echo "</TABLE>\n";
echo "</div> \n";
//
//if (isset($_POST['submitted'])) { 
//foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); } 

// $sql = "UPDATE `vicidial_log` SET  `supervisor` =  '$PHP_AUTH_USER' ,  `comments2` =  '{$_POST['comments']}'    WHERE `uniqueid` = '$uniqueid' "; 		//original edit
//$sql = "insert into vicidial_agent_comments (uniqueid,comment_date,supervisor_id,comments,rate01,rate02,rate03,rate04,rate05,rate06,rate07,rate08) values ('$uniqueid',now(),'$PHP_AUTH_USER','{$_POST['comments']}','{$_POST['rate01']}','{$_POST['rate02']}','{$_POST['rate03']}','{$_POST['rate04']}','{$_POST['rate05']}','{$_POST['rate06']}','{$_POST['rate07']}','{$_POST['rate08']}') ";

//mysql_query($sql) or die(mysql_error()); 
//echo (mysql_affected_rows()) ? "Record Edited.<br />" : "Nothing changed. <br />"; 
//} 
?>
<!-- javascript for audio player mcc_edits_start_12_05_2016 -->
<script>
  audiojs.events.ready(function() {
    var as = audiojs.createAll();
  });
</script>
<!-- javascript for audio player mcc_edits_end_12_05_2016 -->

<?php

//show all comments for uniqueid mcc_start_edits_12_09_2016

$stmt2b = ("SELECT * FROM vicidial_agent_comments WHERE uniqueid = '$uniqueid' ");
	$rslt2b=mysql_query($stmt2b);

$stmt2b1 = ("SELECT comments FROM vicidial_agent_comments WHERE uniqueid = '$uniqueid' ");
	$rslt2b1=mysql_query($stmt2b1);
	$row2b1=mysql_fetch_array($rslt2b1);
	$commcheck = $row2b1['comments'];

if (empty($filename)) {

echo "<table width = \"100%\" cellspacing = \"0\" cellpadding = \"3\" align=\"center\" border=\"0\"> \n";
	echo "<tr bgcolor = \"#B9CBFD\"> \n";
		echo "<td valign = \"top\" align = \"center\" colspan= \"6\"> \n";
			echo "<input type =\"button\" onclick=\"location.href='AST_comments_user.php?agent=".$row[4]."'\" value=\"Back to Review List\" /> \n";
			echo "<input type =\"button\" onclick=\"location.href='AST_comments_list.php'\" value=\"Back to User List\" />\n";
			echo "<input type =\"button\" onclick=\"location.href='AST_comments_search_reset.php'\" value=\"Back to Search\" />\n";
		echo "</td> \n";
	echo "</tr> \n";
echo "</table> \n";	
	
} elseif  (!empty($commcheck)) {

echo "<table width = \"100%\" cellspacing = \"0\" cellpadding = \"3\" align=\"center\" border=\"1\"> \n";
	echo "<tr bgcolor = \"#B9CBFD\"> \n";
		echo "<td width =\"35\" valign = \"top\" align = \"center\"> \n";
			echo "<b>No.</b> \n";
		echo "</td> \n";
		echo "<td width =\"55\" valign = \"top\" align = \"center\"> \n";
			echo "<b>Score</b> \n";
		echo "</td> \n";
		echo "<td width =\"150\" valign = \"top\" align = \"center\"> \n";
			echo "<b>Comment Date</b> \n";
		echo "</td> \n";
		echo "<td valign = \"top\" align = \"center\"> \n";
			echo "<b>Comments/Feedback</b> \n";
		echo "</td> \n";
		echo "<td  width =\"105\" valign = \"top\" align = \"center\"> \n";
			echo "<b>Reviewed By</b> \n";
		echo "</td> \n";
		echo "<td  width =\"110\" valign = \"top\" align = \"center\"> \n";
			echo "<b>Action</b> \n";
		echo "</td> \n";
	echo "</tr> \n";
	
	$count = 1;
	while ($row2b = mysql_fetch_array($rslt2b)) {
		$clogid = $row2b['comments_log_id'];
		$commdate = $row2b['comment_date'];
		$comm = $row2b['comments'];
		$fscore = $row2b['fscore'];
		$supid = $row2b['supervisor_id'];
		
	echo "<tr> \n";
		echo "<td valign = \"top\" align = \"center\"> \n";
			echo $count." \n";
		echo "</td> \n";
		echo "<td width =\"55\" valign = \"top\" align = \"center\"> \n";
			if ($fscore == "FAIL") {
				echo "<font color = \"red\"><b>".$fscore."</b></font> \n";
			} else {
				echo $fscore." \n";
			}
		echo "</td> \n";
		echo "<td valign = \"top\" align = \"center\"> \n";
			echo $commdate." \n";
		echo "</td> \n";
		echo "<td valign = \"top\" align = \"justify\"> \n";
			echo ucfirst($comm)." \n";
		echo "</td> \n";
		echo "<td valign = \"top\" align = \"center\"> \n";
			echo $supid." \n";
		echo "</td> \n";
		echo "<td valign = \"top\" align = \"center\"> \n";
			echo "<a href=\"AST_comments_edit_review.php?clogid=".$clogid."\">Review Record</a> \n";
		echo "</td> \n";
	echo "</tr> \n";

		$count++;
	}
	
	echo "<tr bgcolor = \"#B9CBFD\"> \n";
		echo "<td valign = \"top\" align = \"center\" colspan= \"6\"> \n";
			echo "<input type =\"button\" onclick=\"location.href='AST_comments_user.php?agent=".$row[4]."'\" value=\"Back to Review List\" /> \n";
			echo "<input type =\"button\" onclick=\"location.href='AST_comments_list.php'\" value=\"Back to User List\" />\n";
			echo "<input type =\"button\" onclick=\"location.href='AST_comments_search_reset.php'\" value=\"Back to Search\" />\n";
		echo "</td> \n";
	echo "</tr> \n";
echo "</table> \n";	

} else { 			//if recording is available but no comment yet - auto open scorecard for scoring

$case_cid = strtolower($campid);

$stmt2b2 = ("SELECT question, autofail FROM questions_cid_".$case_cid." WHERE status = 'ACTIVE' ORDER BY seq_num ASC");
	$rslt2b2=mysql_query($stmt2b2);
	
$stmt2b3 = ("SELECT user,user_group FROM vicidial_log WHERE uniqueid = '".$uniqueid."' ");
	$rslt2b3=mysql_query($stmt2b3);
	$row2b3 = mysql_fetch_array($rslt2b3);
	$agent_id = $row2b3['user'];
	$user_group = $row2b3['user_group'];

$stmt2b4 = ("SELECT scoring FROM vicidial_campaigns WHERE campaign_id = '".$campid."' ");
	$rslt2b4=mysql_query($stmt2b4);
	$row2b4 = mysql_fetch_array($rslt2b4);
	$scoring = $row2b4['scoring'];	
	
echo "<form action=\"AST_comments_edit_check.php\" method=\"POST\" name = \"EvalForm\">  \n";
echo "<table width = \"100%\" cellspacing = \"0\" cellpadding = \"5\" align=\"center\" border=\"0\"> \n";	
	
	$c = 1;
	while ($row2b2=mysql_fetch_array($rslt2b2)) {
		$qn = $row2b2['question'];
		$af = $row2b2['autofail'];
		echo "<input type=\"hidden\" name=\"af".$c."\" value=\"".$af."\" /> \n";
		
		if ($c % 2 == 0) {
			$bgcolor = "#9BB9FB";
		} else {
			$bgcolor = "#B9CBFD";
		}
		
	echo "<tr bgcolor = ".$bgcolor."> \n";	
		echo "<td width=\"15\" valign=\"top\" align= \"center\"> \n";
			echo "<b>".$c."</b> \n";
		echo "</td> \n";
		echo "<td width=\"120\" valign=\"top\"> Score:  \n";
			echo "<select name=\"rate0".$c."\"> \n";
				echo "<option value=\"noscore\" selected=\"selected\">Select</option> \n";
				echo "<option value=\"PASS\">PASS</option> \n";
				echo "<option value=\"FAIL\">FAIL</option> \n";
			echo "</select> \n";
		echo "</td> \n";
		echo "<td valign=\"top\"> \n";
			echo $qn." ";
			
		if ($af == "Y") {
			echo "<font color = \"red\"><b><i>AUTOFAIL!!!</b></font> \n";
		} else {
			echo " \n";
		}
			
		echo "</td> \n";
	echo "</tr> \n";
	
	$c++;	
	}
	
	echo "<tr> \n";
		echo "<td colspan=\"3\"> \n";
			echo "<hr> \n";
			
			if ($scoring == "PS") {
				$camp_scoring = "Point System (PS)";
				echo "<b>Scoring System Used: <a href=\"javascript:desc_pop()\">".$camp_scoring."</a></b>\n";
			} elseif ($scoring == "AV") {
				$camp_scoring = "Averaging (AV)";
				echo "<b>Scoring System Used: <a href=\"javascript:desc2_pop()\">".$camp_scoring."</a></b>\n";
			} else {
				$camp_scoring = "Percentile (PC)";
				echo "<b>Scoring System Used: <a href=\"javascript:desc3_pop()\">".$camp_scoring."</a></b>\n";
			}
			
		echo "</td> \n";
	echo "</tr> \n";
	echo "<tr> \n";
		echo "<td colspan=\"3\"> \n";
			echo "<hr> \n";
			echo "<b>COMMENTS/FEEDBACK:</b> \n";
		echo "</td> \n";
	echo "</tr> \n";
	echo "<tr> \n";
		echo "<td align=\"center\" colspan=\"3\"> \n";
			echo "<TEXTAREA name=\"comments\" rows=\"3\" cols=\"120\" placeholder=\"Enter comments/feedback about the call.\"></TEXTAREA> \n";
		echo "</td> \n";
	echo "</tr> \n";
	echo "<tr bgcolor = \"#B9CBFD\"> \n";
		echo "<td colspan = \"3\" align=\"center\"> \n";
			echo "<input type=\"hidden\" name=\"campaign_id\" value=\"".$campid."\" /> \n";
			echo "<input type=\"hidden\" name=\"uniqueid\" value=\"".$uniqueid."\" /> \n";
			echo "<input type=\"hidden\" name=\"user\" value=\"".$agent_id."\" /> \n";
			echo "<input type=\"hidden\" name=\"usergroup\" value=\"".$user_group."\" /> \n";
			echo "<input type=\"hidden\" name=\"supid\" value=\"".$PHP_AUTH_USER."\" /> \n";
			echo "<input type=\"hidden\" name=\"scoring\" value=\"".$scoring."\" /> \n";
			echo "<input type=\"submit\" name=\"subpopup\" value=\"Submit Evaluation\" onclick=\"EvalForm.target='POPUPW'; POPUPW = window.open('','POPUPW','width=520,height=200');\"> \n";
			echo "<input type =\"button\" onclick=\"location.href='AST_comments_user.php?agent=".$row[4]."'\" value=\"Back to Review List\" /> \n";
			echo "<input type =\"button\" onclick=\"location.href='AST_comments_list.php'\" value=\"Back to User List\" /> \n";
			echo "<input type=\"reset\" value=\"Reset Form\" /> \n";
			echo "<input type =\"button\" onclick=\"location.href='AST_comments_search_reset.php'\" value=\"Back to Search\" />\n";
		echo "</td> \n";
	echo "</tr> \n";
echo "</table> \n";
echo "</form> \n";	
	
}
	
	
	//start setting SESSION data
	$_SESSION['datefrom'] = $datefrom;
	$_SESSION['datefrommin'] = $datefrommin;
	$_SESSION['defdatefrom'] = $defdatefrom;
	$_SESSION['dateto'] = $dateto;
	$_SESSION['currdt'] = $currdt;
	$_SESSION['datetomin'] = $datetomin;
	$_SESSION['dateto'] = $dateto;
	$_SESSION['campaigns'] = $campaigns;
	$_SESSION['agents'] = $agents;
	$_SESSION['usergroups'] = $usergroups;
	$_SESSION['specialist'] = $specialist;
	//end setting SESSION data
	

?>


</body>
</html>

