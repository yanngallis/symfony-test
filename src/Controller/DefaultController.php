<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\ProductRepository;
use \Stripe\Checkout\Session;
class DefaultController extends AbstractController
{
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
    /**
     * @Route("/", name="app_default")
     */
    public function index(ProductRepository $productRespository): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        // $stripe = new \Stripe\StripeClient(
        //     'sk_test_51KX3c7Cltqi2ui856ZLMGZVA4GYfreLadqdihnMa5vDzEVwVnJbVTztmRe48kY6F9jRXo7n6hjksod5E4RZnIxOb00tAu3CPF7'
        //   );
        // $productAll =  $stripe->products->all();
        // var_dump(count($productAll));
        $products = $productRespository->findAll();
        return $this->render('default/index.html.twig', [
            'products' => $products,
            'text' => "No purchase !",
            'css' => "green"
        ]);
    }

     /**
     * @Route("/first", name="app_first")
     */
    public function firstAction(ProductRepository $productRespository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $products = $productRespository->findAll();
        return $this->render('default/index.html.twig', [
            'products' => $products,
            'text' => "No purchase !",
            'css' => "green"
        ]);
    }

    /**
     * @Route("/succes", name="app_stripe_succes")
     */
    public function succesAction(ProductRepository $productRespository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $products = $productRespository->findAll();

        return $this->render('default/index.html.twig', [
            'products' => $products,
            'text' => "Your purchase is Valid !",
            'css' => "green"
        ]);
    }

    /**
     * @Route("/failed", name="app_stripe_failed")
     */
    public function failedAction(ProductRepository $productRespository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $products = $productRespository->findAll();

        return $this->render('default/index.html.twig', [
            'products' => $products,
            'text' => "Your purchase is canceled !",
            'css' => "red"
        ]);
    }
    /**
     * @Route("/stripe", name="app_stripe")
     */
    public function stripeAfterLoginAction(){
        $stripe = new \Stripe\StripeClient(
            'sk_test_51KX3c7Cltqi2ui856ZLMGZVA4GYfreLadqdihnMa5vDzEVwVnJbVTztmRe48kY6F9jRXo7n6hjksod5E4RZnIxOb00tAu3CPF7'
          );
        $productAll =  $stripe->prices->all(['limit' => 3]);
        
      

          $checkout_session = $stripe->checkout->sessions->create([
            'success_url' => $this->urlGenerator->generate('app_stripe_succes',array(), UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->urlGenerator->generate('app_stripe_failed',array(), UrlGeneratorInterface::ABSOLUTE_URL),
            'line_items' => [
              [
                'price' => $productAll->data[0]->id,
                'quantity' => 1,
              ],
            ],
            'mode' => 'payment',
          ]);

        
        // header("HTTP/1.1 303 See Other");
        // header("Location: " . $checkout_session->url);
        $this->redirect($checkout_session->url);
        return $this->redirect($checkout_session->url, 303);
    }

    /**
     * @Route("/buy/{id}", name="app_buy")
     */
    public function stripeBuyAction(ProductRepository $productRespository, $id){
        $stripe = new \Stripe\StripeClient(
            'sk_test_51KX3c7Cltqi2ui856ZLMGZVA4GYfreLadqdihnMa5vDzEVwVnJbVTztmRe48kY6F9jRXo7n6hjksod5E4RZnIxOb00tAu3CPF7'
          );
        //$productAll =  $stripe->prices->all(['limit' => 3]);
        $products = $productRespository->find($id);
      

          $checkout_session = $stripe->checkout->sessions->create([
            'success_url' => $this->urlGenerator->generate('app_stripe_succes',array(), UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->urlGenerator->generate('app_stripe_failed',array(), UrlGeneratorInterface::ABSOLUTE_URL),
            'line_items'=> [
                [
                    'price_data'=> [
                        'currency'=> "EUR",
                        'product_data'=> [
                            'name'=> $products->getTitle(),
                        ],
                        'unit_amount'=> $products->getPrice() * 100,
                    ],
                      'quantity'=> 1,
                ]
                  
                
              ],
            'mode' => 'payment',
          ]);

        
        // header("HTTP/1.1 303 See Other");
        // header("Location: " . $checkout_session->url);
        $this->redirect($checkout_session->url);
        return $this->redirect($checkout_session->url, 303);
    }
}
