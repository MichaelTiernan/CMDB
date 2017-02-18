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
for ($i=1; $i <= $round-1; $i ++){
	echo "<tr>";
	echo "<td width=\"3%\">".$i.":</td>";
	foreach (${"resultsRound".$i} as $result){
		echo "<td class=\"col-md-3\">";
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
}
echo "<tr>";
echo "<td width=\"3%\">".$round.":</td>";
for ($i =1 ; $i <= $amount;$i++){
	echo "<td class=\"col-md-2\">";
	echo "Requested: <input type=\"text\" name=\"RequiredPlayer".$i."\" class=\"col-md-3\"><br>";
	echo "Received: <input type=\"text\" name=\"ReceivedPlayer".$i."\" class=\"col-md-3\">";
	echo "</td>";
}
echo "</tr>";
echo "</tbody>";
echo "</table>";
echo "<input type=\"hidden\" name=\"formRound".$round."-submitted\" value=\"1\" /><br>";
if ($lastround != 1){
	echo "<div class=\"form-actions\">";
	echo "<button type=\"submit\" class=\"btn btn-success\">Next Round</button>";
	echo "</div>";
}
echo "</form>";
?>