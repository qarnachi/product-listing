<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProductControllerTest extends WebTestCase
{
    public function testGetProducts(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/products');

        self::assertResponseIsSuccessful(); // 200 OK

        $data = json_decode($client->getResponse()->getContent(), true);

        self::assertIsArray($data);
        self::assertNotEmpty($data);
        self::assertArrayHasKey('id', $data[0]);
    }

    public function testGetProductDetail(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/products/1');

        self::assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);

        self::assertIsArray($data);
        self::assertArrayHasKey('id', $data);
        self::assertSame(1, $data['id']);
    }
}
