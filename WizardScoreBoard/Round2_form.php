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
echo "<td>Round 1</td>";
print_r($this->reslult);
echo "</tr>";
echo "<tr>";
echo "<td>Round 2</td>";
for ($i =1 ; $i <= $amount;$i++){
	echo "<td><input type=\"text\" name=\"ReceivedPlayer".$i."\" placeholder=\"Set Received\"> <input type=\"text\" name=\"RequiredPlayer".$i."\" placeholder=\"Set Requested\"></td>";
}
echo "</tr>";
echo "</tbody>";
echo "</table>";
echo "<input type=\"hidden\" name=\"form-submitted\" value=\"1\" /><br>";
echo "<div class=\"form-actions\">";
echo "<button type=\"submit\" class=\"btn btn-success\">Next Round</button>";
echo "</div>";
echo "</form>";
?>