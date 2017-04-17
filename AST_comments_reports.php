<?php
### Ast_comments_reports.php
### 
### Special thanks to Yiannos Katsirintakis <janokary@gmail.com> for the base code.    www.publicissue.gr LICENSE: GPLv2
#### Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#
# Reports landing page
# Created 01-06-2017 noel cruz noel@mycallcloud.com
session_start();

header ("Content-type: text/html; charset=utf-8");

require("dbconnect.php");

include("AST2_qa_sec.php");



?>
<html>
<head>
<title>VICIDIAL ADMIN: QA Reports</title>
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
	width:390px; 
	font-family: Calibri;
	font-size: 13px;
}

#agfield{
	width:390px;   
	font-family: Calibri;
	font-size: 13px;
}

#supfield{
	width:390px;   
	font-family: Calibri;
	font-size: 13px;
}

#ugfield{
	width:390px;   
	font-family: Calibri;
	font-size: 13px;
}

#csfield{
	width:390px;   
	font-family: Calibri;
	font-size: 13px;
}

#from{
	width:160px;   
	font-family: Calibri;
	font-size: 13px;
}

#to{
	width:160px;   
	font-family: Calibri;
	font-size: 13px;
}

#button {
	margin: 0px 0px 0px 0px;
	padding: 2px 2px 2px 2px;
	font-family: Calibri;
	font-size: 13px;
	font-weight: bold;
}

</style>



<link rel="stylesheet" href="styles/jquery-ui.css">
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

