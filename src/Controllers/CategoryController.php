<?php

namespace App\Controllers;

use App\Http\Response;
use App\Models\Category;
use App\Exceptions\HttpException;

class CategoryController
{
    public function index($request)
    {
        $categories = Category::all();
        return new Response(200, $categories);
    }

    public function show($request, $id)
    {
        if (!is_numeric($id)) {
            throw HttpException::validation(['id' => 'ID must be numeric']);
        }

        $category = Category::find($id);

        if (!$category) {
            throw HttpException::notFound('Category not found');
        }

        return new Response(200, $category);
    }

    public function store($request)
    {
        $body = $request->body;

        if (empty($body['name'])) {
            throw HttpException::validation(['name' => 'Name is required']);
        }

        $id = Category::create([
            'name'        => $body['name'],
            'description' => $body['description'] ?? null,
            'status_id'   => 1,
        ]);

        return new Response(201, ['message' => 'Category created', 'id' => $id]);
    }

    public function update($request, $id)
    {
        if (!is_numeric($id)) {
            throw HttpException::validation(['id' => 'ID must be numeric']);
        }

        $category = Category::find($id);

        if (!$category) {
            throw HttpException::notFound('Category not found');
        }

        $body = $request->body;

        if (empty($body['name'])) {
            throw HttpException::validation(['name' => 'Name is required']);
        }

        Category::update($id, [
            'name'        => $body['name'],
            'description' => $body['description'] ?? $category['description'],
            'status_id'   => $body['status_id']   ?? $category['status_id'],
        ]);

        return new Response(200, ['message' => 'Category updated']);
    }
}