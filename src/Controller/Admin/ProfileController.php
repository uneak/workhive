<?php

    namespace App\Controller\Admin;

    use App\Repository\RoomRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    class ProfileController extends AbstractController
    {
        #[Route('/profile', name: 'app_profile')]
        public function index(RoomRepository $repository): Response
        {
            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            if ($user) {
                $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            }

            return $this->render('admin/profile.html.twig', [
                'user' => $user,
            ]);
        }
    }
