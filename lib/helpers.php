<?php
/* HELPERS.PHP
 *      This file gets included by other files and contains variable/function definitions
 */
 
//  If a string has any spaces, POST will automatically replace those with underscores.
//  clean_word() takes the string and replaces the underscores with spaces, un-doing work of POST.
function clean_word($str) {
    $str = str_replace("_", " ", $str);
    $str = ucwords($str);
    return $str;
}

//  render() is from the Prof, and gets page content & creates the page title.
function render($template, $title="") {
        $path = ROOT.'/views/' . $template . '.php';
        require_once($path);
    }

// a class for all the submit buttons, just some practice for OOP PHP
class Submit_Button {
    private $name;  
    private $value;
    
    public function __construct($a, $b) {
        $this->name = $a;   
        $this->value = $b;
    }

    public function make_button() {
        echo "<input type=\"submit\" name=\"$this->name\" value = \"$this->value\" />";
    }   

}
?> 


