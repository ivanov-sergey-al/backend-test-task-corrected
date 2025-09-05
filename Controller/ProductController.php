<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\View\ProductsView;
use Raketa\BackendTestTask\Model\Product;

readonly class ProductController extends BaseController
{
    public function __construct(private ProductsView $productsVew) 
    {
        parent::__construct();
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);
        $products = $this->productRepository->getByCategory($rawRequest['category']);

        return $this->successResponse($this->productsVew->toArray($products));
    }
}