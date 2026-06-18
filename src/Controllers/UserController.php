<?php

namespace App\Controllers;

use App\Http\Response;
use App\Models\User;
use App\Exceptions\ThrowException;

class UserController
{
    public function index($request)
    {
        return new Response(200, User::all());
    }

    public function show($request, $id)
    {
        if (!is_numeric($id)) {
            throw ThrowException::validation(['id' => 'ID must be numeric']);
        }

        $user = User::find($id);

        if (!$user) {
            throw ThrowException::notFound();
        }

        return new Response(200, $user);
    }

    public function store($request)
    {
        $id = User::create([
            'name'  => $request->body['name']  ?? '',
            'email' => $request->body['email'] ?? '',
        ]);

        return new Response(201, ['message' => 'User created', 'id' => $id]);
    }

    public function update($request, $id)
    {
        if (!is_numeric($id)) {
            throw ThrowException::validation(['id' => 'ID must be numeric']);
        }

        $user = User::find($id);

        if (!$user) {
            throw ThrowException::notFound();
        }

        $affected = User::update($id, [
            'name'  => $request->body['name']  ?? $user['name'],
            'email' => $request->body['email'] ?? $user['email'],
        ]);

        return new Response(200, ['message' => 'User updated', 'affected' => $affected]);
    }

    public function delete($request, $id)
    {
        if (!is_numeric($id)) {
            throw ThrowException::validation(['id' => 'ID must be numeric']);
        }

        $user = User::find($id);

        if (!$user) {
            throw ThrowException::notFound();
        }

        User::delete($id);

        return new Response(200, ['message' => 'User deleted']);
    }
}