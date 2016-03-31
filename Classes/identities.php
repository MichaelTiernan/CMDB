<h2>Identities</h2>
<a class="btn icon-btn btn-success" href="identity.php?op=new">
<span class="glyphicon btn-glyphicon glyphicon-plus img-circle text-success"></span>Add</a>
<p></p>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th><a href="identity.php?orderby=Name">Name</a></th>
            <th><a href="identity.php?orderby=UserID">UserID</a></th>
            <th><a href="identity.php?orderby=E_Mail">E Mail</a></th>
            <th><a href="identity.php?orderby=Language">Language</a></th>
            <th><a href="identity.php?orderby=Type">Type</a></th>
            <th><a href="identity.php?orderby=Active">Active</a></th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
     <?php foreach ($rows as $row):
        ?>
        <tr>
            <td><?php print htmlentities($row['Name']); ?></<span></a></td>
            <td><?php print htmlentities($row['UserID']); ?></td>
            <td><?php print htmlentities($row['E_Mail']); ?></td>
            <td><?php print htmlentities($row['Language']); ?></td>
            <td><?php print htmlentities($row['Type']); ?></td>
            <td><?php print htmlentities($row['Active']); ?></td>
            <td><a class="btn btn-primary" href="identity.php?op=show&id=<?php print $row['Iden_Id']; ?>"><span class="glyphicon glyphicon-pencil"></span></a>
                <a class="btn icon-btn btn-danger" href="identity.php?op=delete&id=<?php print $row['Iden_Id']; ?>">
                    <span class="glyphicon btn-glyphicon glyphicon-minus img-circle text-warning"></span> Remove</a>
                    
            </td>
        </tr>     
        <?php endforeach; ?>
    </tbody>
</table>
 