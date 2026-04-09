<?php
namespace App\Controller;

use App\Service\ProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/products')]
class ProductController extends AbstractController
{
    public function __construct(private ProductService $productService)
    {
    }

    #[Route('', name: 'api_products', methods: ['GET'])]
    public function index(): JsonResponse
    {

        try {
            $products = $this->productService->getProducts();

            return $this->json($products);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de la récupération des produits'], 500);
        }
    }

    #[Route('/{id}', name: 'api_product_detail', methods: ['GET'])]
    public function detail(int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        return $this->json($product);
    }
}