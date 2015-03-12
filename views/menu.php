<?php
/*  MENU.PHP 
 *  This file creates the menu for each food category.
 */ 

//  Each food category has their own file (pizzas.php, dinners.php, pastas.php, etc...)
//  Each of those files calls this files and supplies 2 variables that are used to search the XML DOM.
//  The two varibles are $selfs & $self and represent plural and singular of the food name (eg: pizzas and pizza). 
 
//  One project goal is to allow extensibility in the XML, and this code is written to build tables dynamically.
//  So if a new food category (eg: ice cream)  or sub-category (eg: kid-size pizzas) are added to the XML file, 
//  a menu will be generated.

    require_once(ROOT.'/lib/models.php');
?>
        <div class="food-menu">
            <form action="index.php?page=selections.php" method="POST">     
 <?php
//  "Type" refers to types of food.  Eg: By default there are "regular" pizzas & "specialty" pizzas.    
//  The code doesn't know beforehand what or how many types exist of each food item.  
//  The next section of code figures this out.
//  An array called $type is filled with the value of the type element of each food (eg: pizza) in the DOM. 
        $tree = getTree($dom, $selfs, $self);                   // get the XML DOM tree from models.php
        $type = getFoodType($dom, $tree);                       // $type[] is an array filled with the type of food
        
//  Next section builds a table for each type listed in the type[] array.
//  This first for loop iterates through the type[] array, and creates a header for each type (eg: for pizzas, types are regular, specialty, etc...).
        for($x=0; $x < count($type); $x++) {        
?>  
        <h1><?php echo ucwords($type[$x]." ".$selfs); ?></h1>
            <table border="1">                          
                <tr>
                    <th>Name</th>
                    <th>Prices<span><pre>(Some items only have 1 size)</pre></span></th>
                    <th>Select To Order</th>
                </tr>           
<?php   
//  This loop is nested, so the food type is still being kept track. 
//  This loop goes one level further in the DOM and iterates through each individual pizza for each pizza type.
//  For each individual pizza, the code verifies that the value of the type element matches the current type 
//  that previous for loop is iterating through.  If so, add that particular pizza to the current table.  If not, skip that pizza
        foreach($tree as $index=>$value) {
            if ((string)($value->type) == (string)($type[$x])) {                    // Verify that type in DOM = type listed in the type array.
?>
                <tr>
                    <td><?php echo $value[@name]; ?></td>                           
<?php
                    if (count((array)($value->price)) > 1) {                        // If item has multiple prices...   
?>
                        <td>
<?php
                            foreach((array)($value->price) as $m) {                 // then cycle through each price...
                                echo "<pre>"."$".$m."</pre>";                       // and print the prices in one table cell.
                            }
?>
                        </td>
<?php                           
                        }                           
                        else {                                                      // else means only 1 price exists, list it
?>
                            <td><?php echo "$".$value->price; ?></td>               
<?php
                        }                                                           // next line lists a checkbox for each item
?>                                                                                  
                    <td><input type="checkbox" name="<?php echo $selfs."-".$self."-". $type[$x]."-".$index; ?>"</td>    
                </tr>           
<?php       } # END if ((string) ($value->type) == (string)($type[$x])) 
        }#END foreach($tree as $value)
?>
            </table><br/>
<?php   
    }#END for($x=0; $x < count($type); $x++)
?>  
            <input type="submit" name="submit" value = "Continue to Selection" />
            </form>
            </div><!-- end food-menu -->
            
            
                