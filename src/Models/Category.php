<?php  namespace Models; 
class Category extends Model{

    protected $table='categories';


    public function products($categoryId)
    {
        $sql = sprintf('SELECT * FROM products WHERE category_id=%d', (int) $categoryId);

        $stmt = $this->pdo->query($sql);

        if (!$stmt) return false;

        return $stmt->fetchAll();
    }

}