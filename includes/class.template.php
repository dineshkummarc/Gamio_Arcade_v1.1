<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}
	/**
	 * Simple template engine class (use [@tag] tags in your templates).
	 */
    class Template {
    	/**
    	 * The filename of the template to load.
    	 *
    	 * @access protected
    	 * @var string
    	 */
		
        protected $file;
		protected $lang = array();
        
        /**
         * An array of values for replacing each tag on the template (the key for each value is its corresponding tag).
         *
         * @access protected
         * @var array
         */
        protected $values = array();
        
        /**
         * Creates a new Template object and sets its associated file.
         *
         * @param string $file the filename of the template to load
         */
        public function __construct($file,$lang) {
            $this->file = $file;
			$this->lang = $lang;
        }
        
        /**
         * Sets a value for replacing a specific tag.
         *
         * @param string $key the name of the tag to replace
         * @param string $value the value to replace
         */
        public function set($key, $value) {
            $this->values[$key] = $value;
        }
        
        /**
         * Outputs the content of the template, replacing the keys for its respective values.
         *
         * @return string
         */
        public function output() {
        	/**
        	 * Tries to verify if the file exists.
        	 * If it doesn't return with an error message.
        	 * Anything else loads the file contents and loops through the array replacing every key for its value.
        	 */
            if (!file_exists($this->file)) {
            	return "Error loading template file ($this->file).<br />";
            }
            $output = file_get_contents($this->file);
            
            foreach ($this->values as $key => $value) {
				$tagToReplace = "[@$key]";
				$output = str_replace($tagToReplace, $value, $output);
            }
			
			foreach($this->lang as $k => $v) {
				$tagToReplace = "[#lang_$k]";
				$output = str_replace($tagToReplace, $v, $output);
			}
			
            return $output;
        }
        
        /**
         * Merges the content from an array of templates and separates it with $separator.
         *
         * @param array $templates an array of Template objects to merge
         * @param string $separator the string that is used between each Template object
         * @return string
         */
        static public function merge($templates, $separator = "\n") {
        	/**
        	 * Loops through the array concatenating the outputs from each template, separating with $separator.
        	 * If a type different from Template is found we provide an error message. 
        	 */
            $output = "";
            
            foreach ($templates as $template) {
            	$content = (get_class($template) !== "Template")
            		? "Error, incorrect type - expected Template."
            		: $template->output();
            	$output .= $content . $separator;
            }
            
            return $output;
        }
    }

?>