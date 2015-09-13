<?php

/**
 * Resource controller
 *
 * @author      David J. McCaskill
 * @email       dave@codebuilderusa.com
 * @copyright   Copyright (C) 2015 David J. McCaskill
 * @license     http://opensource.org/licenses/LGPL-2.1
 * @version     1.0.0
 * 
 */

class ResourceController {
    
    /*
     * Responce codes
     */
    const HTTP_200 = '200 OK';
    const HTTP_201 = '201 Created';
    const HTTP_400 = '400 Bad Request';
    const HTTP_404 = '404 Not Found';
    const HTTP_500 = '500 Internal Server Error';
    
    /*
     * Request methods 
     */
    const METHOD_POST	    = 'POST';
    const METHOD_GET	    = 'GET';
    const METHOD_PUT	    = 'PUT';
    const METHOD_DELETE	    = 'DELETE';
    
    /*
     * Content headers Content-Type: 
     */
    const CONTENT_TYPE_JSON = 'Content-Type: text/json; charset=utf-8';
    const CONTENT_TYPE_APP_XML = 'Content-Type: application/xml; charset=utf-8';
    const CONTENT_TYPE_HTML = 'Content-Type: text/html; charset=utf-8';

    /*
     * The method used for a request
     */
    protected $request_method = '';
    
    /*
     * Content type sent
     */
    protected $request_content_type = '';
    
    /*
     * The requested return format
     */
    protected $accept_header = '';
    
    /*
     * User Data
     */
    private $request_data = array();

    /*
     * Our sudo model
     */
    protected $data_model;
    
    function __construct() {
        try {
            /*
             * Set request method 
             */
            $this->request_method = $_SERVER['REQUEST_METHOD'];

            /*
             * See if accept header was sent
             */
            if(isset($_SERVER['HTTP_ACCEPT'])) {
                
                $this->accept_header = $_SERVER['HTTP_ACCEPT'];
            }
            
            /*
             * See if content type was sent
             */
            if(isset($_SERVER['CONTENT_TYPE'])) {
                
                $this->request_content_type = $_SERVER['CONTENT_TYPE'];
            }
            
            /*
             * Instantiate our DM
             * this is just a sudo model, replace it with the real thing.
             */
            $this->data_model = new DB_Model();

            /*
             * set user data for json
             */
            if ($this->request_content_type === 'text/json; charset=utf-8') {

                $content = file_get_contents("php://input");
                $this->request_data = json_decode($content);
            }
            
        } catch (Exception $e) {
            /*
             * Error
             */
            header('HTTP/1.1 '.self::HTTP_500);
        }  
    }
    
    public function create() {
	
	$data = $this->getRequest_data();
	$query = $this->data_model->create($data);
	
	if($query){
	    /*
	     * Request was good record created
	     */
	    header('HTTP/1.1 '.self::HTTP_201);
	    
	} else {
	    /*
	     * Bad request
	     */
	    header('HTTP/1.1 '.self::HTTP_400);
	}

        return;
    }
    
    public function read() {
	
	$data = $this->getRequest_data();
	
	if($this->accept_header !== '') {
            
            /**
             * Output buffering is not necessary but you may want to use it 
             * if you are concatenating views.
             */
	    ob_start();
            
	    $query = $this->data_model->read($data);
	    
	    /*
	     * No data found
	     */
	    if ($query === false) {
		header('HTTP/1.1 '.self::HTTP_404);
		return;
	    }
	    
	    /*
	     * Query was good
	     */
	    if($this->accept_header === self::CONTENT_TYPE_JSON) {
		
		/*
		 * User requested data be sent json
		 */
		header('HTTP/1.1 '.self::HTTP_200);
		header(self::CONTENT_TYPE_JSON);
		echo '[{ Response : '.$query.'}]';
		
	    } else if($this->accept_header === self::CONTENT_TYPE_APP_XML) {
		
		/*
		 * User requested data be sent xml
		 */
		header('HTTP/1.1 '.self::HTTP_200);
		header(self::CONTENT_TYPE_APP_XML);
		require 'views/xml_view.php';
		
	    } else if($this->accept_header === self::CONTENT_TYPE_HTML) {
		
		/*
		 * User requested data be sent html
		 */
		header('HTTP/1.1 '.self::HTTP_200);
		header(self::CONTENT_TYPE_HTML);
		require 'views/html_view.php';
		
	    } else {
		/*
		 * Wrong format requested
		 */
		header('HTTP/1.1 '.self::HTTP_400);
	    }
            
            /**
             * Send output
             */
	    ob_flush();
            
	} else {

	    //No accept header, show base view 
	    require 'views/index.php';
	}
        
	return;
    }
    
    public function update() {
	
	$data = $this->getRequest_data();
	$query = $this->data_model->update($data);
	
	if($query){
	    /*
	     * Request was good record updated
	     */
	    header('HTTP/1.1 '.self::HTTP_200);
	    
	} else {
	    /*
	     * Bad request
	     */
	    header('HTTP/1.1 '.self::HTTP_400);
	}
        
        return;
    }
    
    public function delete() {
	
	$data = $this->getRequest_data();
	$query = $this->data_model->delete($data);
	
	if($query){
	    /*
	     * Request was good record deleted
	     */
	    header('HTTP/1.1 '.self::HTTP_200);
	    
	} else {
	    /*
	     * Bad request
	     */
	    header('HTTP/1.1 '.self::HTTP_400);
	}

        return;
    }
    
    /*
     * getter for request method
     */
    public function getRequest_method() {
	return $this->request_method;
    }
   
    /*
     * getter for our private user data
     */
    protected function getRequest_data() {
	return $this->request_data;
    }
  
}
