<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>
        <?php print htmlentities($title) ?>
        </title>
    </head>
    <body>
        <?php
        if ( $errors ) {
            print '<ul class="errors">';
            foreach ( $errors as $field => $error ) {
                print '<li>'.htmlentities($error).'</li>';
            }
            print '</ul>';
        }
        ?>
        <form method="POST" action="">
            <label for="name">Name:</label><br/>
            <input type="text" name="name" value="<?php print htmlentities($name) ?>"/>
            <br/>
            
            <label for="phone">Phone:</label><br/>
            <input type="text" name="phone" value="<?php print htmlentities($phone) ?>"/>
            <br/>
            <label for="email">Email:</label><br/>
            <input type="text" name="email" value="<?php print htmlentities($email) ?>" />
            <br/>
            <label for="address">Address:</label><br/>
            <textarea name="address"><?php print htmlentities($address) ?></textarea>
            <br/>
            <input type="hidden" name="form-submitted" value="1" />
            <input type="submit" value="Submit" />
        </form>
        
    </body>
</html>
