<?php
	require_once('../functions.php');
	if(!loggedin())
		header("Location: login.php");
	else if($_SESSION['username'] !== 'admin')
		header("Location: login.php");
	else
		include('header.php');
		connectdb();
?>

<li class="active"><a id="Clock"></a>
<li>
<li class="active"><a href="#">Admin Panel</a></li>
<li><a href="users.php">Users</a></li>
<li><a href="preferences.php">Preferences</a></li>
<li><a href="logout.php">Logout</a></li>
</ul>
</div>
<!--/.nav-collapse -->
</div>
</div>
</div>
<div class="container">
	<?php
        if(isset($_GET['added']))
          echo("<div class=\"alert alert-success\">\nProblem added!\n</div>");
        else if(isset($_GET['deleted']))
          echo("<div class=\"alert alert-error\">\nProblem deleted!\n</div>");
        else if(isset($_GET['updated']))
          echo("<div class=\"alert alert-success\">\nProblem updated!\n</div>");
        else if(isset($_GET['derror']))
          echo("<div class=\"alert alert-error\">\nPlease enter all the details asked before you can continue!\n</div>");
      ?>
	<ul class="nav nav-tabs">
		<li><a href="index.php">General</a></li>
		<li class="active"><a href="#">Problems</a></li>
		<li><a href="scoring.php">Scoring</a></li>
	</ul>
	<div>
		<div> Below is a list of already added problems. Click on a problem to edit it.
			<ul class="nav nav-list">
				<li class="nav-header">ADDED PROBLEMS</li>
				<?php
            	  // list all the problems
            	  $query = "SELECT * FROM problems";
            	  $query = "SELECT * FROM problems ORDER BY addtime";
          	  $result = mysql_query($query);
          	  if(mysql_num_rows($result)==0)
          	    echo("<li>None</li>\n");
          	  else {
          	    while($row = mysql_fetch_array($result)) {
          	      if(isset($_GET['action']) and $_GET['action']=='edit' and isset($_GET['id']) and $_GET['id']==$row['sl']) {
          	        $selected = $row;
          	        echo("<li class=\"active\"><a href=\"problems.php?action=edit&id=".$row['sl']."\">".$row['name']."</a></li>\n");
          	      } else
          	        echo("<li><a href=\"problems.php?action=edit&id=".$row['sl']."\">".$row['name']."</a></li>\n");
          	    }
          	  }
          	?>
				<li class="divider"></li>
				<?php
          	  if(!isset($_GET['action']) and !isset($_GET['id']))
          	    echo("<li class=\"active\"><a href=\"#\">Add problem</a></li>\n");
          	  else
          	    echo("<li><a href=\"problems.php\">Add problem</a></li>\n");
          	?>
			</ul>
			<hr/>
			<?php
            if(isset($_GET['action']) and $_GET['action']=='edit') {
              // edit a selected problem
          ?>
			<h1><small>Edit a Problem</small></h1>
			<form method="post" action="update.php">
				<input type="hidden" name="action" value="editproblem" id="action"/>
				<input type="hidden" name="id" id="id" value="<?php echo($selected['sl']);?>"/>
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab1" data-toggle="tab">Problem</a></li>
					<li><a href="#tab2" data-toggle="tab">Test Case</a></li>
					<!--<li><a href="#tab3" data-toggle="tab">Sample Output</a></li>-->
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab1"> Problem Title:
						<input class="span8" type="text" id="title" name="title" value="<?php echo($selected['name']);?>"/>
						<br/>
						Maximum Points:
						<input class="span2" type="text" id="points" name="points" value="<?php echo($selected['points']);?>"/>
						<br/>
						<div class="controls">
							<div class="input-append"> Time Limit:
								<input class="span2" id="appendedInput" size="8" type="text" name="time" value="<?php echo($selected['time']); ?>">
								<span class="add-on">ms</span> </div>
						</div>
						<br/>
						Detailed problem: <span class="label label-info">Markdown formatting supported</span></br/><br/>
						<textarea style="width:785px; height:400px;" name="problem" id="text"><?php echo str_replace("<br>", "", $selected['text']);?></textarea>
						<br/>
					</div>
					<div class="tab-pane" id="tab2">
						<table class="table table-bordered" id="edit-problem-testcase">
							<thead>
								<tr>
									<th>Sample Input</th>
									<th>Sample Output</th>
								</tr>
							</thead>
							<tbody id="edit-problem-testcase-body">
								<?php
								$query = "SELECT `input`, `output` FROM `testcase` WHERE `sl`=" .$selected['sl'];
								$result = mysql_query($query);
								$num = mysql_num_rows($result);
								for ($i = 0; $i < $num; $i++) {
									echo "<tr>";
									echo "<td><textarea style=\"font-family: mono; width:385px; height:200px;\" name=\"input" .$i ."\">" .mysql_result($result, $i, 'input') ."</textarea></td>";
									echo "<td><textarea style=\"font-family: mono; width:385px; height:200px;\" name=\"output" .$i ."\">" .mysql_result($result, $i, 'output') ."</textarea></td>";
									echo "</tr>";
								}
							?>
							</tbody>
						</table>
						<input class="btn btn-success" type="button" value="Add Testcase" onclick="edit_problem_add_testcase()"/>
						<br>
						<br>
						<input type="hidden" name="total-testcase-edit" id="total-testcase-edit" value="">
						<script>    
							function edit_problem_add_testcase() 
							{
							  var tbody = document.getElementById('edit-problem-testcase-body');
							  var tr = document.createElement('tr');
							  var count = $('#edit-problem-testcase >tbody >tr').length;
							  var element = document.getElementById('total-testcase-edit');
							  element.setAttribute('value', count);
							  var td1 = document.createElement('td');
							  var txta1 = document.createElement('textarea');
							  txta1.setAttribute('style', 'font-family: mono; width:385px; height:200px;');
							  txta1.setAttribute('class', 'span9');
							  txta1.setAttribute('name', 'input' + count);
							  td1.appendChild(txta1);
							  
							  var td2 = document.createElement('td');
							  var txta2 = document.createElement('textarea');
							  txta2.setAttribute('style', 'font-family: mono; width:385px; height:200px;');
							  txta2.setAttribute('class', 'span9');
							  txta2.setAttribute('name', 'output' + count);
							  td2.appendChild(txta2);
							
							  tr.appendChild(td1);
							  tr.appendChild(td2);
							  tbody.appendChild(tr);
							};
						</script>
					</div>
				</div>
				<input class="btn btn-primary btn-large" type="submit" value="Update Problem"/>
				<input class="btn btn-large" type="button" value="Preview" onclick="$('#preview').load('preview.php', {action: 'preview', title: $('#title').val(), text: $('#text').val()});"/>
				<input class="btn btn-danger btn-large" type="button" value="Delete Problem" onclick="window.location='update.php?action=delete&id='+$('#id').val();"/>
			</form>
			<div id="preview"></div>
			<?php }else { // add a problem
          ?>
			<h1><small>Add a Problem</small></h1>
			<form method="post" action="update.php">
				<input type="hidden" name="action" value="addproblem"/>
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab1" data-toggle="tab">Problem</a></li>
					<li><a href="#tab2" data-toggle="tab">Test Case</a></li>
					<!--<li><a href="#tab3" data-toggle="tab">Sample Output</a></li>-->
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab1"> Problem Title:
						<input class="span8" type="text" id="title" name="title"/>
						<br/>
						Maximum Points:
						<input class="span2" type="text" id="points" name="points"/>
						<br/>
						<div class="controls">
							<div class="input-append"> Time Limit:
								<input class="span2" id="appendedInput" size="8" type="text" name="time">
								<span class="add-on">ms</span> </div>
						</div>
						<br/>
						Detailed problem: <span class="label label-info">Markdown formatting supported</span></br/><br/>
						<textarea style="height:400px;" class="span9" name="problem" id="text">
						<?php
							echo '
							
<span class="label label-success">Task</span>


<span class="label label-success">Input</span>


<span class="label label-success">Output</span>

';
						?>
						</textarea>
						<br/>
					</div>
					<div class="tab-pane" id="tab2">
						<table class="table table-bordered" id="new-problem-testcase">
							<thead>
								<tr>
									<th>Sample Input</th>
									<th>Sample Output</th>
								</tr>
							</thead>
							<tbody id="new-problem-testcase-body">
								<tr>
									<td><textarea style="font-family: mono; width:385px; height:200px;" class="span9" name="input0"></textarea></td>
									<td><textarea style="font-family: mono; width:385px; height:200px;" class="span9" name="output0"></textarea></td>
								</tr>
							</tbody>
						</table>
						<input class="btn btn-success" type="button" value="Add Testcase" onclick="new_problem_add_testcase()"/>
						<br>
						<br>
						<input type="hidden" name="total-testcase-new" id="total-testcase-new" value="">
						<script>    
							function new_problem_add_testcase() 
							{
							  var tbody = document.getElementById('new-problem-testcase-body');
							  var tr = document.createElement('tr');
							  var count = $('#new-problem-testcase >tbody >tr').length;
							  var element = document.getElementById('total-testcase-new');
							  element.setAttribute('value', count);
							  
							  var td1 = document.createElement('td');
							  var txta1 = document.createElement('textarea');
							  txta1.setAttribute('style', 'font-family: mono; width:385px; height:200px;');
							  txta1.setAttribute('class', 'span9');
							  txta1.setAttribute('name', 'input' + count);
							  td1.appendChild(txta1);
							  
							  var td2 = document.createElement('td');
							  var txta2 = document.createElement('textarea');
							  txta2.setAttribute('style', 'font-family: mono; width:385px; height:200px;');
							  txta2.setAttribute('class', 'span9');
							  txta2.setAttribute('name', 'output' + count);
							  td2.appendChild(txta2);
							
							  tr.appendChild(td1);
							  tr.appendChild(td2);
							  tbody.appendChild(tr);
							};
						</script>
					</div>
				</div>
				<input class="btn btn-primary btn-large" type="submit" value="Add Problem"/>
				<input class="btn btn-large" type="button" value="Preview" onclick="$('#preview').load('preview.php', {action: 'preview', title: $('#title').val(), text: $('#text').val()});"/>
			</form>
			<div id="preview"></div>
			<?php }?>
		</div>
	</div>
</div>
<!-- /container -->
<?php
	include('footer.php');
?>
