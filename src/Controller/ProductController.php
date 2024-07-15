<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product', methods: ['GET'])]
    public function getProducts(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $products = $productRepository->findAll();
        $jsonContent = $serializer->serialize($products, 'json');

        return new JsonResponse($jsonContent, 200, [], true);
    }

    #[Route('/products', name: 'create_product', methods: ['POST'])]
    public function createProduct(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }

        $product = new Product();
        $product->setName($data['name'] ?? 'default name');
        $product->setPrice($data['price'] ?? 0);

        $em->persist($product);
        $em->flush();

        $jsonContent = $serializer->serialize($product, 'json');

        return new JsonResponse($jsonContent, 201, [], true);
    }

    #[Route('/products', name: 'get_all_products', methods: ['GET'])]
    public function getAllProducts(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $products = $productRepository->findAll();
        $jsonContent = $serializer->serialize($products, 'json');

        return new JsonResponse($jsonContent, 200, [], true);
    }
}
