<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ProductRepository;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_default")
     */
    public function index(ProductRepository $productRespository): Response
    {
        $products = $productRespository->findAll();

        return $this->render('default/index.html.twig', [
            'products' => $products,
        ]);
    }
}
