<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Entity\Cart;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\Model\Product;

readonly class CartView
{
    public function __construct(
        private ProductRepository $productRepository, 
        private CustomerView $customerView,
        private CartItemProductView $productView,
    ) {
    }

    public function toArray(Cart $cart): array
    {
        $productIds = array_map(fn (CartItem $cartItem) => $cartItem->getProductUuid(), $cart->getItems());
        $products = $this->productRepository->getByUuids($productIds);

        $total = 0;
        $items = [];
        foreach ($cart->getItems() as $item) {
            $product = array_filter($products, static fn (Product $product) => $product->getUuid() == $item->getProductUuid());
            $product = array_shift($product);

            if (!$product) {
                continue;
            }

            $currentTotal = $item->getPrice() * $item->getQuantity();
            $total += $currentTotal;

            $items[] = [
                'uuid' => $item->getUuid(),
                'price' => $item->getPrice(),
                'total' => $currentTotal,
                'quantity' => $item->getQuantity(),
                'product' => $this->productView->toArray($product),
            ];
        }

        return [
            'uuid' => $cart->getUuid(),
            'customer' => $this->customerView->toArray($cart->getCustomer()),
            'payment_method' => $cart->getPaymentMethod(),
            'total' => $total,
            'items' => $items,
        ];
    }
}


