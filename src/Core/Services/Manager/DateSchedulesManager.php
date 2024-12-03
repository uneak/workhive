<?php

    namespace App\Core\Services\Manager;

    use App\Core\Model\DateSchedulesModel;
    use App\Core\Model\RoomModel;
    use App\Core\Repository\DateSchedulesRepositoryInterface;

    /**
     * Repository class for the DateSchedules entity.
     *
     * @template T of DateSchedulesModel
     * @template-extends AbstractCrudManager<T>
     */
    class DateSchedulesManager extends AbstractCrudManager
    {
        public function __construct(
            private readonly DateSchedulesRepositoryInterface $repository
        ) {
            parent::__construct($this->repository);
        }

        public function getByRoom(int $roomId): array
    {
        return $this->repository->findByRoom($roomId);
    }

        /**
         * Get all schedules for a room grouped by date
         *
         * @param RoomModel $room Room model
         * @return array<string, array> Array with date as key and array of schedules as value
         */
        public function getByRoomGroupedByDate(RoomModel $room): array
        {
            $schedules = $this->getByRoom($room->getId());
            $grouped = [];

            foreach ($schedules as $schedule) {
                $dateKey = $schedule->getDate()->format('Y-m-d');
                if (!isset($grouped[$dateKey])) {
                    $grouped[$dateKey] = [];
                }
                $grouped[$dateKey][] = $schedule;
            }

            return $grouped;
        }
    }
