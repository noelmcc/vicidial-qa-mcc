<?php
### AST_comments_supedit_p4.php
### 
### Special thanks to Yiannos Katsirintakis <janokary@gmail.com> for the base code.    www.publicissue.gr LICENSE: GPLv2
#### Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#
# additional page for supervisor access to qa questions functionalities
# created 12-19-2016 noel cruz noel@mycallcloud.com

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

$currdt = date("Y-m-d h:m:s");
$case_cid = $_POST["case_cid"];
$qid1 = $_POST["qid1"];
$qid2 = $_POST["qid2"];
$qid3 = $_POST["qid3"];
$qid4 = $_POST["qid4"];
$qid5 = $_POST["qid5"];
$qid6 = $_POST["qid6"];
$qid7 = $_POST["qid7"];
$qid8 = $_POST["qid8"];
$qid9 = $_POST["qid9"];
$qid10 = $_POST["qid10"];
$seq_num1 = $_POST["seq_num1"];
$seq_num2 = $_POST["seq_num2"];
$seq_num3 = $_POST["seq_num3"];
$seq_num4 = $_POST["seq_num4"];
$seq_num5 = $_POST["seq_num5"];
$seq_num6 = $_POST["seq_num6"];
$seq_num7 = $_POST["seq_num7"];
$seq_num8 = $_POST["seq_num8"];
$seq_num9 = $_POST["seq_num9"];
$seq_num10 = $_POST["seq_num10"];
$question1 = $_POST["question1"];
$question2 = $_POST["question2"];
$question3 = $_POST["question3"];
$question4 = $_POST["question4"];
$question5 = $_POST["question5"];
$question6 = $_POST["question6"];
$question7 = $_POST["question7"];
$question8 = $_POST["question8"];
$question9 = $_POST["question9"];
$question10 = $_POST["question10"];
$newq1 = $_POST["newq1"];
$newq2 = $_POST["newq2"];
$newq3 = $_POST["newq3"];
$newq4 = $_POST["newq4"];
$newq5 = $_POST["newq5"];
$newq6 = $_POST["newq6"];
$newq7 = $_POST["newq7"];
$newq8 = $_POST["newq8"];
$newq9 = $_POST["newq9"];
$newq10 = $_POST["newq10"];
$newstat1 = $_POST["newstat1"];
$newstat2 = $_POST["newstat2"];
$newstat3 = $_POST["newstat3"];
$newstat4 = $_POST["newstat4"];
$newstat5 = $_POST["newstat5"];
$newstat6 = $_POST["newstat6"];
$newstat7 = $_POST["newstat7"];
$newstat8 = $_POST["newstat8"];
$newstat9 = $_POST["newstat9"];
$newstat10 = $_POST["newstat10"];
$newaf1 = $_POST["newaf1"];
$newaf2 = $_POST["newaf2"];
$newaf3 = $_POST["newaf3"];
$newaf4 = $_POST["newaf4"];
$newaf5 = $_POST["newaf5"];
$newaf6 = $_POST["newaf6"];
$newaf7 = $_POST["newaf7"];
$newaf8 = $_POST["newaf8"];
$newaf9 = $_POST["newaf9"];
$newaf10 = $_POST["newaf10"];

