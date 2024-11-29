<?php

    namespace App\Controller\Admin;

    use App\Entity\Room;
    use App\Entity\RoomRoleRate;
    use App\Form\RoomRoleRateType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/room/{idRoom}/rate', name: 'app_admin_room_rate_')]
    class RoomRoleRateController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(EntityManagerInterface $em, int $idRoom): Response
        {
            $room = $em->getRepository(Room::class)->find($idRoom);
            $roomRoleRates = $em->getRepository(RoomRoleRate::class)->findBy(['room' => $room]);

            return $this->render('admin/room/rate/list.html.twig', [
                'room' => $room,
                'roomRoleRates' => $roomRoleRates
            ]);
        }

        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function create(EntityManagerInterface $em, Request $request, int $idRoom): Response
        {
            $room = $em->getRepository(Room::class)->find($idRoom);
            $action = $this->generateUrl('app_admin_room_rate_new', ['idRoom' => $idRoom]);

            $roomRoleRate = new RoomRoleRate();
            $roomRoleRate->setRoom($room);

            return $this->handleForm($em, $request, $action, $roomRoleRate);
        }

        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(EntityManagerInterface $em, int $id): Response
        {
            $roomRoleRate = $em->getRepository(RoomRoleRate::class)->find($id);

            return $this->render('admin/room/rate/show.html.twig', [
                'roomRoleRate' => $roomRoleRate,
            ]);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(EntityManagerInterface $em, Request $request, int $idRoom, int $id): Response
        {
            $roomRoleRate = $em->getRepository(RoomRoleRate::class)->find($id);
            $action = $this->generateUrl('app_admin_room_rate_edit', ['id' => $id, 'idRoom' => $idRoom]);

            return $this->handleForm($em, $request, $action, $roomRoleRate);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(EntityManagerInterface $em, Request $request, int $idRoom, int $id): Response
        {
            $roomRoleRate = $em->getRepository(RoomRoleRate::class)->find($id);

            if ($this->isCsrfTokenValid('delete' . $roomRoleRate->getId(), $request->getPayload()->getString('_token'))) {
                $em->remove($roomRoleRate);
                $em->flush();
            }

            return $this->redirectToRoute('app_admin_room_rate_list', ['idRoom' => $idRoom], Response::HTTP_SEE_OTHER);
        }

        protected function handleForm(
            EntityManagerInterface $em,
            Request $request,
            string $action,
            RoomRoleRate $roomRoleRate,
            ?string $redirect = null
        ): Response {
            $form = $this->createForm(RoomRoleRateType::class, $roomRoleRate, ['action' => $action, 'method' => 'POST']);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($roomRoleRate);
                $em->flush();

                if ($redirect) {
                    return $this->redirect($redirect);
                } else {
                    return $this->redirectToRoute('app_admin_room_rate_list', ['idRoom' => $roomRoleRate->getRoom()->getId()], Response::HTTP_SEE_OTHER);
                }
            }

            return $this->render('admin/room/rate/form.html.twig', [
                'form' => $form,
                'roomRoleRate' => $roomRoleRate,
            ]);
        }
    }
