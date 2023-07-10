<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductRequest;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = $this->getData();
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $products = $this->getData();

        $id = count($products) + 1;

        $data[$id] = [
            'id'          => $id,
            'description' => $request->description,
            'category'    => $request->category,
            'quantity'    => $request->quantity,
            'status'      => $request->status ?? true,
        ];
        $products = array_merge($products, $data);

        $this->saveFile($products);

        return response()->json(['message' => 'Registro agregado correctamente']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $products = $this->getData();
        $index    = ($id - 1) >= 1 ? $id - 1 : 0;

        if (!isset($products[$index]))
        {
            return response()->json(['message' => 'Producto Inexistente'], 404);
        }
        return response()->json($products[$index]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        $products = $this->getData();
        $index    = ($id - 1) >= 1 ? $id - 1 : 0;

        if (!isset($products[$index]))
        {
            return response()->json(['message' => 'Producto Inexistente'], 404);
        }

        $products[$index]->description = $request->description;
        $products[$index]->category    = $request->category;
        $products[$index]->quantity    = $request->quantity;
        $products[$index]->status      = $request->status ?? $products[$index]->status;

        $this->saveFile($products);

        return response()->json(['message' => 'Registro actualizado correctamente']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $products = $this->getData();
        $index    = ($id - 1) >= 1 ? $id - 1 : 0;

        if (!isset($products[$index]))
        {
            return response()->json(['message' => 'Producto Inexistente'], 404);
        }

        $products[$index]->status = false;
        $this->saveFile($products);
        return response()->json(['message' => 'El producto se a dado de baja']);

    }

    private function getData()
    {
        $data = [];
        if (file_exists(storage_path('app/database.txt')))
        {
            $dataText = file_get_contents(storage_path('app/database.txt'));
            $data     = json_decode($dataText);
        };
        return $data;
    }

    private function saveFile($products)
    {
        $dataJson = json_encode($products);
        $file     = storage_path('app/database.txt');
        file_put_contents($file, $dataJson);
    }
}
