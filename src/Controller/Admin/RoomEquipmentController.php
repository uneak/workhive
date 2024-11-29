<?php

    namespace App\Controller\Admin;

    use App\Entity\Room;
    use App\Entity\RoomEquipment;
    use App\Form\RoomEquipmentType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/room/{idRoom}/equipment', name: 'app_admin_room_equipment_')]
    class RoomEquipmentController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(EntityManagerInterface $em, int $idRoom): Response
        {
            $room = $em->getRepository(Room::class)->find($idRoom);
            $roomEquipments = $em->getRepository(RoomEquipment::class)->findBy(['room' => $room]);

            return $this->render('admin/room/equipment/list.html.twig', [
                'room' => $room,
                'roomEquipments' => $roomEquipments
            ]);
        }

        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function create(EntityManagerInterface $em, Request $request, int $idRoom): Response
        {
            $room = $em->getRepository(Room::class)->find($idRoom);
            $action = $this->generateUrl('app_admin_room_equipment_new', ['idRoom' => $idRoom]);

            $roomEquipment = new RoomEquipment();
            $roomEquipment->setRoom($room);

            return $this->handleForm($em, $request, $action, $roomEquipment);
        }

        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(EntityManagerInterface $em, int $id): Response
        {
            $roomEquipment = $em->getRepository(RoomEquipment::class)->find($id);

            return $this->render('admin/room/equipment/show.html.twig', [
                'roomEquipment' => $roomEquipment,
            ]);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(EntityManagerInterface $em, Request $request, int $idRoom, int $id): Response
        {
            $roomEquipment = $em->getRepository(RoomEquipment::class)->find($id);
            $action = $this->generateUrl('app_admin_room_equipment_edit', ['id' => $id, 'idRoom' => $idRoom]);

            return $this->handleForm($em, $request, $action, $roomEquipment);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(EntityManagerInterface $em, Request $request, int $idRoom, int $id): Response
        {
            $roomEquipment = $em->getRepository(RoomEquipment::class)->find($id);

            if ($this->isCsrfTokenValid('delete' . $roomEquipment->getId(), $request->getPayload()->getString('_token'))) {
                $em->remove($roomEquipment);
                $em->flush();
            }

            return $this->redirectToRoute('app_admin_room_equipment_list', ['idRoom' => $idRoom], Response::HTTP_SEE_OTHER);
        }

        protected function handleForm(
            EntityManagerInterface $em,
            Request $request,
            string $action,
            RoomEquipment $roomEquipment,
            ?string $redirect = null
        ): Response {
            $form = $this->createForm(RoomEquipmentType::class, $roomEquipment, ['action' => $action, 'method' => 'POST']);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($roomEquipment);
                $em->flush();

                if ($redirect) {
                    return $this->redirect($redirect);
                } else {
                    return $this->redirectToRoute('app_admin_room_equipment_list', ['idRoom' => $roomEquipment->getRoom()->getId()], Response::HTTP_SEE_OTHER);
                }
            }

            return $this->render('admin/room/equipment/form.html.twig', [
                'form' => $form,
                'roomEquipment' => $roomEquipment,
            ]);
        }
    }
