<?php

namespace App\Services;

use App\Entity\Product;
use App\SingletonClass\StripeClientSingleton;
use PhpParser\Node\Stmt\TryCatch;
use \Stripe\Checkout\Session as Checkout_Session;

class StripeServices{
    private $public_key;
    private $private_key;

    protected $response_url;
    /**
     * Checkout_Session
     */
    protected $checkout_session;
    protected $stripe_client;

    public function __construct()
    {
        $this->public_key = $_ENV["STRIPE_PUBLIC_KEY"];
        $this->private_key = $_ENV["STRIPE_PRIVATE_KEY"];

    }

    public function getAllProduct(){
        $StripeClientSingleton = StripeClientSingleton::getInstance();

        $stripe_client = $StripeClientSingleton->getStripeClient();
        return $stripe_client->products->all();
    }

    public function createProduct(Product $product){
        $StripeClientSingleton = StripeClientSingleton::getInstance();

        $stripe_client = $StripeClientSingleton->getStripeClient();

        $stripe_product = $stripe_client->products->create(
            [
                'name' => $product->getTitle(),
                'default_price_data' => [
                    'unit_amount' => $product->getPrice(),
                    'currency' => 'eur'
                ],
                'expand' => ['default_price'],
            ]
        );

        return $stripe_product;
    }

    /**
     * 
     */
    public function getPrice(\Stripe\Product $product_stripe,Product $product){
        $StripeClientSingleton = StripeClientSingleton::getInstance();

        $stripe_client = $StripeClientSingleton->getStripeClient();
        
        try {
            $price = $stripe_client->prices->retrieve($product_stripe->default_price);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            $price = null;
        }
        
        if($price==null){
            $price = $stripe_client->prices->create(
                [
                    'product' => $product_stripe->id,
                    'unit_amount' => $product->getPrice(),
                    'currency' => 'euro'
                ]
            );
        }

        return $price;
    }

    public function setCheckoutSession(\Stripe\Product $product_stripe, Product $product){
        \Stripe\Stripe::setApiKey($this->private_key);

        $price = $this->getPrice($product_stripe,$product);
        
        try {
            $this->checkout_session = \Stripe\Checkout\Session::create([
                'line_items' => [[
                    # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
                    'price' => $price->id,
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $this->getSuccessUrl(),
                'cancel_url' => $this->getCancelUrl()
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            var_dump($e->getError());
            $this->checkout_session = null;
        }
    }

    public function setResponseUrl($url){
        $this->response_url= $url;
    }

    public function getSuccessUrl(){
        return $this->response_url.'?payment=success';
    }

    public function getCancelUrl(){
        return $this->response_url . '?payment=cancel';
    }

    public function getCheckoutSession(){
        return $this->checkout_session;
    }

    public function getCheckoutUrl()
    {
        if($this->checkout_session!==null){
            return $this->checkout_session->url;
        }else{
            return null;
        }
        
    }
}