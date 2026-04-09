<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Repository\CartItemRepository;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/cart')]
class CartController extends AbstractController
{
    #[Route('', name: 'api_cart_view', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function view(CartRepository $repo): JsonResponse
    {
        $cart = $repo->findOneBy(['user' => $this->getUser()]);

        if (!$cart) {
            return $this->json([
                'items' => [],
                'total' => 0,
                'discount' => 0,
                'total_after_discount' => 0
            ], 200);
        }
        $items = $cart->getCartItems();
        $total = array_sum(array_map(fn($i) => $i->getPrice() * $i->getQuantity(), $items->toArray()));
        $discount = $total > 100 ? 0.1 * $total : 0;

        return $this->json([
            'items' => $items,
            'total' => $total,
            'discount' => $discount,
            'total_after_discount' => $total - $discount,
        ], 200, [], ['groups' => ['cart:read']]);
    }

    #[Route('/add', name: 'api_cart_add', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function add(Request $request, EntityManagerInterface $em, CartRepository $cartRepo, CartItemRepository $cartItemRepo): JsonResponse
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        $cart = $cartRepo->findOneBy(['user' => $user]);
        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);
            $em->persist($cart);
        }

        $item = $cartItemRepo->findOneBy([
            'cart' => $cart,
            'productId' => $data['id']
        ]) ?? new CartItem();

        $item->setCart($cart);
        $item->setProductId($data['id']);
        $item->setTitle($data['title']);
        $item->setPrice($data['price']);
        $item->setImage($data['image']);
        $item->setQuantity($item->getQuantity() + 1);

        $em->persist($item);
        $em->flush();

        return $this->json(['message' => 'Produit ajouté']);
    }

    #[Route('/remove', name: 'api_cart_remove', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function remove(Request $request, EntityManagerInterface $em, CartRepository $cartRepo, CartItemRepository $cartItemRepo): JsonResponse
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        $cart = $cartRepo->findOneBy(['user' => $user]);
        if (!$cart) {
            return $this->json(['message' => 'Panier introuvable'], 404);
        }

        $item = $cartItemRepo->findOneBy([
            'cart' => $cart,
            'productId' => $data['id']
        ]);

        if ($item) {
            $em->remove($item);
            $em->flush();
        }

        return $this->json(['message' => 'Produit supprimé']);
    }

    #[Route('/update', name: 'api_cart_update', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function update(Request $request, EntityManagerInterface $em, CartRepository $cartRepo, CartItemRepository $cartItemRepo): JsonResponse
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        $productId = $data['id'] ?? null;
        $quantity = $data['quantity'] ?? null;

        $cart = $cartRepo->findOneBy(['user' => $user]);
        if (!$cart) {
            return $this->json(['message' => 'Panier introuvable'], 404);
        }

        if (!$productId || !$quantity || $quantity < 1) {
            return $this->json(['message' => 'Données invalides'], 400);
        }

        $cart = $cartRepo->findOneBy(['user' => $user]);
        if (!$cart) {
            return $this->json(['message' => 'Panier introuvable'], 404);
        }

        $item = $cartItemRepo->findOneBy([
            'cart' => $cart,
            'productId' => $productId
        ]);

        if (!$item) {
            return $this->json(['message' => 'Produit non trouvé dans le panier'], 404);
        }

        $item->setQuantity($quantity);
        $em->flush();

        return $this->json(['message' => 'Quantité mise à jour']);
    }
}
