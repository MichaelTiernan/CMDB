<H2>CMDB Administrator overview</H2>
<?php
if ($ViewAccess){
	if ($AddAccess){
		echo "<a class=\"btn icon-btn btn-success\" href=\"Admin.php?op=new\">";
		echo "<span class=\"glyphicon btn-glyphicon glyphicon-plus img-circle text-success\"></span>Add</a>";
	}
	echo " <a href=\"Admin.php\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>";
	echo "<p></p>";
	echo "<table class=\"table table-striped table-bordered\">";
	echo "<thead>";
	echo "<tr>";
	echo "<th>Name</th>";
	echo "<th>Active</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	foreach ($rows as $row):
	echo "<tr>";
	echo "<td>".htmlentities($row['Account'])."</td>";
	echo "<td>".htmlentities($row['Level'])."</td>";
	echo "</tr>";
	endforeach;
	echo "</tbody>";
	echo "</table>";
	
	echo "<H3>Log overview</H3>";
	if (!empty($logrows)){
		echo "<table class=\"table table-striped table-bordered\">";
		echo "<thead>";
		echo "<tr>";
		echo "<th>Date</th>";
		echo "<th>Text</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
		foreach ($logrows as $log){
			echo "<tr>";
			echo "<td class=\"small\">".htmlentities(date($this->getLogDateFormat(), strtotime($log["Log_Date"])))."</td>";
			echo "<td class=\"small\">".htmlentities($log["Log_Text"])."</td>";
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";
	}  else {
		echo "No Log entries found for this Administrator";
	}
}else {
    $this->showError("Application error", "You do not access to this page");
}