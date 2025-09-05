<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure;

use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Entity\Cart;
use Throwable;

class CartManager extends ConnectorFacade
{
    public $logger;

    public function __construct($host, $port, $password)
    {
        parent::__construct($host, $port, $password, 1);
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function saveCart(Cart $cart): bool
    {
        try {
            $this->connector->set(session_id(), $cart);
            return true;

        } catch (Throwable $e) {
            $this->logger->error('Error: '.$e->getMessage());
            return false;
        }
    }

    public function getCart(): ?Cart
    {
        try {
            $cart = $this->connector->get(session_id());
        } catch (Throwable $e) {
            $this->logger->error('Error: '.$e->getMessage());
        }

        if ($cart instanceof Cart) {
            return $cart;
        }

        return null;
    }
}
