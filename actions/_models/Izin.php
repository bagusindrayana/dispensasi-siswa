<?php
require_once __DIR__ . "/../../config/database.php";
class Izin {
    private $tableName = "izin";
    private $conn;
    public $html_pagination;
    public function __construct() {
        $this->conn = $GLOBALS['conn'];
    }


    function paginationAndSearch($row_per_page,$search_keyword,$customQuery = null) {
        //where like with join table pengguna
        $sql = $customQuery ?? "SELECT $this->tableName.*,siswa.nama_lengkap as nama_siswa,guru.nama_lengkap as nama_guru FROM $this->tableName 
        INNER JOIN pengguna as siswa ON siswa.id = izin.siswa_id 
        INNER JOIN pengguna as guru ON guru.id = izin.guru_id 
        WHERE siswa.nama_lengkap LIKE :keyword OR guru.nama_lengkap LIKE :keyword OR izin.tanggal LIKE :keyword OR izin.keterangan LIKE :keyword
        ORDER BY izin.id DESC ";
        $this->html_pagination = '';
        $page = 1;
        $start=0;
        if(!empty($_POST["page"])) {
            $page = $_POST["page"];
            $start=($page-1) * $row_per_page;
        }
        $limit=" limit " . $start . "," . $row_per_page;
        $pagination_statement = $this->conn->prepare($sql);
        $pagination_statement->bindValue(':keyword', '%' . $search_keyword . '%', PDO::PARAM_STR);
        $pagination_statement->execute();
    
        $row_count = $pagination_statement->rowCount();
        if(!empty($row_count)){
            $this->html_pagination .= "<div style='text-align:center;margin:20px 0px;'>";
            $page_count=ceil($row_count/$row_per_page);
            if($page_count>1) {
                for($i=1;$i<=$page_count;$i++){
                    if($i==$page){
                        $this->html_pagination .= '<input type="submit" name="page" value="' . $i . '" class="btn-page current" />';
                    } else {
                        $this->html_pagination .= '<input type="submit" name="page" value="' . $i . '" class="btn-page" />';
                    }
                }
            }
            $this->html_pagination .= "</div>";
        }
    
        $query = $sql.$limit;
        $pdo_statement = $this->conn->prepare($query);
        $pdo_statement->bindValue(':keyword', '%' . $search_keyword . '%', PDO::PARAM_STR);
        $pdo_statement->execute();
        $result = $pdo_statement->fetchAll();
        return $result;
    }

    //raw query
    public function rawQuery($sql) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    //get all
    public function getAll() {
        $sql = "SELECT * FROM $this->tableName";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    //find by id
    public function findById($id) {
        $sql = "SELECT * FROM $this->tableName WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //insert
    public function create($data) {
        $keys = array_keys($data);
        $values = array_values($data);
        $sql = "INSERT INTO $this->tableName (".implode(', ', $keys).") VALUES (:".implode(', :', $keys).")";
        $stmt = $this->conn->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(':'.$key, $value);
        }
        $stmt->execute();
        return $stmt;
    }

    //update
    public function update($id,$data) {
        $keys = array_keys($data);
        $values = array_values($data);
        $sql = "UPDATE $this->tableName SET ".implode(', ', array_map(function($key) { return $key." = :".$key; }, $keys))." WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(':'.$key, $value);
        }
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt;
    }

    //delete
    public function delete($id) {
        $sql = "DELETE FROM $this->tableName WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt;
    }

}