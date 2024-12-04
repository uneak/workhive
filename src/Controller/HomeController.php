<?php

    namespace App\Controller;

    use App\Core\Services\Manager\RoomManager;
    use App\Repository\RoomRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\PropertyAccess\PropertyAccess;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Serializer\SerializerInterface;

    class HomeController extends AbstractController
    {
        #[Route('/', name: 'app_home')]
        public function index(RoomManager $manager, SerializerInterface $serializer): Response
        {


            return $this->render('front/home.html.twig', [
                'rooms' => $manager->all(),
            ]);
        }
    }
