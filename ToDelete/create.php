<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-select.min.css" rel="stylesheet">
        
</head>
 
<body>
    <?php  
    require 'Clases/Identity.php';
 
    if ( !empty($_POST)) {
        //print_r($_POST);
        // keep track validation errors
        $FirstNameError = null;
        $LastNameError = null;
        $TypeError = null;

        // keep track post values
        $FirstName = $_POST['FirstName'];
        $LastName = $_POST['LastName'];
        $UserID = $_POST['UserID'];
        $type = $_POST['type'];

        // validate input
        $valid = true;
        if (empty($FirstName)) {
            $FirstNameError = 'Please enter First Name';
            $valid = false;
        }
        if (empty($LastName)) {
            $LastNameError = 'Please enter First Name';
            $valid = false;
        }
//        if (empty($email)) {
//            $emailError = 'Please enter Email Address';
//            $valid = false;
//        } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
//            $emailError = 'Please enter a valid Email Address';
//            $valid = false;
//        }
//
        if (empty($type)) {
            $TypeError = 'Please select a Type';
            $valid = false;
        }

        // insert data
        if ($valid) {
            $AdminName = "Root";
            Identity::create($FirstName,$LastName,$UserID,$type,$AdminName);
            header("Location: index.php");
        }
    }
?>
    <div class="container">

        <div class="span10 offset1">
            <div class="row">
                <h3>Create a Identity</h3>
            </div>

            <form class="form-horizontal" action="create.php" method="post">
                <div class="control-group <?php echo !empty($FirstNameError)?'error':'';?>">
                    <label class="control-label">First Name</label>
                    <div class="controls">
                        <input name="FirstName" type="text"  placeholder="FirstName" value="<?php echo !empty($FirstName)?$FirstName:'';?>">
                        <?php if (!empty($FirstNameError)): ?>
                            <span class="text-danger"><?php echo $FirstNameError;?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="control-group <?php echo !empty($LastNameError)?'error':'';?>">
                  <label class="control-label">Last Name</label>
                  <div class="controls">
                      <input name="LastName" type="text" placeholder="LastName" value="<?php echo !empty($LastName)?$LastName:'';?>">
                      <?php if (!empty($LastNameError)): ?>
                          <span class="text-danger"><?php echo $LastNameError;?></span>
                      <?php endif;?>
                  </div>
                </div>
                <div class="control-group">
                    <label class="control-label">UserID</label>
                    <div class="controls">
                        <input name="UserID" type="text"  placeholder="UserID" value="<?php echo !empty($UserID)?$UserID:'';?>">
                    </div>
                </div>
                <div class="control-group <?php echo !empty($TypeError)?'error':'';?>">
                    <label class="control-label">Type</label>
                    <div class="controls">
                        <?php if (!empty($TypeError)): ?>
                          <span class="text-danger"> <?php echo $TypeError;?></span>
                        <?php endif;?>
                          <select name="type" class="selectpicker">
                              <option value=""></option>
                              <option value="1">Internal</option>
                              <option value="2">External</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-success">Create</button>
                    <a class="btn" href="index.php">Back</a>
                </div>
            </form>
        </div>
    </div> <!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-select.js"></script>
</body>
</html>