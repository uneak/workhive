<?php

    namespace App\Controller\Api;

    use App\Core\Services\Manager\RoomManager;
    use App\Entity\Room;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Serializer\SerializerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * Controller for managing Rooms through API endpoints
     *
     * @template-extends AbstractApiController<Room>
     */
    #[Route('api/v1/rooms', name: 'api_room_')]
    class RoomApiController extends AbstractApiController
    {
        public function __construct(
            SerializerInterface $serializer,
            ValidatorInterface $validator,
            RoomManager $manager,
        ) {
            parent::__construct($serializer, $validator, $manager, Room::class);
        }

        /**
         * List all rooms
         */
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(): JsonResponse
        {
            return $this->listAction();
        }

        /**
         * Create new room
         */
        #[Route('/', name: 'new', methods: ['POST'])]
        public function create(Request $request): JsonResponse
        {
            return $this->createAction($request);
        }

        /**
         * Show specific room
         */
        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(int $id): JsonResponse
        {
            return $this->showAction($id);
        }

        /**
         * Update room
         */
        #[Route('/{id}', name: 'edit', methods: ['PUT'])]
        public function edit(Request $request, int $id): JsonResponse
        {
            return $this->editAction($request, $id);
        }

        /**
         * Delete room
         */
        #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
        public function delete(int $id): JsonResponse
        {
            return $this->deleteAction($id);
        }
    }
