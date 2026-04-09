<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProductService
{
    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getProducts(): array
    {
        $response = $this->client->request(
            'GET',
            'https://fakestoreapi.com/products'
        );

        $products = $response->toArray();

        return array_map(function ($product) {
            return [
                'id' => $product['id'],
                'title' => $product['title'],
                'price' => $product['price'],
                'image' => $product['image'],
                'category' => $product['category']
            ];
        }, $products);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getProductById(int $productId): array
    {
        $response = $this->client->request(
            'GET',
            'https://fakestoreapi.com/products/'.$productId
        );

        $product = $response->toArray();

        return [
            'id' => $product['id'],
            'title' => $product['title'],
            'price' => $product['price'],
            'image' => $product['image'],
            'category' => $product['category']
        ];
    }
}