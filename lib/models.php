<?php   
/*
    models.php has the functions that will get/return data from the .xml file
*/
    $dom = simplexml_load_file(__DIR__ . "/menu.xml");
        
    function getTree($dom, $selfs, $self) {
        return $dom->xpath("/menu/$selfs/$self");       
    }
        
    function getFoodType($dom, $tree) {                         //  foreach loop fills the type[] array 
        $arr = array();
        
        foreach($tree as $index=>$data) {
            array_push($arr, $data->type);
        }
        
        $arr = array_values(array_unique($arr));                // array_values() is used to reset the index back to 0,1,2,3...
        return $arr;        
    }
    
    function getIndivFood($dom, $selfs, $self, $position) {
        return $dom->xpath("/menu/$selfs/$self")[$position];        
    }

?>