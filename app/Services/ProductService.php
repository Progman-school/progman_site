<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public static function getProductsList(?bool $active = null): array
    {
        $conditions = [];
        if (!is_null($active)) {
            $conditions = ["is_active" => $active];
        }

        return Product::with(["couponType", "course"])
            ->where($conditions)
            ->get()
            ->keyBy('id')
            ->toArray();
    }

    public static function getProductBy(string $paramName, mixed $paramValue, bool $activeOnly = true): array
    {
        $conditions = [
            $paramName => $paramValue,
        ];

        if ($activeOnly) {
            $conditions["is_active"] = true;
        }

        $product = Product::with(["couponType", "course"])
            ->where($conditions)
            ->first();

        if (!$product) {
            throw new \Exception("The product is not found!");
        }

        return $product->toArray();
    }
}
