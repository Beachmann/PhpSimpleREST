<?php
/**
 * Index
 *
 * @author      David J. McCaskill
 * @copyright   Copyright (C) 2015 David J. McCaskill
 * @license     http://opensource.org/licenses/LGPL-2.1
 * @version    1.0.0
 * 
 * This application is intended to illustrate an 
 * implementation of REST using PHP. My goal was to create a 
 * simple CRUD web service to demonstrate request and response 
 * handling as well as content negotation.
 */

require 'controllers/ResourceController.php';
require 'models/DB_Model.php';

/*
 * Instantiate our controller
 */
$resourceController = new ResourceController();


/*
 * Get the request method from the user request
 */
$method = $resourceController->getRequest_method();

/*
 * Select which CRUD method to use based on the request method
 */
switch ($method) {
    
    case "POST":
	
	$resourceController->create();
	break;
    
    case "GET":
	
	$resourceController->read();
	break;
    
    case "PUT":
	
	$resourceController->update();
	break;
    
    case "DELETE":
	
	$resourceController->delete();
	break;
    
    default:
	break;
}

