<?php
require_once '../../config/databases/Connection.php';
class Product 
{
    private $id;
	private $name;
	private $reference;
	private $price;
	private $weight;
	private $id_category;
	private $stock;
	private $creation_date;

    
    function __construct($field, $value) {
        if ($field != null) {
            if (is_array($field)) {
                foreach ($field as $Variable => $Value) $this->$Variable = $Value;
            } else {
                $query="select id, name, reference, price, weight, id_category, stock, creation_date from Product where $field ='$value'";
                $result = Connection::executeQuery($query, null);
                if(is_array($result)){
                    if (count($result)>0){
                        foreach ($result[0] as $Variable => $Value) $this->$Variable = $Value;
                    }
                }
            }
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }


    public function getName() {
        return trim($this->name);
    }

    public function setName($name) {
        $this->name = trim($name);
    }


    public function getReference() {
        return trim($this->reference);
    }

    public function setReference($reference) {
        $this->reference = trim($reference);
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getWeight() {
        return $this->weight;
    }

    public function setWeight($weight) {
        $this->weight = $weight;
    }

    public function getIdCategory() {
        return $this->id_category;
    }

    public function getCategory() {
        return new Category('id', $this->id_category);
    }

    public function setIdCategory($id_category) {
        $this->id_category = $id_category;
    }

    public function getStock() {
        return $this->stock;
    }

    public function setStock($stock) {
        $this->stock = $stock;
    }

    public function getCreationDate() {
        return $this->creation_date;
    }

    public function setCreationDate($creation_date) {
        $this->creation_date = $creation_date;
    }

    public function insert() {
        $name = $this->getName();
        $reference= $this->getReference();
        $price = $this->getPrice();
        $weight= $this->getWeight();
        $id_category = $this->getIdCategory();
        $stock = $this->getStock();
        $query_sql = "INSERT INTO product (name ,reference ,price, weight ,id_category ,stock) values ('$name', '$reference', $price, $weight, $id_category, $stock);";
        $response = Connection::executeQuery($query_sql,null);
        $data = (object) array("save" => $response );
        return $data;
    }


    public function delete()
    {
        $id_producto = $this->getId();
        $query="DELETE FROM product WHERE id = '$id_producto' ;";
        $response = Connection::executeQuery($query,null);
        $data = (object) array("delete" => $response, "query" => $query);
        return $data;
    }

    public function update()
    {
        $id = $this->getId();
        $name = $this->getName();
        $reference= $this->getReference();
        $price = $this->getPrice();
        $weight= $this->getWeight();
        $id_category = $this->getIdCategory();
        $stock = $this->getStock();
        $query = "UPDATE product set name = '$name', reference = '$reference', price = '$price', weight = '$weight', id_category = '$id_category', stock = '$stock' where id = '$id' ;";
        $response = Connection::executeQuery($query,null);
        $data = (object) array("update" => $response);
        return $data;
    }

    public function validateReference($id = null, $reference = null )
    {
        $exist = false;
        $filter = "reference = '" . $reference ."'";
        $data = Product::getListInObjects($filter);
        error_log(print_r($data,true));
        error_log($reference .'--'. $id);
        if (count($data) > 0) {
            if($data[0]->getId() == $id){
                $exist = false;
            }else{
                
                $exist = true;
            }
        }
        return $exist;
    }


    public function sellProduct($id, $amount)
    {
        $product = Product::getListInObjects(" P.id = '$id'");
        if ( count($product) > 0) {
            $old_stock = $product[0]->getStock();
            if ($old_stock >= $amount) {
                $new_stock = $old_stock - $amount;

                $query_update  = "UPDATE product SET stock = $new_stock WHERE id = '$id'";
                Connection:: executeQuery($query_update, null);
                $insert_sale = "INSERT INTO sale (id_product, amount) VALUES ($id, $amount);";
                Connection:: executeQuery($insert_sale, null);
                return 'Venta exitosa';
            } else {
                return 'No se cuenta con suficiente stock';
            }            
        } else {
            return 'No fue posible encontrar el producto';
        }
    }


    public static function getList($filter){
        if ($filter!=null) $filter=" where $filter";
        $query="SELECT P.id, P.name, P.reference, P.price, P.weight, P.id_category, P.stock, P.creation_date 
        FROM product AS P
        INNER JOIN category AS C ON C.id = P.id_category $filter order by P.id";
        // echo $query;
        return Connection::executeQuery($query,null);
    }

    public static function getListInObjects($filter) {
        $data = Product::getList($filter);
        $products= Array();
        if(is_array($data)){
            for ($i = 0; $i < count($data); $i++) {
                $products[$i] = new Product($data[$i], null);
            }
        }
        return $products;
    }

    public function validateData()
    {
        $id = $this->getId();
        $response = null;
        if (empty($this->getname())) {
            return "El nombre del producto no puede ser vacio";
        }

        if (empty($this->getReference())) {
            return "La referencia no puede ser vacia";
        }elseif($this->validateReference($id, $this->getReference())) {
            
            return "La referencia ya existe en el sistema";
        }

        if (empty($this->getPrice()) || !is_numeric($this->getPrice())) {
            return "El precio no puede ser vacio y debe ser numero";
        }

        if (empty($this->getWeight())) {
            return "El peso no puede ser vacio";
        }

        if (empty($this->getIdCategory())) {
            return "La categoria no puede ser vacia";
        }

        if (empty($this->getStock())  || $this->getStock() <= 0) {
            return "el stock no puede ser vacio";
        }

    }
}
