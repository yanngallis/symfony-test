<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BuyController extends AbstractController
{
    private $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/buy/{id}", name="buy_product")
     */
    public function index($id)
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');
            $product = $this->productRepository->find($id);

            Stripe::setApiKey($this->getParameter('stripe_private_key'));
            $domain = 'https://127.0.0.1:8000';

            // Create a product in Stripe
            $stripeProduct = Product::create(['name' => $product->getTitle()]);

            // Create a price in Stripe
            $stripePrice = Price::create([
                'unit_amount' => $product->getPrice() * 100,
                'currency' => 'eur',
                'product' => $stripeProduct->id,
            ]);

            // Create a session in Stripe
            $stripeSession = Session::create([
                'line_items' => [[
                    'price' => $stripePrice->id,
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $domain . '/?payment=success',
                'cancel_url' => $domain . '/?payment=cancel'
            ]);

            return $this->redirect($stripeSession->url);
        } catch (ApiErrorException $e)
        {
            $response = new Response();
            $response->setStatusCode(500);
            return $response;
        }
    }
}
