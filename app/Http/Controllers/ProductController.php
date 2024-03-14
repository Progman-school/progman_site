<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Services\ProductService;

class ProductController extends MainController
{
    /**
     * @throws \Exception
     */
    public function getProductsByName(string $name): string
    {
        return ApiHelper::createFrontAnswer(
            ProductService::getProductBy('name', $name)
        );
    }

}
