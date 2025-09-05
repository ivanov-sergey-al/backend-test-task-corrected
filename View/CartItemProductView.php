<?php

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Repository\Model\Product;
use Raketa\BackendTestTask\Repository\ProductRepository;

readonly class CartItemProductView
{
    public function __construct(private ProductRepository $productRepository) 
    {
    }

    public function toArray(Product $product): array
    {
        return [
            'id' => $product->getId(),
            'uuid' => $product->getUuid(),
            'name' => $product->getName(),
            'thumbnail' => $product->getThumbnail(),
            'price' => $product->getPrice(),
        ];
    }
}
