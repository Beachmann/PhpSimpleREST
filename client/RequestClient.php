<?php

/**
 * Request Client
 *
 * @author      David J. McCaskill
 * @email       dave@codebuilderusa.com
 * @copyright   Copyright (C) 2015 David J. McCaskill
 * @license     http://opensource.org/licenses/LGPL-2.1
 * @version     1.0.0
 * 
 * 
 */

class RequestClient {

    /*
     * Content headers Content-Type: 
     */
    const CONTENT_TYPE_JSON = 'Content-Type: text/json; charset=utf-8';
    const CONTENT_TYPE_APP_XML = 'Content-Type: application/xml; charset=utf-8';
    const CONTENT_TYPE_HTML = 'Content-Type: text/html; charset=utf-8';
    

    public function send ($uri, $method, $contentType, $accept, $content) {
        /*
         * Setup header
         */
        $header = array (
                'http' => array (
                        'method' => $method,
                        'header'=> "Content-type: ".$contentType."; charset=utf-8\r\n"
                            . "Content-Length: " . strlen($content) . "\r\n"
                            . "Accept: ".$accept."\r\n",
                        'content' => $content
                    )
                );

        /*
         * Create context
         */
        $context = stream_context_create($header);

        /*
         * Open connection
         */
        $conn = fopen($uri, 'r', false, $context);

        /*
         * Get response headers
         */
        $metadata = stream_get_meta_data($conn); 
        
        /*
         * Get response content if any was sent
         */
        $content = stream_get_contents($conn);
        
        /*
         * Close connection
         */
        fclose($conn);
        
        /*
         * Get the HTTP response code
         */
        $response_header = $metadata['wrapper_data'][0];

        $response = array('content' => $content, 'header' => $response_header);
        
        return $response;
        
    }
    
    /**
     * Content type json
     * @return String
     */
    public function getContentTypeJson() {
        
        return self::CONTENT_TYPE_JSON;
    }
    
    /**
     * Content type xml
     * @return String
     */
    public function getContentTypeAppXml() {
        
        return self::CONTENT_TYPE_APP_XML;
    }
    
    /**
     * Content type html
     * @return String
     */
    public function getContentTypeHtml() {
        
        return self::CONTENT_TYPE_HTML;
    }
    
}
