<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>
 
<body>
    <div class="container">
        <div class="row">
            <h3>PHP CRUD Grid</h3>
        </div>
        <div class="row">
            <p><a href="create.php" class="btn btn-success">Create</a></p>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                    <th>FirstName</th>
                    <th>LastName</th>
                    <th>UserID</th>
                    <th>Type</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                 include 'Clases/database.php';
                 $pdo = Database::connect();
                 $sql = 'SELECT * FROM Identity ORDER BY Iden_id DESC';
                 foreach ($pdo->query($sql) as $row) {
                          echo '<tr>';
                          echo '<td>'. $row['FirstName'] . '</td>';
                          echo '<td>'. $row['LastName'] . '</td>';
                          echo '<td>'. $row['Type'] . '</td>';
                          echo '<td><a class="btn" href="read.php?id='.$row['Iden_id'].'">Read</a></td>';
                          echo '</tr>';
                 }
                 Database::disconnect();
                ?>
                </tbody>
            </table>
        </div>
    </div> <!-- /container -->
  </body>
</html>