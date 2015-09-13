<?php

/**
 *  DB_Model
 * 
 * Our sudo data model
 *
 * @author David J. McCaskill
 */
class DB_Model {
   
    public function create($data){
	
	if(isset($data->id)) {
	    /*
	     * do database insert
	     */
	    return "SQL Insert";
	} else {
	    return false;
	}
    }
    
    public function read($data){
	
	if(isset($data->id)) {
	    /*
	     * do database select
	     */
	    return "SQL Select";
	} else {
	    return false;
	}
	
    }
    
    public function update($data){
	
	if(isset($data->id)) {
	    /*
	     * do database update
	     */
	    return "SQL Update";
	} else {
	    return false;
	}
	
    }
    
    public function delete($data){
	
	if(isset($data->id)) {
	    /*
	     * do database delete
	     */
	    return "SQL Delete";
	} else {
	    return false;
	}
    }
    
    
}
