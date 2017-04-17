<?php

# Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#

header ("Content-type: text/html; charset=utf-8");

require("dbconnect.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];

if (isset($_GET["campaigns"]))				{$sent_cid=$_GET["campaigns"];}
	elseif (isset($_POST["campaigns"]))		{$sent_cid=$_POST["campaigns"];}
	
if (isset($_GET["addq"]))				{$sent_addq=$_GET["addq"];}
	elseif (isset($_POST["addq"]))		{$sent_addq=$_POST["addq"];}	

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
<title>VICIDIAL ADMIN: Add Questions</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">

<style>
body {
	font-family: Arial, Helvetica;
}

input[type="number"] {
    width: 50px;
}
</style>


</head>

<BODY BGCOLOR="white" marginheight="5" marginwidth="5" leftmargin="5" topmargin="5">
<TABLE WIDTH="100%" BGCOLOR="#D9E6FE" cellpadding="10" cellspacing="0">
	<TR BGCOLOR="#015B91">
		<TD ALIGN="LEFT">
			<FONT FACE="ARIAL,HELVETICA" COLOR=WHITE SIZE=2><B>VICIDIAL ADMIN</a>: Add Questions</font>
		</TD>
	</TR> 
	<TR BGCOLOR="#F0F5FE">
		<td align = "justify"> 
		
	<?php
		
	if ($sent_addq == "" ) {
			
		$case_cid = strtolower($sent_cid);
		
		$result = mysql_query("SELECT COUNT(*) AS count FROM questions_cid_".$case_cid." WHERE status = 'ACTIVE'");
			$row = mysql_fetch_array($result);
			$rcount = $row['count'];
			$newq = (10 - $rcount);
			
			if ($newq == 0) {
				echo "You already have a total of ".$rcount." active questions recorded. You have reached the maximum number of questions to use.<p> \n";
				echo "<center><INPUT TYPE=\"button\" VALUE=\"Cancel\" onClick=\"window.close()\"></center> \n";
			} elseif ($newq == 1) {
				echo "<form action =\"\" method=\"post\" onSubmit=\"if (this.addq.value == '') {return false;}\"> \n";
					echo "You already have a total of ".$rcount." active questions recorded. You are allowed only ".$newq." question left to add.<p> \n";
					echo "How many questions do you want to add:  <input type = \"number\" min = \"1\" max = \"".$newq."\" name = \"addq\" placeholder = \"1 - ".$newq."\" size = \"2\"> \n";
				echo "<input type = \"submit\" value = \"Add Questions\"> \n";
			echo "</form> \n";
			} else {
				echo "<form action =\"\" method=\"post\" onSubmit=\"if (this.addq.value == '') {return false;}\"> \n";
					echo "You already have a total of ".$rcount." active questions recorded. You are allowed only ".$newq." questions left to add.<p> \n";
					echo "How many questions do you want to add:  <input type = \"number\" min = \"1\" max = \"".$newq."\" name = \"addq\" placeholder = \"1 - ".$newq."\" size = \"2\"> \n";
					echo "<input type = \"submit\" value = \"Add Questions\"> \n";
				echo "</form> \n";
		echo "</tr> \n";
		echo "<TR BGCOLOR=\"#015B91\"> \n";
			echo "<td align=\"center\"> \n";
				echo "<INPUT TYPE=\"button\" VALUE=\"CANCEL\" onClick=\"window.close()\"> \n";
			echo "</td> \n";
		echo "</tr> \n";
echo "</table> \n";

			}	
			
	} else {
		
		$case_cid = strtolower($sent_cid);
		$tbl = "questions_cid_".$case_cid."";
		$user = $PHP_AUTH_USER;
		$currdt = date("Y-m-d h:m:s");
		$result = mysql_query("SELECT MAX(seq_num) FROM ".$tbl." ");
			$row = mysql_fetch_array($result);
			$msqn = $row['MAX(seq_num)']+1;
			$addcount = $sent_addq;
			for ($x = 1; $x <= $addcount; $x++) {
				
				$resultb = mysql_query("SELECT MAX(qid) AS maxqid FROM ".$tbl."");
					$rowb = mysql_fetch_array($resultb);
					$maxqid = $rowb['maxqid'];
					$addedqid = ($maxqid + 1);
				
				//echo "maxqid: ".$maxqid."<br>";
				$resultc = mysql_query("SELECT question FROM questions_cid_default WHERE seq_num = '".$msqn."'");
					$rowc = mysql_fetch_array($resultc);
					$defques = $rowc['question'];
				
				//echo "added qid: ".$addedqid."<br>";
				//echo "added question: ".$defques."<p>";
				
				mysql_query("INSERT INTO ".$tbl." (qid, question, autofail, status, seq_num, created_by, edited_by, create_date, edit_date) VALUES('', '', 'N', 'ACTIVE', '".$msqn++."', '".$user."', '' ,'".$currdt."', '') ") or die(mysql_error());
				mysql_query("UPDATE ".$tbl." SET question = '".$defques."' WHERE qid = '".$addedqid."'") or die(mysql_error());  
			}
			echo "Question Field(s) with default question(s) added.";
		
		echo "</td> \n";
	echo "</tr> \n";
	echo "<TR BGCOLOR=\"#015B91\"> \n";
			echo "<td align=\"center\"> \n";
				echo "<INPUT TYPE=\"button\" VALUE=\"Click Here to Continue Editing\" onClick=\"window.opener.location.reload();window.close();\"> \n";

			echo "</td> \n";
		echo "</tr> \n";
echo "</table> \n";
	}
		
				

		?>
		
		
