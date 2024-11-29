<?php

    namespace App\Controller\Admin;

    use App\Repository\RoomRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    class DashboardController extends AbstractController
    {
        #[Route('/admin', name: 'app_admin_dashboard')]
        public function index(RoomRepository $repository): Response
        {
            return $this->render('admin/dashboard.html.twig', [
                'rooms' => $repository->findAll(),
            ]);
        }
    }
