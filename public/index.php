<?php
/* INDEX.PHP
 * 		Controller file for MVC.
 */

// Helpers.php is a file taken from the course professor, it implements a bit of MVC structure.
// I'm incorporating it to try to understand how MVC works.
// The render function is inside helpers.php and does: 1) include the page content if it exists and 2) generate the title of a page.
require_once('../lib/config.php'); 
require_once('../lib/helpers.php'); 

 // This if statement checks to see if the $GET page variable matches any file names in the directory.
 // If customer tries to enter a random string for the page, it'll print an error message. (eg:  http://localhost/port/pizzamenu/controller/?page=noExist )
 	if(isset($_GET['page'])) {
        // extract the name of the page to retrieve.
        $page = trim( htmlspecialchars($_GET['page']) );       // remove whitespace and convert any html code to a string
        $page = str_replace(".php", "", $page);                // remove the .php extension

        if ($page === 'index') {                               // the index home page will get the login page
            $page = 'home';
        }
        
        // if the header file, $page file and footer file are in their directories...
        // display those pages.  render() is a helper function.
        if( file_exists(VIEW.'/templates/header.php') AND file_exists(VIEW.'/'.$page.'.php') AND file_exists(VIEW.'/templates/footer.php')  ) {
            render('templates/header', 'CS75 Pizza | '.ucwords($page));       // display header
            require_once(VIEW.'/'.$page.'.php');                               // display page specified by $_GET
            render('templates/footer');                                        // display footer
        }
        else {
            render('templates/header', 'CS75 Pizza | 404');                    // else the page doesn't exist, display the 'no exist' page
            require_once(VIEW.'/no_exist.php');
            render('templates/footer');                                
        }       
    }
    // else means $_GET isn't set, display the home page
    else {
        if( file_exists(VIEW.'/templates/header.php') AND file_exists(VIEW.'/home.php') AND file_exists(VIEW.'/templates/footer.php')  ) {
            render('templates/header', 'CS75 Pizza | Home');
            require_once(VIEW.'/home.php');
            render('templates/footer');     
        }
        else {
            render('templates/header', 'CS75 Pizza | 404');
            require_once(VIEW.'/no_exist.php');
            render('templates/footer');
        }           
    } 
?>
 	
 	
 
 
    
    