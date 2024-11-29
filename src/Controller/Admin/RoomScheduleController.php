<?php

    namespace App\Controller\Admin;

    use App\Entity\Room;
    use App\Entity\DateSchedules;
    use App\Entity\WeekSchedules;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/room/{idRoom}/schedule', name: 'app_admin_room_schedule_')]
    class RoomScheduleController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(EntityManagerInterface $em, int $idRoom): Response
        {
            $room = $em->getRepository(Room::class)->find($idRoom);
            $weekSchedulesRepository = $em->getRepository(WeekSchedules::class);
            $dateSchedulesRepository = $em->getRepository(DateSchedules::class);

            $weekSchedules = [];
            $days = [
                0 => 'Sunday',
                1 => 'Monday',
                2 => 'Tuesday',
                3 => 'Wednesday',
                4 => 'Thursday',
                5 => 'Friday',
                6 => 'Saturday',
            ];

            foreach ($days as $dayId => $day) {
                $weekSchedules[$dayId] = $weekSchedulesRepository->findBy([
                    'room' => $room->getId(),
                    'weekDay' => $dayId
                ]);
            }

            $dates = $dateSchedulesRepository->findBy(['room' => $room->getId()]);
            $dateSchedules = [];

            foreach ($dates as $schedule) {
                $dateKey = $schedule->getDate()->format('Y-m-d');
                if (!isset($dateSchedules[$dateKey])) {
                    $dateSchedules[$dateKey] = [];
                }
                $dateSchedules[$dateKey][] = $schedule;
            }

            return $this->render('admin/room/schedule/list.html.twig', [
                'room' => $room,
                'days' => $days,
                'weekSchedules' => $weekSchedules,
                'dateSchedules' => $dateSchedules,
            ]);
        }
    }
