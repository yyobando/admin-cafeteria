<?php 
require_once '../../config/databases/Connection.php';
class Cotegory 
{
    private $id;
    private $name;
    private $description;
    
    function __construct($field, $value) {
        if ($field != null) {
            if (is_array($field)) {
                foreach ($field as $Variable => $Value) $this->$Variable=$Value;
            } else {
                $query="select id, name, description from category where $field ='$value'";
                $result = Connection::executeQuery($query, null);
                if(is_array($result)){
                    if (count($result)>0){
                        foreach ($result[0] as $Variable => $Value) $this->$Variable=$Value;
                    }
                }
            }
        }
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
        return $this;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;

        return $this;
    }

    public function getDescription(){
        return $this->description;
    }

    public function setDescription($description){
        $this->description = $description;
        return $this;
    }

    public static function getList($filter){
        if ($filter!=null) $filter = " where $filter";
        $query = "select id, name, description from category $filter;";
        return Connection::executeQuery($query,null);
    }

    public static function getListInObjects($filter){
        $data = Cotegory::getList($filter);
        $category = Array();
        if(is_array($data)){
            for ($i = 0; $i < count($data); $i++){
                $category[$i] = new Cotegory($data[$i], null);
            }
        }
        return $category;
    }

}

