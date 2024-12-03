<?php

    namespace App\Controller\Admin;

    use App\Core\Model\WeekSchedulesModel;
    use App\Core\Services\Manager\DateSchedulesManager;
    use App\Core\Services\Manager\RoomManager;
    use App\Core\Services\Manager\WeekSchedulesManager;
    use App\Entity\DateSchedules;
    use App\Entity\Room;
    use App\Entity\WeekSchedules;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/room/{idRoom}/schedule', name: 'app_admin_room_schedule_')]
    class SchedulesController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(
            WeekSchedulesManager $weekSchedulesManager,
            DateSchedulesManager $dateSchedulesManager,
            RoomManager $roomManager,
            int $idRoom
        ): Response {
            $room = $roomManager->get($idRoom);

            return $this->render('admin/room/schedules/list.html.twig', [
                'room'          => $room,
                'days'          => $weekSchedulesManager->getWeekDays(),
                'weekSchedules' => $weekSchedulesManager->getByRoomGroupedByDay($room),
                'dateSchedules' => $dateSchedulesManager->getByRoomGroupedByDate($room),
            ]);
        }
    }
