<?php

namespace App\Controller\Admin;

use App\Core\Model\ObjectModel;
use App\Core\Services\Manager\RoomManager;
use App\Core\Services\Manager\WeekSchedulesManager;
use App\Entity\WeekSchedules;
use App\Form\WeekSchedulesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/room/{idRoom}/schedule/week', name: 'app_admin_week_schedules_')]
class WeekSchedulesController extends AbstractController
{

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function create(
        WeekSchedulesManager $manager,
        RoomManager $roomManager,
        Request $request,
        int $idRoom
    ): Response {
        $room = $roomManager->get($idRoom);
        $weekSchedule = new WeekSchedules();
        $weekSchedule->setRoom($room);

        [$isSubmitted, $form, $data] = $this->handleForm(
            $request,
            WeekSchedulesType::class,
            $weekSchedule,
            [
                'action' => $this->generateUrl('app_admin_week_schedules_new', ['idRoom' => $room->getId()]),
                'method' => 'POST'
            ]
        );

        if ($isSubmitted) {
            $manager->save($data, true);
            return $this->redirectToRoute('app_admin_room_schedule_list',
                ['idRoom' => $room->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/room/schedules/week_schedules/form.html.twig', [
            'form' => $form,
            'weekSchedule' => $data
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        WeekSchedulesManager $manager,
        Request $request,
        int $idRoom,
        int $id
    ): Response {
        [$isSubmitted, $form, $data] = $this->handleForm(
            $request,
            WeekSchedulesType::class,
            $manager->get($id),
            [
                'action' => $this->generateUrl('app_admin_week_schedules_edit',
                    ['id' => $id, 'idRoom' => $idRoom]),
                'method' => 'POST'
            ]
        );

        if ($isSubmitted) {
            $manager->save($data, true);
            return $this->redirectToRoute('app_admin_room_schedule_list',
                ['idRoom' => $idRoom], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/room/schedules/week_schedules/form.html.twig', [
            'form' => $form,
            'weekSchedule' => $data
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(
        WeekSchedulesManager $manager,
        Request $request,
        int $idRoom,
        int $id
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $id, $request->getPayload()->getString('_token'))) {
            $manager->remove($manager->get($id), true);
        }

        return $this->redirectToRoute('app_admin_room_schedule_list',
            ['idRoom' => $idRoom], Response::HTTP_SEE_OTHER);
    }

    /**
     * @param Request $request
     * @param string $type
     * @param ObjectModel $data
     * @param array $options
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
