<?php
require_once '../models/Product.php';
$requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);

if ($requestMethod === 'POST') {
    $body = file_get_contents('php://input');
    $params = json_decode($body);
    $action  = $params->action;
    if ($action == 'SAVE') {
        $product = new Product (null, null);
        $product->setName($params->name);
        $product->setReference($params->reference);
        $product->setPrice($params->price);
        $product->setStock($params->stock);
        $product->setIdCategory($params->id_category);
        $product->setWeight($params->weight);

        $error = $product->validateData();
        if (empty($error)) {
            $save_product = $product->insert();
            if ($save_product->save) {
                return Rest::response(200, HttpStatus::OK, array ('message' => 'Datos almazenados correctamente')); 
            } else {
                return Rest::response(400, HttpStatus::DEFAULT_ERROR_MESSAGE, array ('reason' => 'ha ocurrido un error al almacenar los datos'));
            }
        }else {
           return Rest::response(400, HttpStatus::RESOURCE_NOT_EXIST, array ('reason' => $error));  
        }

    } elseif( $action == 'SELL') {
       $sell = Product::sellProduct($params->id, $params->amount);
       return Rest::response(200, HttpStatus::OK, array ('reason' => $sell)); 
    }


}elseif ($requestMethod == 'GET') {
    $filter = null;
    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
        $filter = " P.id = $id";
    }

    $products = Product::getListInObjects($filter);
    
    if (count($products) > 0) {
        $array_response = array ();
        foreach ($products as $key => $item) {
           $p = $products[$key];
           array_push($array_response, 
                array(
                    "id" => $p->getId(),
                    "name" => $p->getName(),
                    "reference" => $p->getReference(),
                    "price" => $p->getPrice(),
                    "stock" => $p->getStock(),
                    "weight" => $p->getWeight(),
                    "category" => $p->getIdCategory(),
                    "creation_date" => $p->getCreationDate()  
                )
            ); 
        }

        return Rest::response(200, HttpStatus::OK, $array_response); 
    } else {
        return Rest::response(200, HttpStatus::OK, array ('reason' => 'No se encontraron datos')); 
    }
   
}elseif ($requestMethod == 'PUT') {
    $body = file_get_contents('php://input');
    $params = json_decode($body);
    if (empty($params->id)) {
        return Rest::response(400, HttpStatus::RESOURCE_NOT_EXIST, array ('reason' => 'en id no puede ser vacio'));
    }


    $product = new Product('id', $params->id);
    $product->setName($params->name);
    $product->setReference($params->reference);
    $product->setPrice($params->price);
    $product->setStock($params->stock);
    $product->setIdCategory($params->id_category);
    $product->setWeight($params->weight);

    $error = $product->validateData();

    if (empty($error)) {
        $updateProduct = $product->update();
        if ($updateProduct->update) {
            return Rest::response(200, HttpStatus::OK, array ('message' => 'Datos editados correctamente'));
        } else {
            return Rest::response(400, HttpStatus::DEFAULT_ERROR_MESSAGE, array ('reason' => 'ha ocurrido un error al editar los datos'));
        }
       
    }else {
        return Rest::response(400, HttpStatus::RESOURCE_NOT_EXIST, array ('reason' => $error)); 
    }   

    

}elseif ($requestMethod == 'DELETE') {
    $product = new Product ('id', $_GET['id']);
    $result = $product->delete();
    if ($result->delete) {
        return Rest::response(200, HttpStatus::OK, array ('message' => 'Datos eliminados correctamente'));
     }else {
        return Rest::response(400, HttpStatus::DEFAULT_ERROR_MESSAGE, array ('reason' => 'ha ocurrido un error al eliminar los datos los datos'));
    }
}else {
    return Rest::response(400, HttpStatus::DEFAULT_ERROR_MESSAGE, array ('reason' => 'request no disponible'));
}