//echo "currdt: "	.$currdt."<br>";
//echo "case_cid: "	.$case_cid."<br>";
//echo "qid1: "	.$qid1."<br>";
//echo "qid2: "	.$qid2."<br>";
//echo "qid3: "	.$qid3."<br>";
//echo "qid4: "	.$qid4."<br>";
//echo "qid5: "	.$qid5."<br>";
//echo "qid6: "	.$qid6."<br>";
//echo "qid7: "	.$qid7."<br>";
//echo "qid8: "	.$qid8."<br>";
//echo "qid9: "	.$qid9."<br>";
//echo "qid10: "	.$qid10."<br>";
//echo "seq_num1: "	.$seq_num1."<br>";
//echo "seq_num2: "	.$seq_num2."<br>";
//echo "seq_num3: "	.$seq_num3."<br>";
//echo "seq_num4: "	.$seq_num4."<br>";
//echo "seq_num5: "	.$seq_num5."<br>";
//echo "seq_num6: "	.$seq_num6."<br>";
//echo "seq_num7: "	.$seq_num7."<br>";
//echo "seq_num8: "	.$seq_num8."<br>";
//echo "seq_num9: "	.$seq_num9."<br>";
//echo "seq_num10: "	.$seq_num10."<br>";
//echo "question1: "	.$question1."<br>";
//echo "question2: "	.$question2."<br>";
//echo "question3: "	.$question3."<br>";
//echo "question4: "	.$question4."<br>";
//echo "question5: "	.$question5."<br>";
//echo "question6: "	.$question6."<br>";
//echo "question7: "	.$question7."<br>";
//echo "question8: "	.$question8."<br>";
//echo "question9: "	.$question9."<br>";
//echo "question10: "	.$question10."<br>";
//echo "newq1: "	.$newq1."<br>";
//echo "newq2: "	.$newq2."<br>";
//echo "newq3: "	.$newq3."<br>";
//echo "newq4: "	.$newq4."<br>";
//echo "newq5: "	.$newq5."<br>";
//echo "newq6: "	.$newq6."<br>";
//echo "newq7: "	.$newq7."<br>";
//echo "newq8: "	.$newq8."<br>";
//echo "newq9: "	.$newq9."<br>";
//echo "newq10: "	.$newq10."<br>";
//echo "newstat1: "	.$newstat1."<br>";
//echo "newstat2: "	.$newstat2."<br>";
//echo "newstat3: "	.$newstat3."<br>";
//echo "newstat4: "	.$newstat4."<br>";
//echo "newstat5: "	.$newstat5."<br>";
//echo "newstat6: "	.$newstat6."<br>";
//echo "newstat7: "	.$newstat7."<br>";
//echo "newstat8: "	.$newstat8."<br>";
//echo "newstat9: "	.$newstat9."<br>";
//echo "newstat10: "	.$newstat10."<br>";
//echo "newsaf1: "	.$newaf1."<br>";
//echo "newsaf2: "	.$newaf2."<br>";
//echo "newsaf3: "	.$newaf3."<br>";
//echo "newsaf4: "	.$newaf4."<br>";
//echo "newsaf5: "	.$newaf5."<br>";
//echo "newsaf6: "	.$newaf6."<br>";
//echo "newsaf7: "	.$newaf7."<br>";
//echo "newsaf8: "	.$newaf8."<br>";
//echo "newsaf9: "	.$newaf9."<br>";
//echo "newsaf10: "	.$newaf10."<br>";
	
