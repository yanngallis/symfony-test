<?php
namespace App\Managers;

use App\Entity\Product;
use App\Services\StripeServices;

class ProductManager{
    /**
     * @var StripeServices
     */
    protected $stripeServices;

    public function __construct(StripeServices $stripeServices)
    {
        $this->stripeServices = $stripeServices;
    }

    /**
     * 
     */
    public function buy(Product $product, $url_response){
        $same_products = array();
        $stripe_products = $this->stripeServices->getAllProduct();
        foreach($stripe_products as $stripe_product){
            if($stripe_product->name==$product->getTitle()){
                array_push($same_products, $stripe_product);
            }
        }

        $product_stripe = null;
        if(count($same_products)==0){
            $product_stripe = $this->stripeServices->createProduct($product);
        }else{
            $product_stripe = $same_products[count($same_products)-1];
        }
        
        $this->stripeServices->setResponseUrl($url_response);
        $this->stripeServices->setCheckoutSession($product_stripe,$product);
        
        $checkout_url = $this->stripeServices->getCheckoutUrl();
        return $checkout_url;
    }
}