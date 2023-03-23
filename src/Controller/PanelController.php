<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PanelController extends AbstractController
{
    /**
     * @Route("/", name="app_panel")
     */
    public function index(): Response
    {
        return $this->render('panel/index.html.twig', [
            'controller_name' => 'PanelController',
        ]);
    }
}
