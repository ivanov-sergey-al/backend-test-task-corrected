<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Raketa\BackendTestTask\Model\Product;
use Doctrine\DBAL\ArrayParameterType;

class ProductRepository
{
    public function getByUuid(string $uuid): ?Product
    {
        $row = $this->connection->createQueryBuilder()->select('*')->from('products')->where('is_active = 1 AND uuid = :uuid')
            ->setParameter('uuid', $uuid)->fetchAssociative();

        if (empty($row)) {
            return null;
        }

        return $this->make($row);
    }
    public function getByUuids(array $uuids): array
    {
        $products = $db->createQueryBuilder()->select('*')->from('products')->where('is_active = 1 AND uuid IN (:uuid)')
            ->setParameter('uuid', $uuids, ArrayParameterType::STRING)->fetchAllAssociative();

        return array_map(fn (array $row): Product => $this->make($row), $products);
    }

    public function getByCategory(string $category): array
    {
        $products = $this->connection->createQueryBuilder()->select('*')->from('products')->where('is_active = 1 AND category = :category')
            ->setParameter('category', $category)->fetchAllAssociative();

        return array_map(fn (array $row): Product => $this->make($row), $products);
    }

    public function make(array $row): Product
    {
        return new Product(
            $row['id'],
            $row['uuid'],
            $row['is_active'],
            $row['category'],
            $row['name'],
            $row['description'],
            $row['thumbnail'],
            $row['price'],
        );
    }
}
