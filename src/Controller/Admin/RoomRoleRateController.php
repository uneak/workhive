<?php

    namespace App\Controller\Admin;

    use App\Core\Model\ObjectModel;
    use App\Core\Services\Manager\RoomManager;
    use App\Core\Services\Manager\RoomRoleRateManager;
    use App\Entity\RoomRoleRate;
    use App\Form\RoomRoleRateType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/room/{idRoom}/rate', name: 'app_admin_room_role_rate_')]
    class RoomRoleRateController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(RoomRoleRateManager $manager, RoomManager $roomManager, int $idRoom): Response
        {
            return $this->render('admin/room/role_rate/list.html.twig', [
                'roomRoleRates' => $manager->getByRoom($idRoom),
                'room'          => $roomManager->get($idRoom)
            ]);
        }

        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function create(
            RoomRoleRateManager $manager,
            RoomManager $roomManager,
            Request $request,
            int $idRoom
        ): Response {
            $room = $roomManager->get($idRoom);
            $roomRoleRate = new RoomRoleRate();
            $roomRoleRate->setRoom($room);

            /** @var RoomRoleRate $data */
            [$isSubmitted, $form, $data] = $this->handleForm(
                $request,
                RoomRoleRateType::class,
                $roomRoleRate,
                [
                    'action' => $this->generateUrl('app_admin_room_role_rate_new', ['idRoom' => $room->getId()]),
                    'method' => 'POST'
                ]
            );

            if ($isSubmitted) {
                $manager->save($data, true);

                return $this->redirectToRoute('app_admin_room_role_rate_list',
                    ['idRoom' => $room->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('admin/room/role_rate/form.html.twig', [
                'form'         => $form,
                'roomRoleRate' => $data
            ]);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(
            RoomRoleRateManager $manager,
            Request $request,
            int $idRoom,
            int $id
        ): Response {
            /** @var RoomRoleRate $data */
            [$isSubmitted, $form, $data] = $this->handleForm(
                $request,
                RoomRoleRateType::class,
                $manager->get($id),
                [
                    'action' => $this->generateUrl('app_admin_room_role_rate_edit',
                        ['id' => $id, 'idRoom' => $idRoom]),
                    'method' => 'POST'
                ]
            );

            if ($isSubmitted) {
                $manager->save($data, true);

                return $this->redirectToRoute('app_admin_room_role_rate_list',
                    ['idRoom' => $idRoom], Response::HTTP_SEE_OTHER);
            }

            return $this->render('admin/room/role_rate/form.html.twig', [
                'form'         => $form,
                'roomRoleRate' => $data
            ]);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(
            RoomRoleRateManager $manager,
            Request $request,
            int $idRoom,
            int $id
        ): Response {
            if ($this->isCsrfTokenValid('delete' . $id, $request->getPayload()->getString('_token'))) {
                $manager->remove($manager->get($id), true);
            }

            return $this->redirectToRoute('app_admin_room_role_rate_list',
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
