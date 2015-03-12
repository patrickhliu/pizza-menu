<?php
/*  CART.PHP
 *  This file displays the shopping cart.  
 *  Customers can change quantities, empty cart, delete an item or checkout the order.
 */

// The cart contents will be stored in a $_SESSION array to let cart contents persist across pages
// This if statement instantiates  the $_SESSION array if it hasn't been set
    require_once(ROOT.'/lib/models.php');
    
    if(!isset($_SESSION['cart'])){                                                                                          
        $_SESSION['cart'] = array();
    }   

//  This next if loop is a bit long , it copies all items in $_POST to $_SESSION['cart']    
    if(isset($_POST['addtocart'])) {                                                        // If customer has added item to cart...
        $trash_submit = array_pop($_POST);                                                  // remove the last element in $_POST, it's always the submit variable
        unset($trash_submit);
        
        foreach($_POST as $key=>$value) {                                                   // Iterate through each POST element
            $foods = split("-", $key);                                                      // Create $foods array to store the key of each POST (A-B-C-D description)
            $obj = getIndivFood($dom, $foods[0], $foods[1], $foods[3]);                     // Use the $foods array elements to find specific item in DOM
                                    
            if(strpos((string)$value, "$") === false) {                                     // Determine if multiple prices exist by looking for "$"                    
                $each_item = array();                                                       // Store item name/price in the $each_item[] array
                $each_item[] = ucwords($obj->type)." ".ucwords($foods[1]).": ".(string)$obj[@name]." ($value)";         //simpleXMLElement needs to be cast object->string
                $each_item[] = (string)str_replace("($value)", "", $obj->price->$value);    //Turn "5.50 (small)" to "5.50"
                $each_item[] = 1;                                                           //Default qty is 1
                
                $match=0;                                                                   // check if the post item already exists in $_SESSION['cart]
                foreach($_SESSION['cart'] as $i=>$v) {                                      // if yes, increment the quantity in $_SESSION by 1
                    if((string)$_SESSION['cart'][$i][0] == (string)$each_item[0]) {     
                        $_SESSION['cart'][$i][2]++;
                        $match=1;
                    }           
                }
                
                if($match == 0) {                                                           // if no, add the item to $_SESSION['cart]
                    $_SESSION['cart'][] = $each_item;
                }               
                                
                unset($each_item);
            }
            else {                                                                          // else means item is single price
                $each_item = array();                                                       // same process as the if branch above
                $each_item[] = ucwords($obj->type)." ".ucwords($foods[1]).": ".(string)$obj[@name];
                $each_item[] = (string)$obj->price;
                $each_item[] = 1;
                
                $match=0;                                                                   // check if the post item already exists in $_SESSION['cart]
                foreach($_SESSION['cart'] as $i=>$v) {                                      // if yes, increment the quantity in $_SESSION by 1
                    if((string)$_SESSION['cart'][$i][0] == (string)$each_item[0]) {     
                        $_SESSION['cart'][$i][2]++;
                        $match=1;
                    }           
                }
                
                if($match == 0) {                                                           // if no, add the item to $_SESSION['cart]
                    $_SESSION['cart'][] = $each_item;
                }               
                
                unset($each_item);
            }
        } #END foreach($_POST as $key=>$value)
        header('location: index.php?page=cart.php');    // When done iterating through POST, if a page refresh happens, then the item is
        exit;  
    }#END if(isset($_POST['addtocart']))                // added to cart multiple times.  To avoid this, redirect user to same cart.php page 
                                                        // Redirect will clear the $_POST array, but since we already saved info in $_POST
                                                        // to $_SESSION, it's fine to clear the $_POST array            

//  The next few sections cover the cases of when customer presses one of the cart buttons: 
//  Update Cart (quantity change), Empty Cart (clears cart), Delete (remove individual item), Checkout (check out order)

//  1: Change quantity or delete item
//  Either customer has pressed DELETE button to remove an item, or UPDATE CART to update item quantity.                
        for($y=0; $y < count($_SESSION['cart']); $y++) {                                    
            if(  isset(  $_POST['delete-item-'.$y]  ) && count($_SESSION['cart']) > 0 ) {       // this if is the logic for removing invidiual
                unset($_SESSION['cart'][$y]);                                                   // this was most difficult to code, hard to explain 
                $_SESSION['cart'] = array_values($_SESSION['cart']);                            // honestly I don't understand the logic 100%
                $y--;
                unset($_POST);                                                          
                header('location: index.php?page=cart.php');
                exit;
            }
            
            if(isset(  $_POST['qty-item-'.$y])) {                                               // this is if for quantity updates
                $_SESSION['cart'][$y][2] = $_POST['qty-item-'.$y];                              // $_SESSION cart array has a element for quantity, default is 1 
            }                                                                                   // Each time Update cart is pressed, update the qty $_SESSION values
        }                                                                                       // with the values in $_POST
        
        
