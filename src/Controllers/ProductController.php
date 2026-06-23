<?php

namespace App\Controllers;

use App\Http\Response;
use App\Models\Product;
use App\Models\Category;
use App\Exceptions\ThrowException;

class ProductController
{
    public function index($request)
    {
        return new Response(200, Product::all());
    }

    public function show($request, $id)
    {
        if (!is_numeric($id)) {
            throw ThrowException::validation(['id' => 'ID must be numeric']);
        }

        $product = Product::find($id);

        if (!$product) {
            throw ThrowException::notFound('Product not found');
        }

        return new Response(200, $product);
    }

    public function store($request)
    {
        $body = $request->body;

        if (empty($body['name'])) {
            throw ThrowException::validation(['name' => 'Name is required']);
        }

        if (!isset($body['price']) || !is_numeric($body['price'])) {
            throw ThrowException::validation(['price' => 'Valid price is required']);
        }

        $id = Product::create([
            'name'      => $body['name'],
            'price'     => $body['price'],
            'quantity'  => $body['quantity'] ?? 0,
            'status_id' => 1,
        ]);

        return new Response(201, ['message' => 'Product created', 'id' => $id]);
    }

    public function update($request, $id)
    {
        if (!is_numeric($id)) {
            throw ThrowException::validation(['id' => 'ID must be numeric']);
        }

        $product = Product::find($id);

        if (!$product) {
            throw ThrowException::notFound('Product not found');
        }

        $body = $request->body;

        if (empty($body['name'])) {
            throw ThrowException::validation(['name' => 'Name is required']);
        }

        Product::update($id, [
            'name'      => $body['name'],
            'price'     => $body['price']     ?? $product['price'],
            'status_id' => $body['status_id'] ?? $product['status_id'],
        ]);

        return new Response(200, ['message' => 'Product updated']);
    }

    public function getCategories($request, $id)
    {
        if (!is_numeric($id)) {
            throw ThrowException::validation(['id' => 'ID must be numeric']);
        }

        $product = Product::find($id);

        if (!$product) {
            throw ThrowException::notFound('Product not found');
        }

        $categories = Product::categories($id);

        return new Response(200, $categories);
    }

    public function syncCategories($request, $id)
    {
        if (!is_numeric($id)) {
            throw ThrowException::validation(['id' => 'ID must be numeric']);
        }

        $product = Product::find($id);

        if (!$product) {
            throw ThrowException::notFound('Product not found');
        }

        $categoryIds = $request->body['category_ids'] ?? [];

        if (empty($categoryIds) || !is_array($categoryIds)) {
            throw ThrowException::validation(['category_ids' => 'category_ids must be a non-empty array']);
        }

        // Confirm every category_id actually exists
        foreach ($categoryIds as $categoryId) {
            if (!Category::find($categoryId)) {
                throw ThrowException::validation(['category_ids' => "Category {$categoryId} does not exist"]);
            }
        }

        Product::syncCategories($id, $categoryIds);

        return new Response(200, ['message' => 'Categories updated']);
    }
}
