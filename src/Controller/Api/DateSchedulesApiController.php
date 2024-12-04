<?php

    namespace App\Controller\Api;

    use App\Core\Services\Manager\DateSchedulesManager;
    use App\Entity\DateSchedules;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Serializer\SerializerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * Controller for managing Date Schedules through API endpoints
     *
     * @template-extends AbstractApiController<DateSchedules>
     */
    #[Route('api/v1/date-schedules', name: 'api_date_schedules_')]
    class DateSchedulesApiController extends AbstractApiController
    {
        public function __construct(
            SerializerInterface $serializer,
            ValidatorInterface $validator,
            DateSchedulesManager $manager,
        ) {
            parent::__construct($serializer, $validator, $manager, DateSchedules::class);
        }

        /**
         * List all date schedules
         */
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(): JsonResponse
        {
            return $this->listAction();
        }

        /**
         * Create new date schedule
         */
        #[Route('/', name: 'new', methods: ['POST'])]
        public function create(Request $request): JsonResponse
        {
            return $this->createAction($request);
        }

        /**
         * Show specific date schedule
         */
        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(int $id): JsonResponse
        {
            return $this->showAction($id);
        }

        /**
         * Update date schedule
         */
        #[Route('/{id}', name: 'edit', methods: ['PUT'])]
        public function edit(Request $request, int $id): JsonResponse
        {
            return $this->editAction($request, $id);
        }

        /**
         * Delete date schedule
         */
        #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
        public function delete(int $id): JsonResponse
        {
            return $this->deleteAction($id);
        }
    }