$result = mysql_query("SELECT * FROM questions_cid_".$case_cid."") or die(mysql_error());
	$row = mysql_fetch_array($result);
	
	//check if there are new questions (newq)
	if (empty($newq1)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = 'INACTIVE' WHERE question = '".$question1."'") or die(mysql_error());
		$newstatset1 = "INACTIVE";
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question1."'") or die(mysql_error());
		mysql_query("INSERT INTO questions_cid_".$case_cid." (qid, question, autofail, status, seq_num, created_by, edited_by, create_date, edit_date) VALUES('', '".$newq1."', '".$newaf1."', 'ACTIVE', '".$seq_num1."', '".$PHP_AUTH_USER."', '".$PHP_AUTH_USER."', '".$currdt."', '".$currdt."' ) ") or die(mysql_error());
	}
	
	if (empty($newq2)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = 'INACTIVE' WHERE question = '".$question2."'") or die(mysql_error());
		$newstatset2 = "INACTIVE";
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question2."'") or die(mysql_error());
		mysql_query("INSERT INTO questions_cid_".$case_cid." (qid, question, autofail, status, seq_num, created_by, edited_by, create_date, edit_date) VALUES('', '".$newq2."', '".$newaf2."', 'ACTIVE', '".$seq_num2."', '".$PHP_AUTH_USER."', '".$PHP_AUTH_USER."', '".$currdt."', '".$currdt."' ) ") or die(mysql_error());  
	}
	
	if (empty($newq3)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = 'INACTIVE' WHERE question = '".$question3."'") or die(mysql_error());
		$newstatset3 = "INACTIVE";
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question3."'") or die(mysql_error());
		mysql_query("INSERT INTO questions_cid_".$case_cid." (qid, question, autofail, status, seq_num, created_by, edited_by, create_date, edit_date) VALUES('', '".$newq3."', '".$newaf3."', 'ACTIVE', '".$seq_num3."', '".$PHP_AUTH_USER."', '".$PHP_AUTH_USER."', '".$currdt."', '".$currdt."' ) ") or die(mysql_error());  
	}
	
	if (empty($newq4)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = 'INACTIVE' WHERE question = '".$question4."'") or die(mysql_error());
		$newstatset4 = "INACTIVE";
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question4."'") or die(mysql_error());
		mysql_query("INSERT INTO questions_cid_".$case_cid." (qid, question, autofail, status, seq_num, created_by, edited_by, create_date, edit_date) VALUES('', '".$newq4."', '".$newaf4."', 'ACTIVE', '".$seq_num4."', '".$PHP_AUTH_USER."', '".$PHP_AUTH_USER."', '".$currdt."', '".$currdt."' ) ") or die(mysql_error());  
	}
	
	if (empty($newq5)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = 'INACTIVE' WHERE question = '".$question5."'") or die(mysql_error());
		$newstatset5 = "INACTIVE";
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question5."'") or die(mysql_error());
		mysql_query("INSERT INTO questions_cid_".$case_cid." (qid, question, autofail, status, seq_num, created_by, edited_by, create_date, edit_date) VALUES('', '".$newq5."', '".$newaf5."', 'ACTIVE', '".$seq_num5."', '".$PHP_AUTH_USER."', '".$PHP_AUTH_USER."', '".$currdt."', '".$currdt."' ) ") or die(mysql_error());  
	}
	
	if (empty($newq6)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = 'INACTIVE' WHERE question = '".$question6."'") or die(mysql_error());
		$newstatset6 = "INACTIVE";
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question6."'") or die(mysql_error());
		mysql_query("INSERT INTO questions_cid_".$case_cid." (qid, question, autofail, status, seq_num, created_by, edited_by, create_date, edit_date) VALUES('', '".$newq6."', '".$newaf6."', 'ACTIVE', '".$seq_num6."', '".$PHP_AUTH_USER."', '".$PHP_AUTH_USER."', '".$currdt."', '".$currdt."' ) ") or die(mysql_error());  
	}
	
	if (empty($newq7)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = 'INACTIVE' WHERE question = '".$question7."'") or die(mysql_error());
		$newstatset7 = "INACTIVE";
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question7."'") or die(mysql_error());
		mysql_query("INSERT INTO questions_cid_".$case_cid." (qid, question, autofail, status, seq_num, created_by, edited_by, create_date, edit_date) VALUES('', '".$newq7."', '".$newaf7."', 'ACTIVE', '".$seq_num7."', '".$PHP_AUTH_USER."', '".$PHP_AUTH_USER."', '".$currdt."', '".$currdt."' ) ") or die(mysql_error());  
	}
	
	if (empty($newq8)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = 'INACTIVE' WHERE question = '".$question8."'") or die(mysql_error());
		$newstatset8 = "INACTIVE";
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question8."'") or die(mysql_error());
		mysql_query("INSERT INTO questions_cid_".$case_cid." (qid, question, autofail, status, seq_num, created_by, edited_by, create_date, edit_date) VALUES('', '".$newq8."', '".$newaf8."', 'ACTIVE', '".$seq_num8."', '".$PHP_AUTH_USER."', '".$PHP_AUTH_USER."', '".$currdt."', '".$currdt."' ) ") or die(mysql_error());  
	}
	
	if (empty($newq9)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = 'INACTIVE' WHERE question = '".$question9."'") or die(mysql_error());
		$newstatset9 = "INACTIVE";
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question9."'") or die(mysql_error());
		mysql_query("INSERT INTO questions_cid_".$case_cid." (qid, question, autofail, status, seq_num, created_by, edited_by, create_date, edit_date) VALUES('', '".$newq9."', '".$newaf9."', 'ACTIVE', '".$seq_num9."', '".$PHP_AUTH_USER."', '".$PHP_AUTH_USER."', '".$currdt."', '".$currdt."' ) ") or die(mysql_error());  
	}
	
	if (empty($newq10)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = 'INACTIVE' WHERE question = '".$question10."'") or die(mysql_error());
		$newstatset10 = "INACTIVE";
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question10."'") or die(mysql_error());
		mysql_query("INSERT INTO questions_cid_".$case_cid." (qid, question, autofail, status, seq_num, created_by, edited_by, create_date, edit_date) VALUES('', '".$newq10."', '".$newaf10."', 'ACTIVE', '".$seq_num10."', '".$PHP_AUTH_USER."', '".$PHP_AUTH_USER."', '".$currdt."', '".$currdt."' ) ") or die(mysql_error());  
	}
	
	//check if there are changes in status (newstat)
	if (empty($newstat1)) {
		//do nothing
	} else {
		if (isset($newstatset1)) {
			$newstatfull1 = "INACTIVE";
		} elseif ($newstat1 == "Y") {
			$newstatfull1 = "ACTIVE";
		} else {
			$newstatfull1 = "INACTIVE";
		}
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = '".$newstatfull1."' WHERE question = '".$question1."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question1."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question1."'") or die(mysql_error());
	}
	
	if (empty($newstat2)) {
		//do nothing
	} else {
		if (isset($newstatset2)) {
			$newstatfull2 = "INACTIVE";
		} elseif ($newstat2 == "Y") {
			$newstatfull2 = "ACTIVE";
		} else {
			$newstatfull2 = "INACTIVE";
		}
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = '".$newstatfull2."' WHERE question = '".$question2."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question2."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question2."'") or die(mysql_error());
	}
	
	if (empty($newstat3)) {
		//do nothing
	} else {
		if (isset($newstatset3)) {
			$newstatfull3 = "INACTIVE";
		} elseif ($newstat3 == "Y") {
			$newstatfull3 = "ACTIVE";
		} else {
			$newstatfull3 = "INACTIVE";
		}
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = '".$newstatfull3."' WHERE question = '".$question3."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question3."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question3."'") or die(mysql_error());
	}
	
	if (empty($newstat4)) {
		//do nothing
	} else {
		if (isset($newstatset4)) {
			$newstatfull4 = "INACTIVE";
		} elseif ($newstat4 == "Y") {
			$newstatfull4 = "ACTIVE";
		} else {
			$newstatfull4 = "INACTIVE";
		}
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = '".$newstatfull4."' WHERE question = '".$question4."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question4."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question4."'") or die(mysql_error());
	}
	
	if (empty($newstat5)) {
		//do nothing
	} else {
		if (isset($newstatset5)) {
			$newstatfull5 = "INACTIVE";
		} elseif ($newstat5 == "Y") {
			$newstatfull5 = "ACTIVE";
		} else {
			$newstatfull5 = "INACTIVE";
		}
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = '".$newstatfull5."' WHERE question = '".$question5."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question5."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question5."'") or die(mysql_error());
	}
	
	if (empty($newstat6)) {
		//do nothing
	} else {
		if (isset($newstatset6)) {
			$newstatfull6 = "INACTIVE";
		} elseif ($newstat6 == "Y") {
			$newstatfull6 = "ACTIVE";
		} else {
			$newstatfull6 = "INACTIVE";
		}
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = '".$newstatfull6."' WHERE question = '".$question6."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question6."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question6."'") or die(mysql_error());
	}
	
	if (empty($newstat7)) {
		//do nothing
	} else {
		if (isset($newstatset7)) {
			$newstatfull7 = "INACTIVE";
		} elseif ($newstat7 == "Y") {
			$newstatfull7 = "ACTIVE";
		} else {
			$newstatfull7 = "INACTIVE";
		}
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = '".$newstatfull7."' WHERE question = '".$question7."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question7."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question7."'") or die(mysql_error());
	}
	
	if (empty($newstat8)) {
		//do nothing
	} else {
		if (isset($newstatset8)) {
			$newstatfull8 = "INACTIVE";
		} elseif ($newstat8 == "Y") {
			$newstatfull8 = "ACTIVE";
		} else {
			$newstatfull8 = "INACTIVE";
		}
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = '".$newstatfull8."' WHERE question = '".$question8."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question8."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question8."'") or die(mysql_error());
	}
	
	if (empty($newstat9)) {
		//do nothing
	} else {
		if (isset($newstatset9)) {
			$newstatfull9 = "INACTIVE";
		} elseif ($newstat9 == "Y") {
			$newstatfull9 = "ACTIVE";
		} else {
			$newstatfull9 = "INACTIVE";
		}
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = '".$newstatfull9."' WHERE question = '".$question9."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question9."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question9."'") or die(mysql_error());
	}
	
	if (empty($newstat10)) {
		//do nothing
	} else {
		if (isset($newstatset10)) {
			$newstatfull10 = "INACTIVE";
		} elseif ($newstat10 == "Y") {
			$newstatfull10 = "ACTIVE";
		} else {
			$newstatfull10 = "INACTIVE";
		}
		mysql_query("UPDATE questions_cid_".$case_cid." SET status = '".$newstatfull10."' WHERE question = '".$question10."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question10."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question10."'") or die(mysql_error());
	}
	
	//check if there are changes in autofail (newaf)
	
	if (empty($newaf1)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET autofail = '".$newaf1."' WHERE question = '".$question1."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question1."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question1."'") or die(mysql_error());
	}
	
	if (empty($newaf2)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET autofail = '".$newaf2."' WHERE question = '".$question2."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question2."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question2."'") or die(mysql_error());
	}
	
	if (empty($newaf3)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET autofail = '".$newaf3."' WHERE question = '".$question3."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question3."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question3."'") or die(mysql_error());
	}
	
	if (empty($newaf4)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET autofail = '".$newaf4."' WHERE question = '".$question4."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question4."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question4."'") or die(mysql_error());
	}
	
	if (empty($newaf5)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET autofail = '".$newaf5."' WHERE question = '".$question5."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question5."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question5."'") or die(mysql_error());
	}
	
	if (empty($newaf6)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET autofail = '".$newaf6."' WHERE question = '".$question6."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question6."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question6."'") or die(mysql_error());
	}
	
	if (empty($newaf7)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET autofail = '".$newaf7."' WHERE question = '".$question7."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question7."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question7."'") or die(mysql_error());
	}
	
	if (empty($newaf8)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET autofail = '".$newaf8."' WHERE question = '".$question8."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question8."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question8."'") or die(mysql_error());
	}
	
	if (empty($newaf9)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET autofail = '".$newaf9."' WHERE question = '".$question9."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question9."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question9."'") or die(mysql_error());
	}
	
	if (empty($newaf10)) {
		//do nothing
	} else {
		mysql_query("UPDATE questions_cid_".$case_cid." SET autofail = '".$newaf10."' WHERE question = '".$question10."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edited_by = '".$PHP_AUTH_USER."' WHERE question = '".$question10."'") or die(mysql_error());
		mysql_query("UPDATE questions_cid_".$case_cid." SET edit_date = '".$currdt."' WHERE question = '".$question10."'") or die(mysql_error());
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

</style>



</head>
<BODY BGCOLOR="white" marginheight="5" marginwidth="0" leftmargin="0" topmargin="5">
<CENTER>
<TABLE WIDTH="720" BGCOLOR="#D9E6FE" cellpadding="10" cellspacing="0" border = "0">
	<TR BGCOLOR="#015B91">
		<TD ALIGN="LEFT">
			<a href="./admin.php"><FONT FACE="ARIAL,HELVETICA" COLOR=WHITE SIZE=2><B>VICIDIAL ADMIN</a>: QA Editor
		</TD>
		<TD ALIGN="RIGHT">
			<FONT FACE="ARIAL,HELVETICA" COLOR="WHITE"><B><?php echo date("l F j, Y G:i:s A") ?></font>
		</TD>
	</TR> 
	<TR>
		<TD ALIGN=LEFT COLSPAN=2>

		<TABLE width="100%" cellspacing="0" cellpadding="5" align="center" border = "0">
				<tr>
					<td align="left" bgcolor="#9BB9FB">
						<b>Update Results</b>
					</td>
				</tr>
				<tr>
					<td align="center" bgcolor="#B9CBFD">
						<font size="+2"><b>Edits Successful!</b></font>
					</td>
				</tr>
				<tr>
					<td align="center" bgcolor="#9BB9FB">
						<input type ="button" onclick="location.href='AST_comments_supedit_p2.php?campaigns=<?php echo $case_cid; ?>'" value="Back to Edits" />
						<input type ="button" onclick="location.href='AST_comments_supedit.php'" value="Back to Search" />
					</td>
				</tr>
			</table>
	
		</td>
	</tr>
</table>
		<br><br>

</body>
</html>
