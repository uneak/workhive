<?php

    namespace App\Controller\Api;

    use App\Core\Services\Manager\ReservationEquipmentManager;
    use App\Entity\ReservationEquipment;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Serializer\SerializerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * Controller for managing Reservation Equipment through API endpoints
     *
     * @template-extends AbstractApiController<ReservationEquipment>
     */
    #[Route('api/v1/reservation-equipment', name: 'api_reservation_equipment_')]
    class ReservationEquipmentApiController extends AbstractApiController
    {
        public function __construct(
            SerializerInterface $serializer,
            ValidatorInterface $validator,
            ReservationEquipmentManager $manager,
        ) {
            parent::__construct($serializer, $validator, $manager, ReservationEquipment::class);
        }

        /**
         * List all reservation equipment
         */
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(): JsonResponse
        {
            return $this->listAction();
        }

        /**
         * Create new reservation equipment
         */
        #[Route('/', name: 'new', methods: ['POST'])]
        public function create(Request $request): JsonResponse
        {
            return $this->createAction($request);
        }

        /**
         * Show specific reservation equipment
         */
        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(int $id): JsonResponse
        {
            return $this->showAction($id);
        }

        /**
         * Update reservation equipment
         */
        #[Route('/{id}', name: 'edit', methods: ['PUT'])]
        public function edit(Request $request, int $id): JsonResponse
        {
            return $this->editAction($request, $id);
        }

        /**
         * Delete reservation equipment
         */
        #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
        public function delete(int $id): JsonResponse
        {
            return $this->deleteAction($id);
        }
    }
