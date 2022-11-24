<?php

namespace App\Controller;

use App\Managers\ProductManager;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_default")
     */
    public function index(ProductRepository $productRespository): Response
    {
        $request = Request::createFromGlobals();
        $payment = $request->query->get("payment","");
        $products = $productRespository->findAll();
        

        return $this->render('default/index.html.twig', [
            'products' => $products,
            'payment' => $payment,
        ]);
    }

    /**
     * @Route("/buyProduct/{product_id}", name="buy_product")
     */
    public function buyProduct(ProductRepository $productRespository,ProductManager $productManager,$product_id): RedirectResponse
    {
        $product = $productRespository->find($product_id);
        $request = Request::createFromGlobals();
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        $url = $this->generateUrl('app_default');
        $url_response = $baseurl.$url;
        $chekout_url = $productManager->buy($product, $url_response);
        
        return $this->redirect($chekout_url);
    }
}
