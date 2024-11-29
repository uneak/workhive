<?php

    namespace App\Controller\Admin;

    use App\Entity\Room;
    use App\Entity\DateSchedules;
    use App\Form\DateSchedulesType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/room/{idRoom}/date_schedule', name: 'app_admin_room_date_schedule_')]
    class RoomDateScheduleController extends AbstractController
    {
        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function create(EntityManagerInterface $em, Request $request, int $idRoom): Response
        {
            $room = $em->getRepository(Room::class)->find($idRoom);
            $action = $this->generateUrl('app_admin_room_date_schedule_new', ['idRoom' => $idRoom]);

            $dateSchedule = new DateSchedules();
            $dateSchedule->setRoom($room);

            return $this->handleForm($em, $request, $action, $dateSchedule);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(EntityManagerInterface $em, Request $request, int $idRoom, int $id): Response
        {
            $dateSchedule = $em->getRepository(DateSchedules::class)->find($id);
            $action = $this->generateUrl('app_admin_room_date_schedule_edit', ['id' => $id, 'idRoom' => $idRoom]);

            return $this->handleForm($em, $request, $action, $dateSchedule);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(EntityManagerInterface $em, Request $request, int $idRoom, int $id): Response
        {
            $dateSchedule = $em->getRepository(DateSchedules::class)->find($id);

            if ($this->isCsrfTokenValid('delete' . $dateSchedule->getId(), $request->getPayload()->getString('_token'))) {
                $em->remove($dateSchedule);
                $em->flush();
            }

            return $this->redirectToRoute('app_admin_room_schedule_list', ['idRoom' => $idRoom], Response::HTTP_SEE_OTHER);
        }

        protected function handleForm(
            EntityManagerInterface $em,
            Request $request,
            string $action,
            DateSchedules $dateSchedule,
            ?string $redirect = null
        ): Response {
            $form = $this->createForm(DateSchedulesType::class, $dateSchedule, ['action' => $action, 'method' => 'POST']);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($dateSchedule);
                $em->flush();

                if ($redirect) {
                    return $this->redirect($redirect);
                } else {
                    return $this->redirectToRoute('app_admin_room_schedule_list', ['idRoom' => $dateSchedule->getRoom()->getId()], Response::HTTP_SEE_OTHER);
                }
            }

            return $this->render('admin/room/schedule/date/form.html.twig', [
                'form' => $form,
                'dateSchedule' => $dateSchedule,
            ]);
        }
    }
