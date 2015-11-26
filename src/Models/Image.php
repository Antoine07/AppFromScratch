<?php namespace Models;
class Image extends Model
{

    protected $table = 'images';

    public function productImage($product_id)
    {

        $sql = sprintf('SELECT * FROM %s WHERE product_id=%d', $this->table, (int) $product_id);

        $stmt = $this->pdo->query($sql);

        if (!$stmt) return false;

        return $stmt->fetch();

    }

}