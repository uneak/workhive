<?php

    namespace App\Controller\Api;

    use App\Core\Services\Manager\PaymentManager;
    use App\Entity\Payment;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Serializer\SerializerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * Controller for managing Payments through API endpoints
     *
     * @template-extends AbstractApiController<Payment>
     */
    #[Route('api/v1/payments', name: 'api_payment_')]
    class PaymentApiController extends AbstractApiController
    {
        public function __construct(
            SerializerInterface $serializer,
            ValidatorInterface $validator,
            PaymentManager $manager,
        ) {
            parent::__construct($serializer, $validator, $manager, Payment::class);
        }

        /**
         * List all payments
         */
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(): JsonResponse
        {
            return $this->listAction();
        }

        /**
         * Create new payment
         */
        #[Route('/', name: 'new', methods: ['POST'])]
        public function create(Request $request): JsonResponse
        {
            return $this->createAction($request);
        }

        /**
         * Show specific payment
         */
        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(int $id): JsonResponse
        {
            return $this->showAction($id);
        }

        /**
         * Update payment
         */
        #[Route('/{id}', name: 'edit', methods: ['PUT'])]
        public function edit(Request $request, int $id): JsonResponse
        {
            return $this->editAction($request, $id);
        }

        /**
         * Delete payment
         */
        #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
        public function delete(int $id): JsonResponse
        {
            return $this->deleteAction($id);
        }
    }
