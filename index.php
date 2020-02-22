<!DOCTYPE html>
<html>
<head>
<title>Cambrian - Erick Araujo</title>

<script language="javascript">
	var isInserting = false;
	
	const deleteRow = (rowId, rowTitle) => {
		document.getElementById('hid').value = rowId;
		return confirm("Would you like to delete " + rowTitle);
	};
	
	const deleteRenderedRow = (field) => {
		const table = document.getElementById('myTable');
		table.deleteRow(field.parentNode.parentNode.rowIndex);
		
		isInserting = false;
		
		return false;
	};
	
	const prepareRow = (e) => {
		e.preventDefault();
		
		if(isInserting){
			return false;
		}

		const table = document.getElementById('myTable');
		const row = document.createElement('tr');
		const col1 = document.createElement('th');
		const col2 = document.createElement('td');
		const col3 = document.createElement('td');
		
		row.className = "d-flex";
		col1.className = "col-2";
		col2.className = "col-8";
		col3.className = "col-2";
		
		const cRow = table.rows[table.rows.length - 1];
		const id = parseInt(cRow.cells[0].innerText) + 1;
		
		col1.innerHTML = "<input id='deptId' name='deptId' value='" + id + "' type='text' readonly style='width:35px' />";
		col2.innerHTML = "<input id='deptName' name='deptName' type='text' class='col-8' />";
		col3.innerHTML = 
			"<button type='submit' name='insert' value='insert' class='btn btn-success'>+</button>" +
			"<button class='ml-2 btn btn-light' onClick='return deleteRenderedRow(this)'>X</button>";
		
		row.appendChild(col1);
		row.appendChild(col2);
		row.appendChild(col3);
		
		table.tBodies[0].appendChild(row);
		
		isInserting = true;

		return false;
	};
</script>
</head>
<body>

<h1>MySQL Connection</h1>

<?php
$host = "192.168.64.2";
$username = "erick";
$password = "12345678";

$conn = new PDO("mysql:host=$host;dbname=db", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<?php

if(isset($_POST['insert'])){
	if(isset($_POST['deptId'])){
		$deptId = $_POST['deptId'];
	}
	
	if(isset($_POST['deptName'])){
		$deptName = $_POST['deptName'];
		
	}
	
	$sql2 = "INSERT INTO departments VALUES ($deptId, '$deptName')";
	$result2 = $conn->exec($sql2);
	
	echo "<div class='alert alert-success'>Data inserted with success</div>";
}

if(isset($_POST["delete"])){
	if(isset($_POST["hid"])){
		$hid = $_POST['hid'];
		
		$sql3 = "DELETE FROM departments WHERE department_number = $hid";
		$result3 = $conn->exec($sql3);
	
		echo "<div class='alert alert-success'>Data deleted with success</div>";
	}
}

?>


<form method="post">

<div class="jumbotron">

<?php

try{
	$sql = "SELECT department_number, department_name FROM departments";
	$result = $conn->query($sql, PDO::FETCH_ASSOC);

	echo "<table id=\"myTable\" class=\"table table-hover table-dark custom_table\">";
	echo "<tr class=\"d-flex\">";
	echo "<th class=\"col-2\">ID</th>";
	echo "<th class=\"col-8\">NAME</th>";
	echo "<th class=\"col-2\">ACTION</th>";
	echo "</tr>";

	foreach($result as $row){
		echo "<tr class=\"d-flex\">";
		echo "<th class=\"col-2\">" . $row["department_number"] . "</th>";
		echo "<td class=\"col-8\">" . $row["department_name"] . "</td>";
		echo "<td class=\"col-2\">";
		echo "<button type=\"submit\" class=\"btn btn-danger\" name=\"delete\" value=\"delete\" onClick=\"return deleteRow(" . $row["department_number"] . ", '" . $row["department_name"] . "')\">X</button>";
		echo "</td>";
		echo "</tr>";
	}
	
	echo "</table>";
	
	echo "<input type=\"hidden\" name=\"hid\" id=\"hid\" />";

}catch(PDOException $e){
	die("Error: " . $e);
}

?>

<button class="btn btn-dark" onClick="return prepareRow(event)">NEW DEPARTMENT</button>

</div>

</form>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>