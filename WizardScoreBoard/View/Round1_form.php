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
for ($i =1 ; $i <= $amount;$i++){
	echo "<td class=\"col-md-2\">";
	echo "Requested: <input type=\"text\" name=\"RequiredPlayer".$i."\" class=\"col-md-3\"><br>";
	echo "Received: <input type=\"text\" name=\"ReceivedPlayer".$i."\" class=\"col-md-3\">";
	echo "</td>";
}
echo "</tr>";
echo "</tbody>";
echo "</table>";
echo "<input type=\"hidden\" name=\"formRound1-submitted\" value=\"1\" /><br>";
echo "<div class=\"form-actions\">";
echo "<button type=\"submit\" class=\"btn btn-success\">Next Round</button>";
echo "</div>";
echo "</form>";
?>