<script>
	  $( function() {
    var icons = {
      header: "ui-icon-circle-arrow-e",
      activeHeader: "ui-icon-circle-arrow-s"
    };
    $( "#accordion" ).accordion({
      icons: icons
    });
    $( "#toggle" ).button().on( "click", function() {
      if ( $( "#accordion" ).accordion( "option", "icons" ) ) {
        $( "#accordion" ).accordion( "option", "icons", null );
      } else {
        $( "#accordion" ).accordion( "option", "icons", icons );
      }
    });
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
<TABLE WIDTH="600" BGCOLOR="#D9E6FE" cellpadding="5" cellspacing="0">
	<TR BGCOLOR="#015B91">
		<TD ALIGN="LEFT">
			<a href="./admin.php"><FONT COLOR="WHITE" SIZE="2"><B>VICIDIAL ADMIN</a>: QA Reports
		</TD>
		<TD ALIGN="RIGHT">
			<FONT COLOR="WHITE" SIZE="2"><B><?php echo date("l F j, Y G:i:s A") ?></font>
		</TD>
	</TR> 
	<TR BGCOLOR="#F0F5FE">
		<TD ALIGN="LEFT" colspan = "2">
			<FONT COLOR="BLACK" ><B>Configure QA Report Generation</B></font>
		</TD>
	</TR>
	<TR>
		<TD ALIGN="center" COLSPAN="2" bgcolor="#B9CBFD">


			<div id="accordion">
				<h3>Generate Yearly Report</h3>
				<div>

				<?php
		
					echo "<form name=\"searchutility\" action=\"AST_comments_reports_pick_year.php\" method=\"post\"> \n";
		
						echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" border = \"0\">\n";
							echo "<tr>\n";
								echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
									echo "<b>Select Report Date Range</b>\n";
								echo "</td>";
							echo "</tr>";
				
							// start date range picker with pre-filled dates mcc_edits_start_12_12_2016
				
							$currdt = date("Y");
							$mindt1 = date("Y", strtotime('-1 year'));
							$mindt2 = date("Y", strtotime('-2 years'));
				
							echo "<tr>";
								echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
									echo "&nbsp; \n";
								echo "</td>";
								echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
									echo "<select name=\"year\" id = \"opfield\"> \n";
										echo "<option value=\"currentyear\" selected = \"selected\">CURRENT YEAR (".$currdt.")</option> \n";
										echo "<option value=\"1year\">Past 1 Year (".$mindt1.")</option> \n";
										echo "<option value=\"2year\">Past 2 Years (".$mindt2.")</option> \n";
									echo "</select> \n";
									echo "<input type=\"hidden\" name=\"datefrom\" value=\"".date("Y-m-d", strtotime('-30 days'))."\" /> \n";
									echo "<input type=\"hidden\" name=\"dateto\" value=\"".$currdt."\" /> \n";
								echo "</td>";
							echo "</tr>";
				
							// end date range picker mcc_edits_end_12_12_2016
				
							echo "<tr>\n";
								echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
									echo "<b>Select Campaign</b></font>\n";
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
									echo "<b>Select Agent ID</b><font size =\"-1\"><i> Pls select either ID or Name only</i></font></i>\n";
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
									echo "<b>Select Agent Name</b><font size =\"-1\"><i> Pls select either ID or Name only</i></font>\n";
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

							echo "<tr>\n";
								echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
									echo "<b>Select User Group</b><i>\n";
								echo "</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
									echo "&nbsp; \n";
								echo "</td>";
								echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
									echo "<select name=\"usergroups\" id = \"ugfield\"> \n";
							
										echo "<option value=\"allgroups\" selected = \"selected\">ALL USER GROUPS</option> \n";
						
										$result1c = mysql_query("SELECT DISTINCT user_group AS ugroups FROM vicidial_users WHERE user_group != '---ALL---' ") or die(mysql_error()); 
										while ($row1c = mysql_fetch_array($result1c)){
											$ugroup = $row1c['ugroups'];
							
										echo "<option value=\"".$ugroup."\">".$ugroup."</option> \n";
							
										}

									echo "</select> \n";
								echo "</td>";
							echo "</tr>";
				
							echo "<tr>\n";
								echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
									echo "<b>Select QA Specialist</b>\n";
								echo "</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
									echo "&nbsp; \n";
								echo "</td>";
								echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
									echo "<select name=\"specialist\" id = \"supfield\"> \n";
						
										echo "<option value=\"allsups\" selected = \"selected\">ALL SUPERVISORS</option> \n";
							
										$result1d = mysql_query("SELECT DISTINCT supervisor_id as sup_id FROM vicidial_agent_comments WHERE supervisor_id != '' ") or die(mysql_error()); 
										while ($row1d = mysql_fetch_array($result1d)){
											$sup = $row1d['sup_id'];
							
											$result1d2 = mysql_query("SELECT full_name FROM vicidial_users WHERE user = '$sup'") or die(mysql_error()); 
												$row1d2 = mysql_fetch_array($result1d2); 
												$supfn = $row1d2['full_name'];
							
										echo "<option value=\"".$sup."\">".$sup." - ".$supfn."</option> \n";
										}
													
									echo "</select> \n";
								echo "</td> \n";
							echo "</tr> \n";
				
							echo "<tr> \n";
								echo "<td align=\"center\" bgcolor=\"#015B91\" colspan = \"2\">\n";
									echo "<input type=\"submit\" id = \"button\" value=\"Generate Report\" /> \n";
									echo "<input type=\"reset\" id = \"button\" value=\"Reset to Defaults\" /> \n";
									echo "<input type =\"button\" id = \"button\" onclick=\"location.href='AST_comments_reports_reset.php'\" value=\"Reset Form\" />\n";
								echo "</td> \n";
							echo "</tr> \n";
							echo "<tr> \n";
				
						echo "</table> \n";
		
					echo "</form> \n";
		
				?>
	
				</div>
				<h3>Generate Monthly Report</h3>
				<div>
			
					<?php
		
					echo "<form name=\"searchutility\" action=\"AST_comments_reports_pick_month.php\" method=\"post\"> \n";
		
						echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" border = \"0\">\n";
							echo "<tr>\n";
								echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
									echo "<b>Select Report Date Range</b>\n";
								echo "</td>";
							echo "</tr>";
				
							// start date range picker with pre-filled dates mcc_edits_start_12_12_2016
				
							$currdt = date("Y-m-d");
							$currmonth = date("F");
							$prevmonth1 = date("F", strtotime('-1 month'));
							$prevmonth2 = date("F", strtotime('-2 months'));
							$prevmonth3 = date("F", strtotime('-3 months'));
							$prevmonth4 = date("F", strtotime('-4 months'));
							$prevmonth5 = date("F", strtotime('-5 months'));
							$prevmonth6 = date("F", strtotime('-6 months'));
							$prevmonth7 = date("F", strtotime('-7 months'));
							$prevmonth8 = date("F", strtotime('-8 months'));
							$prevmonth9 = date("F", strtotime('-9 months'));
							$prevmonth10 = date("F", strtotime('-10 months'));
							$prevmonth11 = date("F", strtotime('-11 months'));
				
							echo "<tr>";
								echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
									echo "&nbsp; \n";
								echo "</td>";
								echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
									echo "<select name=\"month\" id = \"opfield\"> \n";
										echo "<option value=\"currentmonth\" selected = \"selected\">CURRENT MONTH (".$currmonth.")</option> \n";
										echo "<option value=\"1month\">Past 1 Month (".$prevmonth1.")</option> \n";
										echo "<option value=\"2month\">Past 2 Months (".$prevmonth2.")</option> \n";
										echo "<option value=\"3month\">Past 3 Months (".$prevmonth3.")</option> \n";
										echo "<option value=\"4month\">Past 4 Months (".$prevmonth4.")</option> \n";
										echo "<option value=\"5month\">Past 5 Months (".$prevmonth5.")</option> \n";
										echo "<option value=\"6month\">Past 6 Months (".$prevmonth6.")</option> \n";
										echo "<option value=\"7month\">Past 7 Months (".$prevmonth7.")</option> \n";
										echo "<option value=\"8month\">Past 8 Months (".$prevmonth8.")</option> \n";
										echo "<option value=\"9month\">Past 9 Months (".$prevmonth9.")</option> \n";
										echo "<option value=\"10month\">Past 10 Months (".$prevmonth10.")</option> \n";
										echo "<option value=\"11month\">Past 11 Months (".$prevmonth11.")</option> \n";
									echo "</select> \n";
									echo "<input type=\"hidden\" name=\"datefrom\" value=\"".date("Y-m-d", strtotime('-30 days'))."\" /> \n";
									echo "<input type=\"hidden\" name=\"dateto\" value=\"".$currdt."\" /> \n";
								echo "</td>";
							echo "</tr>";
				
							// end date range picker mcc_edits_end_12_12_2016
				
							echo "<tr>\n";
								echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
									echo "<b>Select Campaign</b></font>\n";
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
									echo "<b>Select Agent ID</b><font size =\"-1\"><i> Pls select either ID or Name only</i></font></i>\n";
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
									echo "<b>Select Agent Name</b><font size =\"-1\"><i> Pls select either ID or Name only</i></font>\n";
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

							echo "<tr>\n";
								echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
									echo "<b>Select User Group</b><i>\n";
								echo "</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
									echo "&nbsp; \n";
								echo "</td>";
								echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
									echo "<select name=\"usergroups\" id = \"ugfield\"> \n";
							
										echo "<option value=\"allgroups\" selected = \"selected\">ALL USER GROUPS</option> \n";
						
										$result1c = mysql_query("SELECT DISTINCT user_group AS ugroups FROM vicidial_users WHERE user_group != '---ALL---' ") or die(mysql_error()); 
										while ($row1c = mysql_fetch_array($result1c)){
											$ugroup = $row1c['ugroups'];
							
										echo "<option value=\"".$ugroup."\">".$ugroup."</option> \n";
							
										}

									echo "</select> \n";
								echo "</td>";
							echo "</tr>";
				
							echo "<tr>\n";
								echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
									echo "<b>Select QA Specialist</b>\n";
								echo "</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
									echo "&nbsp; \n";
								echo "</td>";
								echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
									echo "<select name=\"specialist\" id = \"supfield\"> \n";
						
										echo "<option value=\"allsups\" selected = \"selected\">ALL SUPERVISORS</option> \n";
							
										$result1d = mysql_query("SELECT DISTINCT supervisor_id as sup_id FROM vicidial_agent_comments WHERE supervisor_id != '' ") or die(mysql_error()); 
										while ($row1d = mysql_fetch_array($result1d)){
											$sup = $row1d['sup_id'];
							
											$result1d2 = mysql_query("SELECT full_name FROM vicidial_users WHERE user = '$sup'") or die(mysql_error()); 
												$row1d2 = mysql_fetch_array($result1d2); 
												$supfn = $row1d2['full_name'];
							
										echo "<option value=\"".$sup."\">".$sup." - ".$supfn."</option> \n";
										}
													
									echo "</select> \n";
								echo "</td> \n";
							echo "</tr> \n";
				
							echo "<tr> \n";
								echo "<td align=\"center\" bgcolor=\"#015B91\" colspan = \"2\">\n";
									echo "<input type=\"submit\" id = \"button\" value=\"Generate Report\" /> \n";
									echo "<input type=\"reset\" id = \"button\" value=\"Reset to Defaults\" /> \n";
									echo "<input type =\"button\" id = \"button\" onclick=\"location.href='AST_comments_reports_reset.php'\" value=\"Reset Form\" />\n";
								echo "</td> \n";
							echo "</tr> \n";
							echo "<tr> \n";
				
						echo "</table> \n";
		
					echo "</form> \n";
		
				?>
	
				</div>
				<h3>Generate Weekly Report</h3>
				<div>
				
				<?php
		
					echo "<form name=\"searchutility\" action=\"AST_comments_reports_pick_week.php\" method=\"post\"> \n";
		
						echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" border = \"0\">\n";
							echo "<tr>\n";
								echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
									echo "<b>Select Report Date Range</b>\n";
								echo "</td>";
							echo "</tr>";
				
							// start date range picker with pre-filled dates mcc_edits_start_12_12_2016
				
							$currdt = date("Y-m-d");
							$wkstart = date("Y-m-d", strtotime('monday this week'));
							$wkend = date("Y-m-d", strtotime('friday this week'));
							$currweek = $wkstart." to ".$wkend;
							
							$prevwkstart = date("Y-m-d", strtotime('last week monday'));
							$prevwkend = date("Y-m-d", strtotime('previous friday'));
							$prevwk = $prevwkstart." to ".$prevwkend;
							
							$prevwkstart1 = date("Y-m-d", strtotime('-3 weeks monday'));
							$prevwkend1 = date("Y-m-d", strtotime('-2 weeks friday'));
							$prevwk1 = $prevwkstart1." to ".$prevwkend1;
							
							$prevwkstart2 = date("Y-m-d", strtotime('-4 weeks monday'));
							$prevwkend2 = date("Y-m-d", strtotime('-3 weeks friday'));
							$prevwk2 = $prevwkstart2." to ".$prevwkend2;
				
							echo "<tr>";
								echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
									echo "&nbsp; \n";
								echo "</td>";
								echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
									echo "<select name=\"week\" id = \"opfield\"> \n";
										echo "<option value=\"currentweek\" selected = \"selected\">CURRENT WEEK (".$currweek.")</option> \n";
										echo "<option value=\"1week\">Past 1 Week (".$prevwk.")</option> \n";
										echo "<option value=\"2week\">Past 2 Weeks (".$prevwk1.")</option> \n";
										echo "<option value=\"3week\">Past 3 Weeks (".$prevwk2.")</option> \n";
									echo "</select> \n";
									echo "<input type=\"hidden\" name=\"datefrom\" value=\"".date("Y-m-d", strtotime('-30 days'))."\" /> \n";
									echo "<input type=\"hidden\" name=\"dateto\" value=\"".$currdt."\" /> \n";
								echo "</td>";
							echo "</tr>";
				
							// end date range picker mcc_edits_end_12_12_2016
				
							echo "<tr>\n";
								echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
									echo "<b>Select Campaign</b></font>\n";
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
									echo "<b>Select Agent ID</b><font size =\"-1\"><i> Pls select either ID or Name only</i></font></i>\n";
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
									echo "<b>Select Agent Name</b><font size =\"-1\"><i> Pls select either ID or Name only</i></font>\n";
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

							echo "<tr>\n";
								echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
									echo "<b>Select User Group</b><i>\n";
								echo "</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
									echo "&nbsp; \n";
								echo "</td>";
								echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
									echo "<select name=\"usergroups\" id = \"ugfield\"> \n";
							
										echo "<option value=\"allgroups\" selected = \"selected\">ALL USER GROUPS</option> \n";
						
										$result1c = mysql_query("SELECT DISTINCT user_group AS ugroups FROM vicidial_users WHERE user_group != '---ALL---' ") or die(mysql_error()); 
										while ($row1c = mysql_fetch_array($result1c)){
											$ugroup = $row1c['ugroups'];
							
										echo "<option value=\"".$ugroup."\">".$ugroup."</option> \n";
							
										}

									echo "</select> \n";
								echo "</td>";
							echo "</tr>";
				
							echo "<tr>\n";
								echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
									echo "<b>Select QA Specialist</b>\n";
								echo "</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
									echo "&nbsp; \n";
								echo "</td>";
								echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
									echo "<select name=\"specialist\" id = \"supfield\"> \n";
						
										echo "<option value=\"allsups\" selected = \"selected\">ALL SUPERVISORS</option> \n";
							
										$result1d = mysql_query("SELECT DISTINCT supervisor_id as sup_id FROM vicidial_agent_comments WHERE supervisor_id != '' ") or die(mysql_error()); 
										while ($row1d = mysql_fetch_array($result1d)){
											$sup = $row1d['sup_id'];
							
											$result1d2 = mysql_query("SELECT full_name FROM vicidial_users WHERE user = '$sup'") or die(mysql_error()); 
												$row1d2 = mysql_fetch_array($result1d2); 
												$supfn = $row1d2['full_name'];
							
										echo "<option value=\"".$sup."\">".$sup." - ".$supfn."</option> \n";
										}
													
									echo "</select> \n";
								echo "</td> \n";
							echo "</tr> \n";
				
							echo "<tr> \n";
								echo "<td align=\"center\" bgcolor=\"#015B91\" colspan = \"2\">\n";
									echo "<input type=\"submit\" id = \"button\" value=\"Generate Report\" /> \n";
									echo "<input type=\"reset\" id = \"button\" value=\"Reset to Defaults\" /> \n";
									echo "<input type =\"button\" id = \"button\" onclick=\"location.href='AST_comments_reports_reset.php'\" value=\"Reset Form\" />\n";
								echo "</td> \n";
							echo "</tr> \n";
							echo "<tr> \n";
				
						echo "</table> \n";
		
					echo "</form> \n";
		
				?>
	
				</div>
				<h3>Generate Daily Report</h3>
				<div>
				
				<?php
		
					echo "<form name=\"searchutility\" action=\"AST_comments_reports_pick.php\" method=\"post\"> \n";
		
						echo "<TABLE width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" border = \"0\">\n";
							echo "<tr>\n";
								echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
									echo "<b>Select Report Date Range</b>\n";
								echo "</td>";
							echo "</tr>";
				
							// start date range picker with pre-filled dates mcc_edits_start_12_12_2016
				
							$currdt = date("Y-m-d");
							$mindt = date("Y-m-d", strtotime('-30 days'));			//set min date to 30 days from current date
				
							echo "<tr>";
								echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
									echo "&nbsp; \n";
								echo "</td>";
								echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
									echo "<label for=\"from\">From: </label> \n";
									echo "<input type=\"text\" id=\"from\" name=\"datefrom\" value = \"".$mindt."\"> \n";
									echo "<label for=\"to\"> To: </label> \n";
									echo "<input type=\"text\" id=\"to\" name=\"dateto\" value = \"".$currdt."\"> \n";
								echo "</td>";
							echo "</tr>";
				
							// end date range picker mcc_edits_end_12_12_2016
				
							echo "<tr>\n";
								echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
									echo "<b>Select Campaign</b></font>\n";
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
									echo "<b>Select Agent ID</b><font size =\"-1\"><i> Pls select either ID or Name only</i></font></i>\n";
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
									echo "<b>Select Agent Name</b><font size =\"-1\"><i> Pls select either ID or Name only</i></font>\n";
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

							echo "<tr>\n";
								echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
									echo "<b>Select User Group</b><i>\n";
								echo "</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
									echo "&nbsp; \n";
								echo "</td>";
								echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
									echo "<select name=\"usergroups\" id = \"ugfield\"> \n";
							
										echo "<option value=\"allgroups\" selected = \"selected\">ALL USER GROUPS</option> \n";
						
										$result1c = mysql_query("SELECT DISTINCT user_group AS ugroups FROM vicidial_users WHERE user_group != '---ALL---' ") or die(mysql_error()); 
										while ($row1c = mysql_fetch_array($result1c)){
											$ugroup = $row1c['ugroups'];
							
										echo "<option value=\"".$ugroup."\">".$ugroup."</option> \n";
							
										}

									echo "</select> \n";
								echo "</td>";
							echo "</tr>";
				
							echo "<tr>\n";
								echo "<td align=\"left\" bgcolor=\"#9BB9FB\" colspan = \"2\">\n";
									echo "<b>Select QA Specialist</b>\n";
								echo "</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td bgcolor=\"#B9CBFD\" width = \"15\">";
									echo "&nbsp; \n";
								echo "</td>";
								echo "<td align=\"center\" bgcolor=\"#B9CBFD\">\n";
									echo "<select name=\"specialist\" id = \"supfield\"> \n";
						
										echo "<option value=\"allsups\" selected = \"selected\">ALL SUPERVISORS</option> \n";
							
										$result1d = mysql_query("SELECT DISTINCT supervisor_id as sup_id FROM vicidial_agent_comments WHERE supervisor_id != '' ") or die(mysql_error()); 
										while ($row1d = mysql_fetch_array($result1d)){
											$sup = $row1d['sup_id'];
							
											$result1d2 = mysql_query("SELECT full_name FROM vicidial_users WHERE user = '$sup'") or die(mysql_error()); 
												$row1d2 = mysql_fetch_array($result1d2); 
												$supfn = $row1d2['full_name'];
							
										echo "<option value=\"".$sup."\">".$sup." - ".$supfn."</option> \n";
										}
													
									echo "</select> \n";
								echo "</td> \n";
							echo "</tr> \n";
				
							echo "<tr> \n";
								echo "<td align=\"center\" bgcolor=\"#015B91\" colspan = \"2\">\n";
									echo "<input type=\"submit\" id = \"button\" value=\"Generate Report\" /> \n";
									echo "<input type=\"reset\" id = \"button\" value=\"Reset to Defaults\" /> \n";
									echo "<input type =\"button\" id = \"button\" onclick=\"location.href='AST_comments_reports_reset.php'\" value=\"Reset Form\" />\n";
								echo "</td> \n";
							echo "</tr> \n";
							echo "<tr> \n";
				
						echo "</table> \n";
		
					echo "</form> \n";
		
				?>
	
				</div>
				</div>

		</td>
	</tr>
</table>

</body>
</html>
