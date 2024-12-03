<?php

    namespace App\Controller\Admin;

    use App\Core\Model\ObjectModel;
    use App\Core\Services\Manager\RoomEquipmentManager;
    use App\Core\Services\Manager\RoomManager;
    use App\Entity\RoomEquipment;
    use App\Form\RoomEquipmentType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/room/{idRoom}/equipment', name: 'app_admin_room_equipment_')]
    class RoomEquipmentController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(RoomEquipmentManager $manager, RoomManager $roomManager, int $idRoom): Response
        {
            return $this->render('admin/room/equipment/list.html.twig', [
                'roomEquipments' => $manager->getByRoom($idRoom),
                'room'          => $roomManager->get($idRoom)
            ]);
        }

        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function create(
            RoomEquipmentManager $manager,
            RoomManager $roomManager,
            Request $request,
            int $idRoom
        ): Response {
            $room = $roomManager->get($idRoom);
            $roomEquipment = new RoomEquipment();
            $roomEquipment->setRoom($room);

            /** @var RoomEquipment $data */
            [$isSubmitted, $form, $data] = $this->handleForm(
                $request,
                RoomEquipmentType::class,
                $roomEquipment,
                [
                    'action' => $this->generateUrl('app_admin_room_equipment_new', ['idRoom' => $room->getId()]),
                    'method' => 'POST'
                ]
            );

            if ($isSubmitted) {
                $manager->save($data, true);

                return $this->redirectToRoute('app_admin_room_equipment_list',
                    ['idRoom' => $room->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('admin/room/equipment/form.html.twig', [
                'form'          => $form,
                'roomEquipment' => $data
            ]);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(
            RoomEquipmentManager $manager,
            Request $request,
            int $idRoom,
            int $id
        ): Response {
            /** @var RoomEquipment $data */
            [$isSubmitted, $form, $data] = $this->handleForm(
                $request,
                RoomEquipmentType::class,
                $manager->get($id),
                [
                    'action' => $this->generateUrl('app_admin_room_equipment_edit',
                        ['id' => $id, 'idRoom' => $idRoom]),
                    'method' => 'POST'
                ]
            );

            if ($isSubmitted) {
                $manager->save($data, true);

                return $this->redirectToRoute('app_admin_room_equipment_list',
                    ['idRoom' => $idRoom], Response::HTTP_SEE_OTHER);
            }

            return $this->render('admin/room/equipment/form.html.twig', [
                'form'          => $form,
                'roomEquipment' => $data
            ]);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(
            RoomEquipmentManager $manager,
            Request $request,
            int $idRoom,
            int $id
        ): Response {
            if ($this->isCsrfTokenValid('delete' . $id, $request->getPayload()->getString('_token'))) {
                $manager->remove($manager->get($id), true);
            }

            return $this->redirectToRoute('app_admin_room_equipment_list',
                ['idRoom' => $idRoom], Response::HTTP_SEE_OTHER);
        }

        /**
         * @param Request     $request
         * @param string      $type
         * @param ObjectModel $data
         * @param array       $options
         *
         * @return array{0: bool, 1: FormInterface, 2: ObjectModel}
         */
        protected function handleForm(
            Request $request,
            string $type,
            ObjectModel $data,
            array $options,
        ): array {
            $form = $this->createForm($type, $data, $options);
            $form->handleRequest($request);

            return [
                $form->isSubmitted() && $form->isValid(),
                $form,
                $form->getData()
            ];
        }
    }
