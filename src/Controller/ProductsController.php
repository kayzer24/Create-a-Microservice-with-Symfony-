<?php

namespace App\Controller;

use App\Cache\PromotionCache;
use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\Filter\PromotionFilterInterface;
use App\Repository\ProductRepository;
use App\Service\Serializer\DTOSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ProductsController extends AbstractController
{
    public function __construct(
        private ProductRepository      $productRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
    public function lowestPrice(
        Request                  $request,
        int                      $id,
        DTOSerializer            $serializer,
        PromotionFilterInterface $promotionsFilter,
        PromotionCache           $promotionCache
    ): Response
    {
        if ($request->headers->has('force_fail')) {
            return new JsonResponse(
                ['error' => 'Promotion Engine failure message'],
                $request->headers->get('force_fail')
            );
        }

        /** @var LowestPriceEnquiry $lowestPriceEnquiry */
        $lowestPriceEnquiry = $serializer->deserialize(
            $request->getContent(),
            LowestPriceEnquiry::class, 'json'
        );

        $product = $this->productRepository->find($id);

        $lowestPriceEnquiry->setProduct($product);

        $promotions = $promotionCache->findValidForProduct($product, $lowestPriceEnquiry->getRequestDate());

        $modifierEnquiry = $promotionsFilter->apply($lowestPriceEnquiry, ...$promotions);

        $responseContent = $serializer->serialize($modifierEnquiry, 'json');

        return new Response($responseContent, 200, ['Content-Type' => 'application/json']);
    }
}
