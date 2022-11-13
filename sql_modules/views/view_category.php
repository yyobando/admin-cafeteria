<?php
require_once '../models/Category.php';
$requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);

if ($requestMethod == 'GET') {
    $filter = null;
    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
        $filter = " P.id = $id";
    }

    $categorys = Cotegory::getListInObjects($filter);
    
    if (count($categorys) > 0) {
        $array_response = array ();
        foreach ($categorys as $key => $item) {
           $c = $categorys[$key];
           array_push($array_response, 
                array(
                    "id" => $c->getId(),
                    "name" => $c->getName(),  
                )
            ); 
        }

        return Rest::response(200, HttpStatus::OK, $array_response); 
    } else {
        return Rest::response(200, HttpStatus::OK, array ('reason' => 'No se encontraron datos')); 
    }
}