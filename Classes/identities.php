<h2>Identities</h2>
<a class="btn icon-btn btn-success" href="identity.php?op=new">
<span class="glyphicon btn-glyphicon glyphicon-plus img-circle text-success"></span>Add</a>
<p></p>
<!-- <div><a href="identity.php?op=new">Add new contact</a></div> -->
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th><a href="identity.php?orderby=FirstName">First Name</a></th>
            <th><a href="identity.php?orderby=LastName">Last Name</a></th>
            <th><a href="identity.php?orderby=UserID">UserID</a></th>
            <th><a href="identity.php?orderby=Type">Type</a></th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
     <?php foreach ($rows as $row):
        ?>
        <tr>
            <td><a href="identity.php?op=show&id=<?php print $row['Iden_Id']; ?>"><?php print "<span class=\"glyphicon glyphicon-pencil\">". htmlentities($row['FirstName']); ?></<span></a></td>
            <td><?php print htmlentities($row['LastName']); ?></td>
            <td><?php print htmlentities($row['UserID']); ?></td>
            <td><?php print htmlentities($row['Type']); ?></td>
            <td><a class="btn icon-btn btn-danger" href="identity.php?op=delete&id=<?php print $row['Iden_Id']; ?>">
                    <span class="glyphicon btn-glyphicon glyphicon-minus img-circle text-warning"></span> Remove</a></td>
        </tr>     
        <?php endforeach; ?>
    </tbody>
</table>
 