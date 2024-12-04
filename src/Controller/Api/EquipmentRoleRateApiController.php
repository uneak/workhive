<?php

    namespace App\Controller\Api;

    use App\Core\Services\Manager\EquipmentRoleRateManager;
    use App\Entity\EquipmentRoleRate;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Serializer\SerializerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * Controller for managing Equipment Role Rates through API endpoints
     *
     * @template-extends AbstractApiController<EquipmentRoleRate>
     */
    #[Route('api/v1/equipment-role-rates', name: 'api_equipment_role_rate_')]
    class EquipmentRoleRateApiController extends AbstractApiController
    {
        public function __construct(
            SerializerInterface $serializer,
            ValidatorInterface $validator,
            EquipmentRoleRateManager $manager,
        ) {
            parent::__construct($serializer, $validator, $manager, EquipmentRoleRate::class);
        }

        /**
         * List all equipment role rates
         */
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(): JsonResponse
        {
            return $this->listAction();
        }

        /**
         * Create new equipment role rate
         */
        #[Route('/', name: 'new', methods: ['POST'])]
        public function create(Request $request): JsonResponse
        {
            return $this->createAction($request);
        }

        /**
         * Show specific equipment role rate
         */
        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(int $id): JsonResponse
        {
            return $this->showAction($id);
        }

        /**
         * Update equipment role rate
         */
        #[Route('/{id}', name: 'edit', methods: ['PUT'])]
        public function edit(Request $request, int $id): JsonResponse
        {
            return $this->editAction($request, $id);
        }

        /**
         * Delete equipment role rate
         */
        #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
        public function delete(int $id): JsonResponse
        {
            return $this->deleteAction($id);
        }
    }
