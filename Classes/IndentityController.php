<?php

require_once ('IdentityService.php');
class IndentityController {
    private $identityService = NULL;
    
    public function __construct() {
        $this->identityService = new IdentityService();
    }
    
    public function redirect($location) {
        header('Location: '.$location);
    }
    
    public function handleRequest() {
        $op = isset($_GET['op'])?$_GET['op']:NULL;
        try {
            if ( !$op || $op == 'list' ) {
                $this->listIdentity();
            } elseif ( $op == 'new' ) {
                $this->saveIdentity();
            } elseif ( $op == 'delete' ) {
                $this->deleteIdentity();
            } elseif ( $op == 'show' ) {
                $this->showIdentity();
            } else {
                $this->showError("Page not found", "Page for operation ".$op." was not found!");
            }
        } catch ( Exception $e ) {
            // some unknown Exception got through here, use application error page to display it
            $this->showError("Application error", $e->getMessage());
        }
    }
    public function listIdentity() {
        //$orderby = isset($_GET['orderby'])?$_GET['orderby']:NULL;
        if (isset($_GET['orderby'])){
            $orderby = $_GET['orderby'];
        }else{
            $orderby = "";
        }
        //echo "IdentityController.listIdentity Orderby = ".$orderby."<br>";
        $rows = $this->identityService->getAllContacts($orderby);
        include 'identities.php';
    }
    
    public function saveIdentity() {
       
        $title = 'Add new Identity';
        
        $name = '';
        $phone = '';
        $email = '';
        $address = '';
       
        $errors = array();
        
        if ( isset($_POST['form-submitted']) ) {
            
            $name       = isset($_POST['name']) ?   $_POST['name']  :NULL;
            $phone      = isset($_POST['phone'])?   $_POST['phone'] :NULL;
            $email      = isset($_POST['email'])?   $_POST['email'] :NULL;
            $address    = isset($_POST['address'])? $_POST['address']:NULL;
            
            try {
                $this->contactsService->createIdentity($name, $phone, $email, $address);
                $this->redirect('index.php');
                return;
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            }
        }
        
        include 'view/contact-form.php';
    }
    
    public function deleteIdentity() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        
        $this->contactsService->deleteIdentity($id);
        
        $this->redirect('index.php');
    }
    
    public function showIdentity() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $contact = $this->contactsService->getIdentity($id);
        
        include '../view/identity.php';
    }
    
    public function showError($title, $message) {
        ?>
        <h1><?php print htmlentities($title) ?></h1>
        <p>
            <?php print htmlentities($message) ?>
        </p>
        <?php
    }
}
