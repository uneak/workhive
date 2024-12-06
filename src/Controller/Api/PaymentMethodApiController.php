<?php

    namespace App\Controller\Api;

    use App\Core\Model\ObjectModel;
    use App\Core\Services\Manager\PaymentMethodManager;
    use App\Entity\Payment;
    use App\Entity\PaymentMethod;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use OpenApi\Attributes as OA;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Serializer\SerializerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * API Controller for managing payment methods
     */
    #[Route('api/v1/payment-methods', name: 'api_payment_method_')]
    #[OA\Tag(name: 'payment-methods', description: 'Operations for managing payment methods')]
    class PaymentMethodApiController extends AbstractApiController
    {
        public function __construct(
            SerializerInterface $serializer,
            ValidatorInterface $validator,
            PaymentMethodManager $manager,
        ) {
            parent::__construct($serializer, $validator, $manager, PaymentMethod::class);
        }

        #[OA\Get(
            operationId: 'getPaymentMethods',
            description: "Retrieves a list of all available payment methods with advanced filtering capabilities.",
            summary: 'List all payment methods',
            security: [['Bearer' => []]],
            tags: ['payment-methods'],
            parameters: [
                new OA\Parameter(
                    name: 'order',
                    description: "Sort direction for the results\n\n" .
                    "| Value | Description |\n" .
                    "|-------|-------------|\n" .
                    "| `asc` | Ascending order (A to Z) |\n" .
                    "| `desc` | Descending order (Z to A) |",
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(
                        type: 'string',
                        default: 'asc',
                        enum: ['asc', 'desc']
                    ),
                ),
                new OA\Parameter(
                    name: 'filters[]',
                    description: "Filter criteria in format `field:value`\n\n" .
                    "### Simple Filters\n" .
                    "| Filter | Example | Description |\n" .
                    "|--------|---------|-------------|\n" .
                    "| `type` | `type:credit_card` | Filter by payment type |\n" .
                    "| `status` | `status:active` | Filter by status |\n\n" .
                    "### List Filters\n" .
                    "Use `[]` suffix for array values:\n" .
                    "```\n" .
                    "types[]:credit_card,cash\n" .
                    "```",
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(
                        type: 'array',
                        items: new OA\Items(
                            type: 'string',
                            example: 'status:active'
                        )
                    )
                ),
                new OA\Parameter(
                    name: 'page',
                    description: "Page number for pagination\n\n" .
                    "| Value | Description |\n" .
                    "|-------|-------------|\n" .
                    "| `minimum` | 1 |",
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(
                        type: 'integer',
                        default: 1,
                        minimum: 1
                    )
                ),
                new OA\Parameter(
                    name: 'limit',
                    description: "Number of items per page\n\n" .
                    "| Constraint | Value |\n" .
                    "|------------|-------|\n" .
                    "| `minimum` | 1 |\n" .
                    "| `maximum` | 100 |",
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(
                        type: 'integer',
                        default: 10,
                        maximum: 100,
                        minimum: 1
                    )
                )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: "Returns a list of payment methods matching the specified criteria.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'data', type: 'array',
                                items: new OA\Items(ref: new Model(type: PaymentMethod::class,
                                    groups: [ObjectModel::READ_PREFIX]))),
                            new OA\Property(property: 'meta', properties: [
                                new OA\Property(property: 'total', type: 'integer'),
                                new OA\Property(property: 'page', type: 'integer'),
                                new OA\Property(property: 'per_page', type: 'integer')
                            ])
                        ]
                    )
                ),
                new OA\Response(
                    response: 401,
                    description: "Authentication is required to access this endpoint.\n\n" .
                    "#### Common Causes\n" .
                    "- Missing authentication token\n" .
                    "- Invalid authentication token\n" .
                    "- Expired authentication token"
                )
            ]
        )]
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(Request $request): JsonResponse
        {
            return $this->listEntities($request, ObjectModel::READ_PREFIX);
        }

        #[OA\Post(
            operationId: 'createPaymentMethod',
            description: "Creates a new payment method with the provided details.",
            summary: 'Create a new payment method',
            security: [['Bearer' => []]],
            requestBody: new OA\RequestBody(
                description: "Details of the payment method to be created",
                required: true,
                content: new OA\JsonContent(ref: new Model(type: PaymentMethod::class,
                    groups: [ObjectModel::CREATE_PREFIX]))
            ),
            tags: ['payment-methods'],
            responses: [
                new OA\Response(
                    response: 201,
                    description: "Payment method successfully created.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'message', type: 'string',
                                example: 'Payment method created successfully'),
                            new OA\Property(property: 'payment_method', ref: new Model(type: PaymentMethod::class,
                                groups: [ObjectModel::READ_PREFIX]))
                        ]
                    )
                ),
                new OA\Response(
                    response: 400,
                    description: "The request contains invalid data.\n\n" .
                    "#### Common Causes\n" .
                    "- Missing required fields\n" .
                    "- Invalid field values\n" .
                    "- Validation constraints not met",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string'),
                            new OA\Property(
                                property: 'details',
                                type: 'array',
                                items: new OA\Items(type: 'string')
                            )
                        ]
                    )
                ),
                new OA\Response(
                    response: 401,
                    description: "Authentication is required to create a payment method.\n\n" .
                    "#### Common Causes\n" .
                    "- Missing authentication token\n" .
                    "- Invalid authentication token\n" .
                    "- Expired authentication token"
                )
            ]
        )]
        #[Route('/', name: 'new', methods: ['POST'])]
        public function create(Request $request): JsonResponse
        {
            return $this->createEntity($request);
        }

        #[OA\Get(
            operationId: 'getPaymentMethod',
            description: "Retrieves detailed information about a specific payment method identified by its unique ID.",
            summary: 'Get payment method details',
            security: [['Bearer' => []]],
            tags: ['payment-methods'],
            parameters: [
                new OA\Parameter(
                    name: 'id',
                    description: 'Unique identifier of the payment method',
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer')
                )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Returns the requested payment method details',
                    content: new OA\JsonContent(ref: new Model(type: PaymentMethod::class,
                        groups: [ObjectModel::READ_PREFIX]))
                ),
                new OA\Response(
                    response: 404,
                    description: 'Payment method not found',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'Payment method not found')
                        ]
                    )
                )
            ]
        )]
        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(int $id): JsonResponse
        {
            return $this->showEntity($id, ObjectModel::READ_PREFIX);
        }

        #[OA\Put(
            operationId: 'updatePaymentMethod',
            description: "Updates an existing payment method with the provided details.",
            summary: 'Update a payment method',
            security: [['Bearer' => []]],
            requestBody: new OA\RequestBody(
                description: 'Updated payment method details',
                required: true,
                content: new OA\JsonContent(ref: new Model(type: PaymentMethod::class,
                    groups: [ObjectModel::UPDATE_PREFIX]))
            ),
            tags: ['payment-methods'],
            parameters: [
                new OA\Parameter(
                    name: 'id',
                    description: 'Unique identifier of the payment method to update',
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer')
                )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Payment method successfully updated',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'message', type: 'string',
                                example: 'Payment method updated successfully'),
                            new OA\Property(property: 'payment_method', ref: new Model(type: PaymentMethod::class,
                                groups: [ObjectModel::READ_PREFIX]))
                        ]
                    )
                ),
                new OA\Response(
                    response: 404,
                    description: 'Payment method not found',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'Payment method not found')
                        ]
                    )
                )
            ]
        )]
        #[Route('/{id}', name: 'edit', methods: ['PUT'])]
        public function edit(int $id, Request $request): JsonResponse
        {
            return $this->updateEntity($request, $id, ObjectModel::UPDATE_PREFIX);
        }

        #[OA\Delete(
            operationId: 'deletePaymentMethod',
            description: "Deletes a specific payment method.",
            summary: 'Delete a payment method',
            security: [['Bearer' => []]],
            tags: ['payment-methods'],
            parameters: [
                new OA\Parameter(
                    name: 'id',
                    description: 'Unique identifier of the payment method to delete',
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer')
                )
            ],
            responses: [
                new OA\Response(
                    response: 204,
                    description: 'Payment method successfully deleted'
                ),
                new OA\Response(
                    response: 404,
                    description: 'Payment method not found',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'Payment method not found')
                        ]
                    )
                )
            ]
        )]
        #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
        public function delete(int $id): JsonResponse
        {
            return $this->deleteEntity($id);
        }
    }
