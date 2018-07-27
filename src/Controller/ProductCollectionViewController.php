<?php

namespace Messere\Cart\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductCollectionViewController
{

    /**
     * @Route("/v1/product")
     */
    public function index(): Response
    {
        return new Response('test');
    }
}