//  2: Empty Cart
//  Customer will remove all items in cart
        if(  isset(  $_POST['empty-cart']  )  ) {                                               // if empty cart is pressed, unset the $_SESSION variable 
            unset($_SESSION['cart']);
        }

//  3: Complete Order
//  This if branch checks that the order hasn't been check out, if not then display the shopping cart.
//  The else branch (at very bottom) covers the case when customer checks out and displays the Thank You message                
        if(!isset($_POST['complete-order'])) {                                                  //  if customer hasn't checked out
            if( isset($_SESSION['cart']) && count($_SESSION['cart']) > 0 ) {                    //  if $_SESSION cart is set, and isn't empty, display the cart.            
?>          
    <div class="food-menu"> 
        <h2>Your Cart</h2>                                                      
        <form action="index.php?page=cart.php" method="POST">
        <table border="1" id="cart-table">
            <tr>
                <th>Item</th><th>Price</th><th>Quantity</th><th>Delete</th>
            </tr>
<?php       
            $calc=0;                                                            //  $calc is the variable keeping track of total cost
            foreach($_SESSION['cart'] as $i=>$arr) {                            //  iterate through each item in cart, $i is index, $arr is element value
                                                                                //  $_SESSION['cart'] is a 2D array, so $arr will be an array where
                                                                                //  [0] is name, [1] is price, [2] is qty
?>                                                                      
            <tr>
                <td><?php echo $arr[0]; ?></td>                                 
                <td><?php echo "$".number_format(  ((float)$arr[1] * (float)$arr[2]), 2, '.', ','); ?></td>     
                <td>
                    <select name="qty-item-<?php echo $i; ?>">
                        <?php 
                            for($d=1; $d<11; $d++) {                            //  qty can be 1-10, this for loop will print out each select option from 1-10
                                 if(  $d == $_SESSION['cart'][$i][2] ) {        //  while checking if a particular # 1-10 needs to be selected as default
                                                                                //  a value needs to be set to default when customer has changed quantity
                                 ?>                                                     
                                    <option value=<?php echo $d; ?> selected><?php echo $d; ?></option>
                                 <?php
                                 }
                                 else {
                                 ?>
                                    <option value=<?php echo $d; ?>><?php echo $d; ?></option>
                                 <?php
                                 } 
                            } 
                            ?>
                    </select>
                </td>
                <td><input type="submit" name="delete-item-<?php echo $i; ?>" id="delete" value="Delete" /></td>    
            </tr>
<?php
            $calc +=(float) str_replace("$", "", $arr[1]) * $arr[2];            // $calc calculates total cost, $arr[1] * $arr[2] is price * quantity
            $calc = number_format($calc, 2, '.', ',');                      
        }           
?>  
        </table><br/>       
        <input type="hidden" name="total-cost" value="<?php echo $calc; ?>" />
        
<?php
        $update_cart = new Submit_Button("update-cart", "Update Cart");         // create html buttons, oop practice
        $update_cart->make_button();
        
        $empty_cart = new Submit_Button("empty-cart", "Empty Cart");
        $empty_cart->make_button();
        
        $complete_order = new Submit_Button("complete-order", "Complete Order (Click Once)");
        $complete_order->make_button();
?>
        </form>             
<?php       
    echo "<h2>Your Total: $".$calc."</h2>";
?>
    </div><!-- end food menu -->
<?php   
    }                                                                           # END if( isset($_SESSION['cart']) && count($_SESSION['cart']) > 0 ) 
    else {                                                                      # else means $_SESSION[cart] doesn't have more than 0 items, meaning cart is empty.     
?>
        <div class="food-menu">
<?php
        echo "<h1>Your cart is empty !</h1>";
?>
        </div>
<?php
    }
}                                                                               # END if(!isset($_POST['complete-order'])) 
else {                                                                          # else means user has pressed Complete Order.  Then we want to clear the cart, thank customer, display order total
    unset($_SESSION['cart']);
?>
    <div class="food-menu"> 
    <h2>Thank you for your Order!</h2>
    <h2>Total Charge: $<?php echo $_POST['total-cost']; ?></h2>
    </div>
<?php
}
?>
