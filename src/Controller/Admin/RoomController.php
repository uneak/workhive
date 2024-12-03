<?php

    namespace App\Controller\Admin;

    use App\Core\Model\ObjectModel;
    use App\Core\Services\Manager\RoomManager;
    use App\Entity\Room;
    use App\Form\RoomType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/room', name: 'app_admin_room_')]
    class RoomController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(RoomManager $manager): Response
        {
            return $this->render('admin/room/list.html.twig', [
                'rooms' => $manager->all()
            ]);
        }

        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function create(RoomManager $manager, Request $request): Response
        {
            $room = new Room();

            /** @var Room $data */
            [$isSubmitted, $form, $data] = $this->handleForm(
                $request,
                RoomType::class,
                $room,
                [
                    'action' => $this->generateUrl('app_admin_room_new'),
                    'method' => 'POST'
                ]
            );

            if ($isSubmitted) {
                $manager->save($data, true);

                return $this->redirectToRoute('app_admin_room_list', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('admin/room/form.html.twig', [
                'form' => $form,
                'room' => $data
            ]);
        }

        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(RoomManager $manager, int $id): Response
        {
            return $this->render('admin/room/show.html.twig', [
                'room' => $manager->get($id),
            ]);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(RoomManager $manager, Request $request, int $id): Response
        {
            /** @var Room $data */
            [$isSubmitted, $form, $data] = $this->handleForm(
                $request,
                RoomType::class,
                $manager->get($id),
                [
                    'action' => $this->generateUrl('app_admin_room_edit', ['id' => $id]),
                    'method' => 'POST'
                ]
            );

            if ($isSubmitted) {
                $manager->save($data, true);

                return $this->redirectToRoute('app_admin_room_list', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('admin/room/form.html.twig', [
                'form' => $form,
                'room' => $data
            ]);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(RoomManager $manager, Request $request, int $id): Response
        {
            if ($this->isCsrfTokenValid('delete' . $id, $request->getPayload()->getString('_token'))) {
                $manager->remove($manager->get($id), true);
            }

            return $this->redirectToRoute('app_admin_room_list', [], Response::HTTP_SEE_OTHER);
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
