<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\Repository\CartManager;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\View\CartView;
use Ramsey\Uuid\Uuid;
use Raketa\BackendTestTask\Entity\Cart;
use Raketa\BackendTestTask\Entity\Customer;

readonly class CartController extends BaseController
{
    public function __construct(private CartView $cartView, private CartManager $cartManager) 
    {
        parent::__construct();
    }

    public function addCartItem(RequestInterface $request, ProductRepository $productRepository): ResponseInterface
    {
        $cart = $this->cartManager->getCart();
        if (!$cart) {
            return $this->errorResponse('Cart not found', 404);
        } 

        $rawRequest = json_decode($request->getBody()->getContents(), true);
        $product = $productRepository->getByUuid($rawRequest['productUuid']);
        if (!$product) {
            return $this->errorResponse('Product not found', 404);
        }

        $cart = $this->cartManager->getCart();
        $cart->addItem(new CartItem(
            Uuid::uuid4()->toString(),
            $product->getUuid(),
            $product->getPrice(),
            $rawRequest['quantity'],
        ));

        if (!$this->cartManager->saveCart($cart)) {
            return $this->errorResponse('Failed to save basket', 500);
        }

        return $this->successResponse($this->cartView->toArray($cart));
    }

    public function getCart(RequestInterface $request): ResponseInterface
    {
        $cart = $this->cartManager->getCart();
        if (!$cart) {
            return $this->errorResponse('Cart not found', 404);
        } 

        return $this->successResponse($this->cartView->toArray($cart));
    }
}