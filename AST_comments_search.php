<?php
### Ast_comments_search.php
### 
### Special thanks to Yiannos Katsirintakis <janokary@gmail.com> for the base code.    www.publicissue.gr LICENSE: GPLv2
#### Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#
# additional page for searching qa questions functionalities per agent, date range, or sup
# created 12-12-2016 noel cruz noel@mycallcloud.com
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
	
	#############################################################################################
	## START: FULL UPDATER SCRIPT TO POPULATE VICIDIAL_AGENT_COMMENTS - ADDED 10/30/2017
	#############################################################################################
	
	$cdsrc = mysql_query("SELECT MAX(call_date) as last_call_date FROM vicidial_agent_comments ");
	$rowcd = mysql_fetch_array($cdsrc);
	$last_call_date = $rowcd['last_call_date'];
		
	$dbsrc = 	mysql_query("SELECT * FROM vicidial_log WHERE call_date > '$last_call_date' AND user != 'VDAD' AND user != 'VDCL' AND length_in_sec > '10' ");
	$i=1;
	while ($rowsrc = mysql_fetch_array($dbsrc)) {
		$call_date = $rowsrc['call_date'];
		$uniqueid = $rowsrc['uniqueid'];
		$user = $rowsrc['user'];
		$user_group = $rowsrc['user_group'];
		$campaign_id = $rowsrc['campaign_id'];
		$lead_id = $rowsrc['lead_id'];
		
		mysql_query("INSERT INTO vicidial_agent_comments 
			(call_date,
			uniqueid,
			lead_id,
			user,
			user_group,
			campaign_id) 
		VALUES
			('$call_date', 
			'$uniqueid',
			'$lead_id',
			'$user', 
			'$user_group', 
			'$campaign_id') ") 
		or die(mysql_error());  

		$cidsrc = mysql_query("SELECT campaign_name,active FROM vicidial_campaigns WHERE campaign_id = '$campaign_id' ");
		$rowcid = mysql_fetch_array($cidsrc);
		$campname = $rowcid['campaign_name'];
		$active = $rowcid['active'];
			
		$fnsrc = mysql_query("SELECT full_name FROM vicidial_users WHERE user = '$user' ");
		$rowsrc = mysql_fetch_array($fnsrc);
		$full_name = $rowsrc['full_name'];
		
		mysql_query("UPDATE vicidial_agent_comments SET campname = '$campaign_name' WHERE campaign_id = '$campaign_id' ");
		mysql_query("UPDATE vicidial_agent_comments SET active = '$active' WHERE user = '$user' ");
		mysql_query("UPDATE vicidial_agent_comments SET full_name = '$full_name' WHERE user = '$user' ");
		
		//echo $i.". added: ".$user." | ".$full_name." | ".$call_date."<br>\n";

	$i++;
	}
	
	#############################################################################################
	## END: FULL UPDATER SCRIPT TO POPULATE VICIDIAL_AGENT_COMMENTS - ADDED 10/30/2017
	#############################################################################################

//$datefromset = $_SESSION['datefrom'];
//$datetoset = $_SESSION['dateto'];
//$campaignsset = $_SESSION['campaigns'];
//$camp_nameset = $_SESSION['camp_name'];
//$agentsset = $_SESSION['agents'];
//$agent_fnset = $_SESSION['agent_fn'];
//$usergroupsset = $_SESSION['usergroups'];
//$specialistset = $_SESSION['specialist'];
//$supfn = $_SESSION['spec_fn'];
	
//echo "DF datefromset: ".$datefromset."<br>";
//echo "DT datetoset: ".$datetoset."<br>";
//echo "CID campaignsset: ".$campaignsset."<br>";
//echo "CN camp_nameset: ".$camp_nameset."<br>";
//echo "AG agentsset: ".$agentsset."<br>";
//echo "AGN agent_fnset: ".$agent_fnset."<br>";
//echo "UG usergroupsset: ".$usergroupsset."<br>";
//echo "SP specialistset: ".$specialistset."<br>";
//echo "SPFN supfn: ".$supfn."<br>";
	


?>
<html>
<head>
<title>VICIDIAL ADMIN: QA Utilities Search</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">

<style>
body {
	font-family: Arial, Helvetica;
}
</style>
<!-- start imports and script for datepicker mcc_edits_start_12-12-2016 -->

<!-- css style for option field witdh -->
<style>
#opfield{
 width:365px;   
}

#agfield{
 width:365px;   
}

