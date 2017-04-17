<?php
### AST_comments_reports_list_year.php
### 
### Special thanks to Yiannos Katsirintakis <janokary@gmail.com> for the base code.    www.publicissue.gr LICENSE: GPLv2
#### Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#
# 
# edited 01-09-2017 noel cruz noel@mycallcloud.com

session_start();

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
	
	$currdt = date("Y-m-d");

	//start getting POST data from AST_comments_reports_pick.php
	
	if (empty($_POST['post_search'])) {$post_search = $_SESSION['post_search'];
	} else {$post_search = $_POST['post_search'];}
	
	if (empty($_POST['year'])) {$year = $_SESSION['year'];
	} else {$year = $_POST['year'];}

	if (empty($_POST['campaigns'])) {$campaigns = $_SESSION['campaigns'];
	} else {$campaigns = $_POST['campaigns'];}

	if (empty($_POST['agentid'])) {$agentid = $_SESSION['agentid'];
	} else {$agentid = $_POST['agentid'];}

	if (empty($_POST['agentname'])) {$agentname = $_SESSION['agentname'];
	} else {$agentname = $_POST['agentname'];}
	
	if (empty($_POST['usergroups'])) {$usergroups = $_SESSION['usergroups'];
	} else {$usergroups = $_POST['usergroups'];}

	if (empty($_POST['specialist'])) {$specialist = $_SESSION['specialist'];
	} else {$specialist = $_POST['specialist'];}
	
	if (isset($_GET["campaign_choice"]))				{$campaign_selected=$_GET["campaign_choice"];}
	elseif (isset($_POST["campaign_choice"]))		{$campaign_selected=$_POST["campaign_choice"];}
	
	if (empty($_GET["limitup"])) {$lu = 0;} else {$lu=$_GET["limitup"];}
	
	if (empty($_GET["limitdown"])) {$ld = 50;} else {$ld=$_GET["limitdown"];}
	
	//compute for years
	
	$currdt = date("Y");
	//$currdt = "2016";
	$mindt1 = date("Y", strtotime('-1 year'));
	$mindt2 = date("Y", strtotime('-2 years'));
	$curryearstart = $currdt."-01-01 00:00:00 ";
	$curryearend = $currdt."-12-31 23:59:59 ";
	$year1start = $mindt1."-01-01 00:00:00 ";
	$year1end = $mindt1."-12-31 23:59:59 ";
	$year2start = $mindt2."-01-01 00:00:00 ";
	$year2end = $mindt2."-12-31 23:59:59 ";
	
	if ($year == "currentyear") {
		$datefrom = $curryearstart;
		$dateto = $curryearend;
	} elseif ($year == "1year") {
		$datefrom = $year1start;
		$dateto = $year1end;
		$currdt = $mindt1;
	} elseif ($year == "2year") {
		$datefrom = $year2start;
		$dateto = $year2end;
		$currdt = $mindt2;
	}

	/* echo "<h2>LimitUP: ".$lu."</h2>";
	echo "<h2>LimitDOWN: ".$ld."</h2>"; */
	
	
?>
<html>
<head>
<title>VICIDIAL ADMIN: QA Reports - Yearly</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">

<style>
body {
	font-family: Arial, Helvetica;
	font-size: 8px;
}

</style>
<style type="text/css">
	table.tblText tr td
	{
		font-size: 12px;
	}
</style>

