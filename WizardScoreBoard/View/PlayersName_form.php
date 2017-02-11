<?php
print "<h2>" . htmlentities ($title) . "</h2>";
if ( $errors ) {
	print '<ul class="list-group">';
	foreach ( $errors as $field => $error ) {
		print "<li class=\"list-group-item list-group-item-danger\">".htmlentities($error)."</li>";
	}
	print '</ul>';
}
echo "Please set the name of the players";
echo "<form class=\"form-horizontal\" action=\"\" method=\"post\">";
for ($i = 1; $i <= $amount; $i++){	
	echo "<div class=\"control-group\">";
	echo "<label class=\"control-label\">Payer ".$i." <span style=\"color: red;\">*</span></label>";
	echo "<div class=\"controls\">";
	echo "<input name=\"player".$i."\" type=\"text\" placeholder=\"Name of player".$i."\" value=\"".$Name."\">";
	echo "</div>";
	echo "</div>";
}	
echo "<input type=\"hidden\" name=\"form-PlayersSubmitted\" value=\"1\" /><br>";
echo "<div class=\"form-actions\">";
echo "<button type=\"submit\" class=\"btn btn-success\">Next</button>";
echo "</div>";
echo "<div class=\"form-group\">";
echo "<span class=\"text-muted\"><em><span style=\"color: red;\">*</span>";
echo "Indicates required field</em></span>";
echo "</div>";
echo "</form>";