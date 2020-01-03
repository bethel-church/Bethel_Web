<?php
    /*
        * Admins Controller class
        * Functionality -  Manage the admin login,listing,add 
        * Developer - Navdeep
        * Created date - 11-Feb-2014
        * Modified date - 
    */
    	App::uses('Sanitize', 'Utility'); 
    class TripsController extends AppController {
	
        var $name = '';
      	var $uses = array();
        //var $components = array('Email','Cookie','Common');
		   
        function beforeFilter(){
            //parent::beforeFilter();    
            
        } 
        
        function index() {
          //echo "hello";exit();
          $this->redirect('/trips');
        }

}
?>