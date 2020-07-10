<?php

namespace Infra\ItemList\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/item-list", name="item_list_")
 */
class CreateListController
{
    /**
     * @Route("/create-list", name="create_list", methods={"GET", "POST"})
     */
    public function __invoke(Request $request): Response
    {
        return new Response('<html><head><meta charset="UTF-8"/></head><body></body></html>');
    }
}
