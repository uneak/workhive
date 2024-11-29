<?php

    namespace App\Controller;

    use App\Repository\RoomRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    class HomeController extends AbstractController
    {
        #[Route('/', name: 'app_home')]
        public function index(RoomRepository $repository): Response
        {
            return $this->render('front/home.html.twig', [
                'rooms' => $repository->findAll(),
            ]);
        }
    }
