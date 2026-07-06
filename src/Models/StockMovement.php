<?php

namespace App\Models;

use App\Database\Model;

class StockMovement extends Model
{
    protected static $table = 'stock_movements';

    public static function getByProduct($productId)
    {
        $stmt = static::db()->prepare(
            "SELECT * FROM stock_movements 
             WHERE product_id = ? 
             ORDER BY created_at DESC"
        );
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }
}
