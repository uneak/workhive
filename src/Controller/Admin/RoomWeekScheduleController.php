<?php

    namespace App\Controller\Admin;

    use App\Entity\Room;
    use App\Entity\WeekSchedules;
    use App\Form\WeekSchedulesType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/room/{idRoom}/week_schedule', name: 'app_admin_room_week_schedule_')]
    class RoomWeekScheduleController extends AbstractController
    {
        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function create(EntityManagerInterface $em, Request $request, int $idRoom): Response
        {
            $room = $em->getRepository(Room::class)->find($idRoom);
            $action = $this->generateUrl('app_admin_room_week_schedule_new', ['idRoom' => $idRoom]);

            $weekSchedule = new WeekSchedules();
            $weekSchedule->setRoom($room);

            return $this->handleForm($em, $request, $action, $weekSchedule);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(EntityManagerInterface $em, Request $request, int $idRoom, int $id): Response
        {
            $weekSchedule = $em->getRepository(WeekSchedules::class)->find($id);
            $action = $this->generateUrl('app_admin_room_week_schedule_edit', ['id' => $id, 'idRoom' => $idRoom]);

            return $this->handleForm($em, $request, $action, $weekSchedule);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(EntityManagerInterface $em, Request $request, int $idRoom, int $id): Response
        {
            $weekSchedule = $em->getRepository(WeekSchedules::class)->find($id);

            if ($this->isCsrfTokenValid('delete' . $weekSchedule->getId(), $request->getPayload()->getString('_token'))) {
                $em->remove($weekSchedule);
                $em->flush();
            }

            return $this->redirectToRoute('app_admin_room_schedule_list', ['idRoom' => $idRoom], Response::HTTP_SEE_OTHER);
        }

        protected function handleForm(
            EntityManagerInterface $em,
            Request $request,
            string $action,
            WeekSchedules $weekSchedule,
            ?string $redirect = null
        ): Response {
            $form = $this->createForm(WeekSchedulesType::class, $weekSchedule, ['action' => $action, 'method' => 'POST']);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($weekSchedule);
                $em->flush();

                if ($redirect) {
                    return $this->redirect($redirect);
                } else {
                    return $this->redirectToRoute('app_admin_room_schedule_list', ['idRoom' => $weekSchedule->getRoom()->getId()], Response::HTTP_SEE_OTHER);
                }
            }

            return $this->render('admin/room/schedule/week/form.html.twig', [
                'form' => $form,
                'weekSchedule' => $weekSchedule,
            ]);
        }
    }