</head>
<BODY BGCOLOR="#D9E6FE" marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">
<TABLE WIDTH="1309" BGCOLOR="#D9E6FE" cellpadding="3" cellspacing="0" border="1" bordercolor="white" class="tblText"> <!-- change table size here -->

	<?php
		$chkrept = mysql_query("SELECT * FROM vicidial_agent_comments WHERE campaign_id = '$campaign_selected' AND fscore != '' AND call_date BETWEEN '$datefrom' AND '$dateto' LIMIT ".$lu.", ".$ld." ");
							
			$i = 1;
			while ($rowrept = mysql_fetch_array($chkrept)) {
				$full_name = $rowrept['full_name'];
				$fscore = $rowrept['fscore'];
				$rate01 = $rowrept['rate01'];
				$rate02 = $rowrept['rate02'];
				$rate03 = $rowrept['rate03'];
				$rate04 = $rowrept['rate04'];
				$rate05 = $rowrept['rate05'];
				$rate06 = $rowrept['rate06'];
				$rate07 = $rowrept['rate07'];
				$rate08 = $rowrept['rate08'];
				$rate09 = $rowrept['rate09'];
				$rate010 = $rowrept['rate010'];
								
				if ($rate01 == "PASS") {$pq1 = 1; $fq1 = 0; $nq1 = 0;} elseif ($rate01 == "FAIL") {$pq1 = 0; $fq1 = 1; $nq1 = 0;} elseif ($rate01 == "NA") {$pq1 = 0; $fq1 = 0; $nq1 = 1;} else {$pq1 = 0; $fq1 = 0; $nq1 = 0;}
				if ($rate02 == "PASS") {$pq2 = 1; $fq2 = 0; $nq2 = 0;} elseif ($rate02 == "FAIL") {$pq2 = 0; $fq2 = 1; $nq2 = 0;} elseif ($rate02 == "NA") {$pq2 = 0; $fq2 = 0; $nq2 = 1;} else {$pq2 = 0; $fq2 = 0; $nq2 = 0;}
				if ($rate03 == "PASS") {$pq3 = 1; $fq3 = 0; $nq3 = 0;} elseif ($rate03 == "FAIL") {$pq3 = 0; $fq3 = 1; $nq3 = 0;} elseif ($rate03 == "NA") {$pq3 = 0; $fq3 = 0; $nq3 = 1;} else {$pq3 = 0; $fq3 = 0; $nq3 = 0;}
				if ($rate04 == "PASS") {$pq4 = 1; $fq4 = 0; $nq4 = 0;} elseif ($rate04 == "FAIL") {$pq4 = 0; $fq4 = 1; $nq4 = 0;} elseif ($rate04 == "NA") {$pq4 = 0; $fq4 = 0; $nq4 = 1;} else {$pq4 = 0; $fq4 = 0; $nq4 = 0;}									
				if ($rate05 == "PASS") {$pq5 = 1; $fq5 = 0; $nq5 = 0;} elseif ($rate05 == "FAIL") {$pq5 = 0; $fq5 = 1; $nq5 = 0;} elseif ($rate05 == "NA") {$pq5 = 0; $fq5 = 0; $nq5 = 1;} else {$pq5 = 0; $fq5 = 0; $nq5 = 0;}									
				if ($rate06 == "PASS") {$pq6 = 1; $fq6 = 0; $nq6 = 0;} elseif ($rate06 == "FAIL") {$pq6 = 0; $fq6 = 1; $nq6 = 0;} elseif ($rate06 == "NA") {$pq6 = 0; $fq6 = 0; $nq6 = 1;} else {$pq6 = 0; $fq6 = 0; $nq6 = 0;}									
				if ($rate07 == "PASS") {$pq7 = 1; $fq7 = 0; $nq7 = 0;} elseif ($rate07 == "FAIL") {$pq7 = 0; $fq7 = 1; $nq7 = 0;} elseif ($rate07 == "NA") {$pq7 = 0; $fq7 = 0; $nq7 = 1;} else {$pq7 = 0; $fq7 = 0; $nq7 = 0;}									
				if ($rate08 == "PASS") {$pq8 = 1; $fq8 = 0; $nq8 = 0;} elseif ($rate08 == "FAIL") {$pq8 = 0; $fq8 = 1; $nq8 = 0;} elseif ($rate08 == "NA") {$pq8 = 0; $fq8 = 0; $nq8 = 1;} else {$pq8 = 0; $fq8 = 0; $nq8 = 0;}									
				if ($rate09 == "PASS") {$pq9 = 1; $fq9 = 0; $nq9 = 0;} elseif ($rate09 == "FAIL") {$pq9 = 0; $fq9 = 1; $nq9 = 0;} elseif ($rate09 == "NA") {$pq9 = 0; $fq9 = 0; $nq9 = 1;} else {$pq9 = 0; $fq9 = 0; $nq9 = 0;}									
				if ($rate010 == "PASS") {$pq10 = 1; $fq10 = 0; $nq10 = 0;} elseif ($rate010 == "FAIL") {$pq10 = 0; $fq10 = 1; $nq10 = 0;} elseif ($rate010 == "NA") {$pq10 = 0; $fq10 = 0; $nq10 = 1;} else {$pq10 = 0; $fq10 = 0; $nq10 = 0;}
								
				$tpass = ($pq1 + $pq2 + $pq3 + $pq4 + $pq5 + $pq6 + $pq7 + $pq8 + $pq9 + $pq10);
				$tfail = ($fq1 + $fq2 + $fq3 + $fq4 + $fq5 + $fq6 + $fq7 + $fq8 + $fq9 + $fq10);
				$tna = ($nq1 + $nq2 + $nq3 + $nq4 + $nq5 + $nq6 + $nq7 + $nq8 + $nq9 + $nq10);
								
				$ingroup = $rowrept['ingroup'];
				$call_status = $rowrept['call_status'];
				$campaign_id = $rowrept['campaign_id'];
				$supervisor_name = $rowrept['supervisor_name'];
				$comments = $rowrept['comments'];
				$comment_date = $rowrept['comment_date'];
				$call_date = $rowrept['call_date'];
				$lead_id = $rowrept['lead_id'];
								
								
							
				if ($i % 2 == 0) {
					$bgcolor = "#9BB9FB";
				} else {
					$bgcolor = "#B9CBFD";
				}
							
		echo "<tr bgcolor = \"".$bgcolor."\">\n";
			echo "<td align = \"center\" valign = \"top\" width=\"23\"> \n";
				if ($lu == 0) {
					echo $i;
				} else {
					echo ($i + $lu);
				}
				
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"46\"> \n";
				if ($fscore == "PASS") {
					echo "<font color=\"green\">".$fscore."</font> \n";
				} else {
					echo "<font color=\"red\">".$fscore."</font> \n";
				}									
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"55\"> \n";
				if (($tfail == 0) && ($tpass > 0)) {
					$tpf = 100;
				} elseif (($tfail > 0) && ($tpass > 0)) {
					$tcount = ($tfail + $tpass);
					$tpf = (($tpass / $tcount) * 100);
				} else {
					$tpf = 0;
				}
					echo number_format((float)$tpf, 2, '.', '')."%";
		
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"55\"> \n";
				echo $tpass;
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"35\"> \n";
				echo $tfail;
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"35\"> \n";
				echo $tna;
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"64\"> \n";
				echo $full_name;
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"90\"> \n";
				echo $ingroup;
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"54\"> \n";
				echo $call_status;
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"78\"> \n";
				echo $comment_date;
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"79\"> \n";
				echo $call_date;
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"53\"> \n";
				echo $supervisor_name;
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"71\"> \n";
				echo $lead_id;
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"122\"> \n";
				$comment = explode("*", $comments);
				if (strlen($comment[0]) > 3) {
					echo "<a href=\"\" onclick=\"return myFunction".$i."();\">".substr($comment[0],0,30).'...'."</a> \n";
					echo "<script> \n";
						echo "function myFunction".$i."() { \n";
							echo "alert(\"".$comment[0]."\"); return false; \n";
						echo "} \n";
					echo "</script> \n";
				} else {
					echo "None \n";
				}
				
				
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\"  width=\"25\"> \n";
				if ($rate01 == "PASS") {
					echo "<font color = \"green\">P</font> \n";
				} elseif ($rate01 == "NA") {
					echo "<font color = \"black\">NA</font> \n";
				} else {
					echo "<font color = \"red\">F</font> \n";
				}
									
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"25\"> \n";
				if ($rate02 == "PASS") {
					echo "<font color = \"green\">P</font> \n";
				} elseif ($rate02 == "NA") {
					echo "<font color = \"black\">NA</font> \n";
				} else {
					echo "<font color = \"red\">F</font> \n";
				}
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"25\"> \n";
				if ($rate03 == "PASS") {
					echo "<font color = \"green\">P</font> \n";
				} elseif ($rate03 == "NA") {
					echo "<font color = \"black\">NA</font> \n";
				} else {
					echo "<font color = \"red\">F</font> \n";
				}
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"25\"> \n";
				if ($rate04 == "PASS") {
					echo "<font color = \"green\">P</font> \n";
				} elseif ($rate04 == "NA") {
					echo "<font color = \"black\">NA</font> \n";
				} else {
					echo "<font color = \"red\">F</font> \n";
				}
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"25\"> \n";
				if ($rate05 == "PASS") {
					echo "<font color = \"green\">P</font> \n";
				} elseif ($rate05 == "NA") {
					echo "<font color = \"black\">NA</font> \n";
				} else {
					echo "<font color = \"red\">F</font> \n";
				}
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"25\"> \n";
				if ($rate06 == "PASS") {
					echo "<font color = \"green\">P</font> \n";
				} elseif ($rate06 == "NA") {
					echo "<font color = \"black\">NA</font> \n";
				} else {
					echo "<font color = \"red\">F</font> \n";
				}
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"25\"> \n";
				if ($rate07 == "PASS") {
					echo "<font color = \"green\">P</font> \n";
				} elseif ($rate07 == "NA") {
					echo "<font color = \"black\">NA</font> \n";
				} else {
					echo "<font color = \"red\">F</font> \n";
				}
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"25\"> \n";
				if ($rate08 == "PASS") {
					echo "<font color = \"green\">P</font> \n";
				} elseif ($rate08 == "NA") {
					echo "<font color = \"black\">NA</font> \n";
				} else {
					echo "<font color = \"red\">F</font> \n";
				}
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"25\"> \n";
				if ($rate09 == "PASS") {
					echo "<font color = \"green\">P</font> \n";
				} elseif ($rate09 == "NA") {
					echo "<font color = \"black\">NA</font> \n";
				} else {
					echo "<font color = \"red\">F</font> \n";
				}
			echo "</td> \n";
			echo "<td align = \"center\" valign = \"top\" width=\"25\"> \n";
				if ($rate010 == "PASS") {
					echo "<font color = \"green\">P</font> \n";
				} elseif ($rate010 == "NA") {
					echo "<font color = \"black\">NA</font> \n";
				} else {
					echo "<font color = \"red\">F</font> \n";
				}
			echo "</td> \n";
			
		echo "</tr> \n";
		$i++;
		}
		
		echo "<tr> \n";
			echo "<td colspan=\"25\" align = \"center\"> \n";

				/* get total number of qualified rows */
				$chkcount = mysql_query("SELECT COUNT(*) AS maxcount FROM vicidial_agent_comments WHERE campaign_id = '$campaign_selected' AND fscore != '' AND call_date BETWEEN '$datefrom' AND '$dateto' ");
					$rowcount = mysql_fetch_array($chkcount);
					$maxcount = $rowcount['maxcount'];
					$pagecount = ($maxcount / 50);	//divide maxcount by 50 to limit display
					$pagecountround = round($pagecount, 0, PHP_ROUND_HALF_DOWN);	//trim decimal point without rounding up
					if ($pagecount == $pagecountround) {	//compare results if equal to whole number
					} else {
						$totalpages = ($pagecountround + 1);	//add 1 to total pages and declare as new total
					}
					
					$c=0;
					$limitup = 0;
					$limitdown = 50;
					while($c < $totalpages) {
						
						echo "<input type =\"button\" onclick=\"location.href='AST_comments_reports_list_year_result.php?limitup=".$limitup."&limitdown=".$limitdown."&campaign_choice=".$campaign_selected."'\" value=\"Page ".($c + 1)."\"/>\n";
						
					$c++;
					$limitup = $limitup + 50;
					
					}
					
					echo "<input type =\"button\" onclick=\"location.href='AST_comments_reports_list_year_result.php?limitup=0&limitdown=10000&campaign_choice=".$campaign_selected."'\" value=\"Show All\"/>\n";
					
			echo "</td> \n";
		echo "</tr> \n";
echo "</TABLE>\n";
	
	
	
?>
<!-- main content ends here -->

</body>
</html>



