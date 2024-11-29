<?php

    namespace App\Controller\Admin;

    use App\Entity\Room;
    use App\Form\RoomType;
    use App\Repository\RoomRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/room', name: 'app_admin_room_')]
    class RoomController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(RoomRepository $repository): Response
        {
            return $this->render('admin/room/list.html.twig', [
                'rooms' => $repository->findAll()
            ]);
        }

        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function create(EntityManagerInterface $em, Request $request): Response
        {
            $room = new Room();
            $action = $this->generateUrl('app_admin_room_new');

            return $this->handleForm($em, $request, $action, $room);
        }

        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(RoomRepository $repository, int $id): Response
        {
            return $this->render('admin/room/show.html.twig', [
                'room' => $repository->find($id),
            ]);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(EntityManagerInterface $em, Request $request, int $id): Response
        {
            $room = $em->getRepository(Room::class)->find($id);
            $action = $this->generateUrl('app_admin_room_edit', ['id' => $id]);

            return $this->handleForm($em, $request, $action, $room);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(EntityManagerInterface $em, Request $request, int $id): Response
        {
            $room = $em->getRepository(Room::class)->find($id);

            if ($this->isCsrfTokenValid('delete' . $room->getId(), $request->getPayload()->getString('_token'))) {
                $em->remove($room);
                $em->flush();
            }

            return $this->redirectToRoute('app_admin_room_list', [], Response::HTTP_SEE_OTHER);
        }

        protected function handleForm(
            EntityManagerInterface $em,
            Request $request,
            string $action,
            Room $room,
            ?string $redirect = null
        ): Response {
            $form = $this->createForm(RoomType::class, $room, ['action' => $action, 'method' => 'POST']);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($room);
                $em->flush();

                if ($redirect) {
                    return $this->redirect($redirect);
                } else {
                    return $this->redirectToRoute('app_admin_room_list');
                }
            }

            return $this->render('admin/room/form.html.twig', [
                'form' => $form,
                'room' => $room,
            ]);
        }
    }
