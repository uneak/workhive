<?php

    namespace App\Core\Services\Manager;

    use App\Core\Model\RoomModel;
    use App\Core\Model\WeekSchedulesModel;
    use App\Repository\WeekSchedulesRepository;

    /**
     * Repository class for the WeekSchedules entity.
     *
     * @template T of WeekSchedulesModel
     * @template-extends AbstractCrudManager<T>
     */
    class WeekSchedulesManager extends AbstractCrudManager
    {
        public function __construct(
            private readonly WeekSchedulesRepository $repository
        ) {
            parent::__construct($this->repository);
        }

        /**
         * Get all week schedules by room ID
         *
         * @param RoomModel $room The ID of the room
         * @return array<int, array<WeekSchedulesModel>> Array with day ID as key and array of schedules as value
         */
        public function getByRoomGroupedByDay(RoomModel $room): array
        {
            $schedules = [];
            $results = $this->repository->findByRoom($room->getId());
            foreach ($results as $result) {
                if (!isset($schedules[$result->getWeekDay()])) {
                    $schedules[$result->getWeekDay()] = [];
                }
                $schedules[$result->getWeekDay()][] = $result;
            }

            return $schedules;
        }

        /**
         * Get all week schedules by room ID and day
         *
         * @param int $roomId The ID of the room
         * @param int $weekDay The day of the week (0-6)
         * @return array Array of schedules for the specified day
         */
        public function getByRoomAndDay(int $roomId, int $weekDay): array
        {
            return $this->repository->findByRoomAndDay($roomId, $weekDay);
        }

        /**
         * Get list of available week days
         *
         * @return array<int, string> Array with day ID as key and day name as value
         */
        public function getWeekDays(): array
        {
            return WeekSchedulesModel::WEEK_DAYS;
        }


    }
