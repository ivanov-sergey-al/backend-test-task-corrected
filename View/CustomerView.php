<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Entity\Customer;
use Raketa\BackendTestTask\Repository\ProductRepository;

readonly class CustomerView
{
    public function toArray(Customer $customer): array
    {
        return [
            'id' => $customer->getId(),
            'name' => implode(' ', [$customer->getLastName(), $customer->getFirstName(), $customer->getMiddleName()]),
            'email' => $customer->getEmail(),
        ];
    }
}