#supfield{
 width:365px;   
}

#ugfield{
 width:365px;   
}

#csfield{
 width:365px;   
}
</style>



<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $( function() {
    var dateFormat = "mm/dd/yy",
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
  
  <script language="JavaScript">
function updb_pop(){
popup = window.open("AST_comments_search_mupdate_start.php","","screenX=0,screenY=0,toolbar=0,location=0,status=0,menubar=0,scrollbars=1,resizable=0,width=520,height=155,top='+screen.availTop+',left='+screen.availLeft");
}

</script>
  
  
  
<!-- end imports and script for datepicker mcc_edits_end_12-12-2016 -->
  
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
		echo "<TD ALIGN=LEFT>\n";
			echo "<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK ><B>Search Records</B>\n";
		echo "</TD>\n";
		echo "<TD ALIGN=right>\n";
			//echo "<input type =\"button\" onclick=\"location.href='javascript:updb_pop()'\" value=\"Manual Update\" />\n";
		echo "</TD>\n";
	echo "</TR>\n";
	echo "<TR>\n";
		echo "<TD ALIGN=LEFT COLSPAN=2>\n";

		//start search form mcc_edits_start_12_12_2016
		
		echo "<form name=\"searchutility\" action=\"AST_comments_list.php\" method=\"post\"> \n";
		
			echo "<TABLE width=\"600\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" border = \"0\">\n";
				echo "<tr>\n";
					echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
						echo "<b>Select a Call Date Range</b> <i><font size =\"-1\">Click fields to select dates (recommended range is within 30 days)*</font></i>\n";
					echo "</td>";
				echo "</tr>";
				
				// start date range picker with pre-filled dates mcc_edits_start_12_12_2016
				
				$currdt = date("Y-m-d h:m:s");
				$mindt = date("Y-m-d", strtotime('-30 days'));			//set min date to 30 days from current date
				
				echo "<tr>";
					echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
						echo "&nbsp; \n";
					echo "</td>";
					echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
						echo "<label for=\"from\">From: </label> \n";
						echo "<input type=\"text\" id=\"from\" name=\"datefrom\" value = \"".$mindt." 00:00:00\"> \n";
						echo "<label for=\"to\"> To: </label> \n";
						echo "<input type=\"text\" id=\"to\" name=\"dateto\" value = \"".$currdt."\"> \n";
					echo "</td>";
				echo "</tr>";
				
				// end date range picker mcc_edits_end_12_12_2016
				
				echo "<tr>\n";
					echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
						echo "<b>Select Campaign</b><i><font size =\"-1\"> Default is all campaigns.</i></font>\n";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
						echo "&nbsp; \n";
					echo "</td>";
					echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
					echo "<select name=\"campaigns\" id = \"opfield\"> \n";
						
					echo "<option value=\"allcamps\" selected = \"selected\">ALL CAMPAIGNS</option> \n";
					
					$result1 = mysql_query("SELECT * FROM vicidial_campaigns WHERE campaign_id != '$campaignsset'") or die(mysql_error()); 
						while ($row1 = mysql_fetch_array($result1)){
							$campid = $row1['campaign_id'];
							$camp_name = $row1['campaign_name'];	
						
						echo "<option value=\"".$campid."\">".$campid." - ".$camp_name."</option> \n";
						}

						echo "</select> \n";
					echo "</td>";
				echo "</tr>";
				
				//original dropdown search for agent id
				echo "<tr>\n";
					echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
						echo "<b>Select Agent ID</b><font size =\"-1\"><i> Default is all agents. <font color = \"red\"><i>(Pls select either ID or Name only)</i></font></font></i>\n";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
						echo "&nbsp; \n";
					echo "</td>";
					echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
						echo "<select name=\"agentid\" id = \"agfield\"> \n";
						
							echo "<option value=\"allagentids\" selected = \"selected\">ALL AGENT IDS</option> \n";
							
						$result1b = mysql_query("SELECT DISTINCT user AS agent FROM vicidial_users WHERE active = 'Y' AND user != 'VDAD' AND user != 'VDCL' ORDER BY agent ASC") or die(mysql_error()); 
							while ($row1b = mysql_fetch_array($result1b)){
							$agent = $row1b['agent'];
							echo "<option value=\"".$agent."\">".$agent."</option> \n";
							}
						
						echo "</select> \n";
					echo "</td>";
				echo "</tr>";
				
				//original dropdown search for agent name
				echo "<tr>\n";
					echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
						echo "<b>Select Agent Name</b><font size =\"-1\"><i> Default is all agents.<font color = \"red\"><i>(Pls select either ID or Name only)</i></font></font></i></i>\n";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
						echo "&nbsp; \n";
					echo "</td>";
					echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
						echo "<select name=\"agentname\" id = \"agfield\"> \n";
						
							echo "<option value=\"allagentnames\" selected = \"selected\">ALL AGENT NAMES</option> \n";
							
						$result1ba = mysql_query("SELECT DISTINCT user AS agent FROM vicidial_users WHERE active = 'Y' ORDER BY agent ASC") or die(mysql_error()); 
							while ($row1ba = mysql_fetch_array($result1ba)){
								$agent = $row1ba['agent'];
							
							$result1b2 = mysql_query("SELECT full_name FROM vicidial_users WHERE user = '$agent' AND active = 'Y' ORDER BY full_name ASC ") or die(mysql_error()); 
								$row1b2 = mysql_fetch_array($result1b2) or die(mysql_error()); 
								$agentfn = $row1b2['full_name'];
							
								echo "<option value=\"".$agentfn."\">".$agentfn."</option> \n";
							}

						echo "</select> \n";
					echo "</td>";
				echo "</tr>";
					
					echo "</td>";
				echo "</tr>";
				
				echo "<tr>\n";
					echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
						echo "<b>Select User Group</b><i><font size =\"-1\"> Default is all User Groups.</font></i>\n";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
						echo "&nbsp; \n";
					echo "</td>";
					echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
						echo "<select name=\"usergroups\" id = \"ugfield\"> \n";
						
						if ($usergroupsset == "allgroups") {
						
							echo "<option value=\"allgroups\" selected = \"selected\">ALL USER GROUPS</option> \n";
						
							$result1c = mysql_query("SELECT DISTINCT user_group as ugroups FROM vicidial_users") or die(mysql_error()); 
							while ($row1c = mysql_fetch_array($result1c)){
								$ugroup = $row1c['ugroups'];
							
							echo "<option value=\"".$ugroup."\">".$ugroup."</option> \n";
						
							}
							
						} elseif (empty($usergroupsset)) {
							
							echo "<option value=\"allgroups\" selected = \"selected\">ALL USER GROUPS</option> \n";
						
							$result1c = mysql_query("SELECT DISTINCT user_group AS ugroups FROM vicidial_users WHERE user_group != '---ALL---' ") or die(mysql_error()); 
							while ($row1c = mysql_fetch_array($result1c)){
								$ugroup = $row1c['ugroups'];
							
							echo "<option value=\"".$ugroup."\">".$ugroup."</option> \n";
							
							}
						
						} else {
							
							$ugroup = $usergroupsset;
							
							echo "<option value=\"allgroups\">ALL USER GROUPS</option> \n";
							echo "<option value=\"".$ugroup."\" selected = \"selected\">".$ugroup."</option> \n";
							
							$result1c = mysql_query("SELECT DISTINCT user_group as ugroups FROM vicidial_users WHERE user_group != '$ugroup'") or die(mysql_error()); 
							while ($row1c = mysql_fetch_array($result1c)){
								$ugroup = $row1c['ugroups'];
							
							echo "<option value=\"".$ugroup."\">".$ugroup."</option> \n";
							
							}
							
						}
							
						echo "</select> \n";
					echo "</td>";
				echo "</tr>";
				
				echo "<tr>\n";
					echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
						echo "<b>Select QA Specialist</b><i><font size =\"-1\"> Default is all Specialists.</font></i>\n";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
						echo "&nbsp; \n";
					echo "</td>";
					echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
						echo "<select name=\"specialist\" id = \"supfield\"> \n";
						
						if ($specialistset == "allsups") {
							
							echo "<option value=\"allsups\" selected = \"selected\">ALL SUPERVISORS</option> \n";
							
							$result1d = mysql_query("SELECT DISTINCT supervisor_id as sup_id FROM vicidial_agent_comments WHERE supervisor_id != '' ") or die(mysql_error()); 
							while ($row1d = mysql_fetch_array($result1d)){
								$sup = $row1d['sup_id'];
							
								$result1d2 = mysql_query("SELECT full_name FROM vicidial_users WHERE user = '$sup'") or die(mysql_error()); 
									$row1d2 = mysql_fetch_array($result1d2); 
									$supfn = $row1d2['full_name'];
							
							echo "<option value=\"".$sup."\">".$sup." - ".$supfn."</option> \n";
													
							}
							
						} elseif (empty($specialistset)) {
							
							echo "<option value=\"allsups\" selected = \"selected\">ALL SUPERVISORS</option> \n";
							
							$result1d = mysql_query("SELECT DISTINCT supervisor_id as sup_id FROM vicidial_agent_comments WHERE supervisor_id != '' ") or die(mysql_error()); 
							while ($row1d = mysql_fetch_array($result1d)){
								$sup = $row1d['sup_id'];
							
								$result1d2 = mysql_query("SELECT full_name FROM vicidial_users WHERE user = '$sup'") or die(mysql_error()); 
									$row1d2 = mysql_fetch_array($result1d2); 
									$supfn = $row1d2['full_name'];
							
							echo "<option value=\"".$sup."\">".$sup." - ".$supfn."</option> \n";
							}
													
						} else {
								
							$sup = $specialistset;
							
							echo "<option value=\"allsups\">ALL SUPERVISORS</option> \n";
							echo "<option value=\"".$sup."\" selected = \"selected\">".$sup." - ".$supfn."</option> \n";
							
							$result1d = mysql_query("SELECT DISTINCT supervisor_id AS sup_id FROM vicidial_agent_comments WHERE supervisor_id != '$sup'") or die(mysql_error()); 
							while ($row1d = mysql_fetch_array($result1d)){
								$sup = $row1d['sup_id'];
								
							echo "<option value=\"".$sup."\">".$sup." - ".$supfn."</option> \n";
							
							}

						}
							
						echo "</select> \n";
					echo "</td> \n";
				echo "</tr> \n";
				
				echo "<tr> \n";
					echo "<td align=\"center\" bgcolor=\"#015B91\" colspan = \"2\">\n";
						echo "<input type=\"submit\" value=\"Begin Records Search\" /> \n";
						echo "<input type=\"reset\" value=\"Clear Current Changes\" /> \n";
						echo "<input type =\"button\" onclick=\"location.href='AST_comments_search_reset.php'\" value=\"Reset Form\" />\n";
					echo "</td> \n";
				echo "</tr> \n";
				echo "<tr> \n";
					echo "<td align=\"justify\" valign = \"top\">\n";
						echo "<font size = \"-1\"><b><i>NOTE:</b> \n";
					echo "</td> \n";
					echo "<td align=\"justify\">\n";
						echo "<font size = \"-1\"><i>Keeping your call date range within 30 days will ensure availability of call recordings for playback. If no audio file is found, results will show \"Audio File Not Found.\"<p> \n";
						echo "Selecting ALL DEFAULTS may take some time for your results to fully display. Try to be specific in searching.</i></font> \n";
					echo "</td> \n";
				echo "</tr> \n";
				
			echo "</table> \n";
		
		echo "</form> \n";
		
		echo "<form name=\"searchcampstats\" action=\"AST_comments_campstats.php\" method=\"post\"> \n";
		
			echo "<TABLE width=\"600\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" border = \"0\">\n";
				echo "<tr>\n";
					echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
						echo "<b>Select Campaign Status</b><i><font size =\"-1\"> Default is all Statuses (Independent Search).</font></i>\n";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
						echo "&nbsp; \n";
					echo "</td>";
					echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
						echo "<select name=\"campaign_status\" id = \"csfield\"> \n";
						
						echo "<option value=\"allcampstats\" selected = \"selected\">ALL STATUSES</option> \n";
						$cschk = mysql_query("SELECT DISTINCT status, status_name FROM vicidial_campaign_statuses WHERE selectable = 'Y' ORDER BY status");
							while ($rowchk = mysql_fetch_array($cschk)) {
								$campstatus = $rowchk['status'];
								$campstatusname = $rowchk['status_name'];
								
								echo "<option value=\"".$campstatus."\">".$campstatus." - ".$campstatusname."</option> \n";

							}
						
						echo "</select> \n";
					echo "</td> \n";
				echo "</tr> \n";
				
				echo "<tr> \n";
					echo "<td align=\"center\" bgcolor=\"#015B91\" colspan = \"2\">\n";
						echo "<input type=\"submit\" value=\"Begin Campaign Status Search\" /> \n";
						echo "<input type=\"reset\" value=\"Reset Campaign Status Search\" /> \n";
					echo "</td> \n";
				echo "</tr> \n";
				
			echo "</table> \n";
		
		echo "</form> \n";
		
?>

