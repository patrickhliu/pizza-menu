<?php
/*  SELECTIONS.PHP
 *  This page is displayed after a customer has selected a food item.
 *  and shows any available options for that item. (eg: pizzas have multiple sizes and a price for each size)
 */

 //     Each element key in $_POST is a "descriptive string" of the food item selected by the customer.
 // The descriptive string looks like: $selfs-$self-type-number
 // eg: For a pizza, the string looks like:  pizzas-pizza-regular-4
 // pizzas-pizza (denotes where to look in the DOM for the item)
 // "regular" (denotes the type of pizza)
 // "4" (denotes numeric position of the pizza appears in the DOM. Due to 0-base arrays,  4 means the 5th pizza listed)
 // The "descriptive string" is always has 4 sub-strings seperated by hyphens (A-B-C-D)
 // This if statement verifies that a customer submission has occurred...   
    require_once(ROOT.'/lib/models.php');
    
    if (isset($_POST['submit']) && count($_POST) != 1 ) {
?>      <div class="food-menu">
        <form action="?page=cart.php" method="POST">
<?php
//  This foreach loop iterates through each POST element (where each element is a selected food item)
//  and breaks up the A-B-C-D descriptive string into a new array called $foods.
        foreach($_POST as $key=>$value) {       
            $foods = split("-", $key);          
            
// $_POST also contains an element representing the value the 'submit' variable, and it has length 1.
// That element needs to be ignored.
// This if statement verifies that $foods array has 4 elements, meaning it represents a food item and not the 'submit' variable. 
            if (count($foods) == 4) {            
                $foods[2] = str_replace("_", " ", $foods[2]);   //  POST automatically replaces all spaces in a string with underscores
                                                                //  Undo that because strings will be used for comparison, where spaces are expected            

//  This $val statement uses the info in $foods array to search the DOM for the specific item.
//  eg: suppose $foods = ["pizzas", "pizza" ,"regular", "5"]
//  $val will look for menu -> pizzas -> pizza; then go to the 6th item in that list (5 means 6 since arrays are 0-base)                    
                $val = getIndivFood($dom, $foods[0], $foods[1], $foods[3]);
                
//  So in DOM, pizza #5 has a element called type.  The $foods array also lists the type of the item selected by the customer.
//  The next if statement verifies that these two match.
//  It seeems unnecessary because the numeric position of a item is enough to find the exact item.
//  The rest of the if statement looks if the item has multiple prices/sizes.  
//  If yes, then get all prices and list them in a drop down menu.
//  If no, that means there's only one price, so list that in a read-only text box.
//  From looking at other websites (Domino's, Amazon), you can only select 1 size or color at a time, so I mimick that.
//  I'm guessing ordering multiple sizes/colors at the same time tends to be a worse UX experience.
                if(  (string)$val->type == (string)$foods[2]) {
?>
                    <div id="food-block">
                        <div class="food-name">
<?php                               
                        echo "<p>".ucwords($val->type)." ".ucwords($foods[1]).": ".$val[@name]."</p>";      // First list the food name

                            if(   count((array)$val->price) > 1) {                                          // if item has multiple prices....then make a drop down menu            
?>
                            
                                <label for:"size-select"><p>Select A Size:</p></label>
                                <div class='input-food-price'>               
                                    <select name="<?php echo implode("-", $foods); ?>" id="size-select">
                            
<?php                       
                            foreach( (array)$val->price as $m) {                                            // cycle through each price and list them in a drop down menu
                            // this next really long line, for the value, I'm setting it equal to the numerical index of the price (0, 1, 2, 3.....)
?>
                                <option value="<?php echo array_search($m, (array)$val->price); ?>"><?php echo "$".$m; ?></option>
<?php               
                            }                       
?>
                            </select>
                            </div>
                        </div>
<?php                       
                            }
                        else {                                                                              // else it's single price, list that out in a textbox
?>
                            <label for:"single-price"><p>Price:</p></label>
                            <div class='input-food-price'>
                                <input type="text" name="<?php echo implode("-", $foods); ?>" value="<?php echo "$".$val->price; ?>" id="single-price" readonly />
                            </div>
                            </div>
<?php   
                        }
?>
                    </div>
<?php                       
                    }       
            } # END if (count($foods) == 4) {           
        } # END foreach($_POST as $key=>$value) {
        
        $selections_button = new Submit_Button("addtocart", "Add to Cart");                                 // create html buttons, oop practice
        $selections_button->make_button();              
?>                  
                            </form>
                            </div> <!-- end food menu -->
<?php  }                                                                                                    #if (isset($_POST['submit']) && count($_POST) != 1 )    
    else {  // else means $_POST only has 1 element, the submit variable.  This means no food items where selected, so prompt the user to select something.
?>
    <div class="food-menu">
<?php
        echo "<h1>Whoops, 0 items were selected.</h1>";
        echo "<h1>Please go back and make a selection.</h1>";
    }
?>
    </div>