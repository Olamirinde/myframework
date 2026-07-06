<?php

namespace App\Models;

use App\Database\Model;

class Product extends Model
{
    protected static $table = 'products';

    public static function categories($productId)
    {
        $stmt = static::db()->prepare(
            "SELECT categories.* 
             FROM categories
             JOIN product_categories ON categories.id = product_categories.category_id
             WHERE product_categories.product_id = ?"
        );
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public static function syncCategories($productId, $categoryIds)
    {
        $stmt = static::db()->prepare(
            "DELETE FROM product_categories WHERE product_id = ?"
        );
        $stmt->execute([$productId]);

        foreach ($categoryIds as $categoryId) {
            $stmt = static::db()->prepare(
                "INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)"
            );
            $stmt->execute([$productId, $categoryId]);
        }
    }
}
