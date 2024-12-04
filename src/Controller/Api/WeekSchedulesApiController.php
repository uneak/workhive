<?php

    namespace App\Controller\Api;

    use App\Core\Services\Manager\WeekSchedulesManager;
    use App\Entity\WeekSchedules;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Serializer\SerializerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * Controller for managing Week Schedules through API endpoints
     *
     * @template-extends AbstractApiController<WeekSchedules>
     */
    #[Route('api/v1/week-schedules', name: 'api_week_schedules_')]
    class WeekSchedulesApiController extends AbstractApiController
    {
        public function __construct(
            SerializerInterface $serializer,
            ValidatorInterface $validator,
            WeekSchedulesManager $manager,
        ) {
            parent::__construct($serializer, $validator, $manager, WeekSchedules::class);
        }

        /**
         * List all week schedules
         */
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(): JsonResponse
        {
            return $this->listAction();
        }

        /**
         * Create new week schedule
         */
        #[Route('/', name: 'new', methods: ['POST'])]
        public function create(Request $request): JsonResponse
        {
            return $this->createAction($request);
        }

        /**
         * Show specific week schedule
         */
        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(int $id): JsonResponse
        {
            return $this->showAction($id);
        }

        /**
         * Update week schedule
         */
        #[Route('/{id}', name: 'edit', methods: ['PUT'])]
        public function edit(Request $request, int $id): JsonResponse
        {
            return $this->editAction($request, $id);
        }

        /**
         * Delete week schedule
         */
        #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
        public function delete(int $id): JsonResponse
        {
            return $this->deleteAction($id);
        }
    }
