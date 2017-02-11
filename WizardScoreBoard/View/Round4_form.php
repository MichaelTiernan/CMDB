<?php
print "<h2>" . htmlentities ($title) . "</h2>";
echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
echo "<table class=\"table table-striped table-bordered\">";
echo "<thead>";
echo "<tr>";
echo "<th>Round:</th>";
foreach ($players as $row):	
	echo "<th> Player: ".htmlentities($row['Name'])."</th>";
endforeach;
echo "</tr>";
echo "</thead>";
echo "<tbody>";
echo "<tr>";
echo "<td width=\"3%\">1:</td>";
foreach ($resulsRound1 as $result){
	echo "<td>";
	echo "<table class=\"table table-striped\">";
	echo "<thead>";
	echo "<tr>";
	echo "<th>Required</th>";
	echo "<th>Received</th>";
	echo "<th>Score</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	echo "<tr>";
	echo "<td>".$result["Required"]."</td>";
	echo "<td>".$result["Received"]."</td>";
	echo "<td>".$result["Score"]."</td>";
	echo "</tr>";
	echo "</tbody>";
	echo "</table>";
	echo "</td>";
}
echo "</tr>";
echo "<tr>";
echo "<td width=\"3%\">2:</td>";
foreach ($resulsRound2 as $result){
	echo "<td>";
	echo "<table class=\"table table-striped\">";
	echo "<thead>";
	echo "<tr>";
	echo "<th>Required</th>";
	echo "<th>Received</th>";
	echo "<th>Score</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	echo "<tr>";
	echo "<td>".$result["Required"]."</td>";
	echo "<td>".$result["Received"]."</td>";
	echo "<td>".$result["Score"]."</td>";
	echo "</tr>";
	echo "</tbody>";
	echo "</table>";
	echo "</td>";
}
echo "</tr>";
echo "<tr>";
echo "<td width=\"3%\">3:</td>";
foreach ($resulsRound3 as $result){
	echo "<td>";
	echo "<table class=\"table table-striped\">";
	echo "<thead>";
	echo "<tr>";
	echo "<th>Required</th>";
	echo "<th>Received</th>";
	echo "<th>Score</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	echo "<tr>";
	echo "<td>".$result["Required"]."</td>";
	echo "<td>".$result["Received"]."</td>";
	echo "<td>".$result["Score"]."</td>";
	echo "</tr>";
	echo "</tbody>";
	echo "</table>";
	echo "</td>";
}
echo "</tr>";
echo "<tr>";
echo "<td width=\"3%\">4:</td>";
for ($i =1 ; $i <= $amount;$i++){
	echo "<td>";
	echo "<table>";
	echo "<tr>";
	echo "<td>Requested</td>";
	echo "<td>Received</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><input type=\"text\" name=\"RequiredPlayer".$i."\" class=\"col-md-3\"></td>";
	echo "<td><input type=\"text\" name=\"ReceivedPlayer".$i."\" class=\"col-md-3\"></td>";
	echo "</tr>";
	echo "</table>";
	echo "</td>";
}
echo "</tr>";
echo "</tbody>";
echo "</table>";
echo "<input type=\"hidden\" name=\"formRound4-submitted\" value=\"1\" /><br>";
echo "<div class=\"form-actions\">";
echo "<button type=\"submit\" class=\"btn btn-success\">Next Round</button>";
echo "</div>";
echo "</form>";
?>