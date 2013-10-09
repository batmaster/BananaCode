<?php
	include('../functions.php');
	connectdb();
	date_default_timezone_set('UTC');
	
		if($_POST['action']=='email') {
			// update the admin email
			if(trim($_POST['email']) == "")
				header("Location: index.php?derror=1");
			else {
				mysql_query("UPDATE users SET email='".mysql_real_escape_string($_POST['email'])."' WHERE username='".$_SESSION['username']."'");
				header("Location: index.php?changed=1");
			}
		} else if($_POST['action']=='password') {
			// update the admin password
			if(trim($_POST['oldpass']) == "" or trim($_POST['newpass']) == "")
				header("Location: index.php?derror=1");
			else {
				$query = "SELECT salt,hash FROM users WHERE username='admin'";
				$result = mysql_query($query);
				$fields = mysql_fetch_array($result);
				$currhash = crypt($_POST['oldpass'], $fields['salt']);
				if($currhash == $fields['hash']) {
					$salt = randomAlphaNum(5);
					$newhash = crypt($_POST['newpass'], $salt);
					mysql_query("UPDATE users SET hash='$newhash', salt='$salt' WHERE username='".$_SESSION['username']."'");
					header("Location: index.php?changed=1");
				} else
					header("Location: index.php?passerror=1");
			}
		} else if($_POST['action']=='settings') {
			// update the event settings
			if(trim($_POST['name']) == "")
				header("Location: index.php?derror=1");
			else {
				list($day, $month, $year, $hour, $minute) = split('[/ :]', $_POST['start']);
				$start=mktime($hour, $minute,0, $month, $day, $year);
				list($day, $month, $year, $hour, $minute) = split('[/ :]', $_POST['end']);
				$end=mktime($hour, $minute,0, $month, $day, $year);
				if($_POST['c']=='on') $c=1; else $c=0;
				if($_POST['cpp']=='on') $cpp=1; else $cpp=0;
				if($_POST['java']=='on') $java=1; else $java=0;
				if($_POST['python']=='on') $python=1; else $python=0;
				mysql_query("UPDATE prefs SET name='".mysql_real_escape_string($_POST['name'])."', start=$start, end=$end, c=$c, cpp=$cpp, java=$java, python=$python");
				header("Location: index.php?changed=1");
			}
		} else if($_POST['action']=='addproblem') {
			// add a problem
			if(trim($_POST['title']) == "" or trim($_POST['problem']) == "" or !is_numeric($_POST['time']))
				header("Location: problems.php?derror=1");
			else {
				$txt =  str_replace('\n', "<br>", mysql_real_escape_string($_POST['problem']));
				$query="INSERT INTO `problems` ( `name` , `text`, `time`, `points`, `addtime`) VALUES ('".mysql_real_escape_string($_POST['title'])."', '".$txt ."', '".$_POST['time']."', '" .$_POST['points']."', '" .time() ."')";
				mysql_query($query);
				
				for ($i = 0; $i <= $_POST['total-testcase-new']; $i++) {
					$query = "SELECT `sl` FROM `problems` WHERE `name`='" .mysql_real_escape_string($_POST['title']) ."'";
					$result = mysql_query($query);
					$sl = mysql_result($result, 0, "sl");
					$in = 'input' .$i;
					$out = 'output' .$i;
					$query = "INSERT INTO `testcase` (`sl`, `input`, `output`) VALUES (" .$sl .",'" .mysql_real_escape_string($_POST[$in]) ."','" .mysql_real_escape_string($_POST[$out]) ."')";
					mysql_query($query);
				}
				
				header("Location: problems.php?added=1");
			}
		} else if($_POST['action']=='editproblem' and is_numeric($_POST['id'])) {
			// update an already existing problem
			if(trim($_POST['title']) == "" or trim($_POST['problem']) == "" or !is_numeric($_POST['time']))
				header("Location: problems.php?derror=1&action=edit&id=".$_POST['id']);
			else {
				$txt =  str_replace('\n', "<br>", mysql_real_escape_string($_POST['problem']));
				$query = "UPDATE problems SET name='".mysql_real_escape_string($_POST['title'])."', text='".$txt ."', time='".$_POST['time']."' WHERE sl='".$_POST['id']."'";
				mysql_query($query);
				
				$query = "DELETE FROM testcase WHERE sl='" .$_POST['id'] ."'";
				mysql_query($query);
				
				for ($i = 0; $i <= $_POST['total-testcase-edit']; $i++) {
					$query = "SELECT `sl` FROM `problems` WHERE `name`='" .mysql_real_escape_string($_POST['title']) ."'";
					$result = mysql_query($query);
					$sl = mysql_result($result, 0, "sl");
					$in = 'input' .$i;
					$out = 'output' .$i;
					$query = "INSERT INTO `testcase` (`sl`, `input`, `output`) VALUES (" .$sl .",'" .mysql_real_escape_string($_POST[$in]) ."','" .mysql_real_escape_string($_POST[$out]) ."')";
					mysql_query($query);
				}
				
				header("Location: problems.php?updated=1&action=edit&id=".$_POST['id']);
			}
		} else if($_POST['action']=='updateformula') {
			mysql_query("UPDATE prefs SET formula='".$_POST['formula']."'");
			$fp = fopen('formula.php','w');
			fwrite($fp, "<?php\n".$_POST['formula']."\n?>");
			fclose($fp);
			header("Location: scoring.php?updated=1");
		}
		else if($_GET['action']=='delete' and is_numeric($_GET['id'])) {
			// delete an existing problem
			$query="DELETE FROM problems WHERE sl=".$_GET['id'];
			mysql_query($query);
			$query="DELETE FROM solve WHERE problem_id=".$_GET['id'];
			mysql_query($query);
			$query="DELETE FROM testcase WHERE sl=".$_GET['id'];
			mysql_query($query);
			header("Location: problems.php?deleted=1");
		} else if($_GET['action']=='ban') {
			// ban a user from the event
			$query="UPDATE users SET status=0 WHERE username='".mysql_real_escape_string($_GET['username'])."'";
			mysql_query($query);
			header("Location: users.php?banned=1");
		} else if($_GET['action']=='unban') {
			// unban a user from the event
			$query="UPDATE users SET status=1 WHERE username='".mysql_real_escape_string($_GET['username'])."'";
			mysql_query($query);
			header("Location: users.php?unbanned=1");
		}
?>
