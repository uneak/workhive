<?php

    namespace App\Controller\Api;

    use App\Core\Model\ObjectModel;
    use App\Core\Services\Manager\ReservationManager;
    use App\Entity\PaymentMethod;
    use App\Entity\Reservation;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use OpenApi\Attributes as OA;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Serializer\SerializerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * API Controller for managing reservations
     */
    #[Route('api/v1/reservations', name: 'api_reservation_')]
    #[OA\Tag(name: 'reservations', description: 'Operations for managing reservations')]
    class ReservationApiController extends AbstractApiController
    {
        public function __construct(
            SerializerInterface $serializer,
            ValidatorInterface $validator,
            ReservationManager $manager,
        ) {
            parent::__construct($serializer, $validator, $manager, Reservation::class);
        }

        #[OA\Get(
            operationId: 'getReservations',
            description: "Retrieves a list of all available reservations with advanced filtering capabilities.",
            summary: 'List all reservations',
            security: [['Bearer' => []]],
            tags: ['reservations'],
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
                    "| `room` | `room:conference_a` | Filter by room name |\n" .
                    "| `user` | `user:john.doe` | Filter by user name |\n" .
                    "| `status` | `status:confirmed` | Filter by reservation status |\n\n" .
                    "### List Filters\n" .
                    "Use `[]` suffix for array values:\n" .
                    "```\n" .
                    "rooms[]:conference_a,meeting_b\n" .
                    "```\n\n" .
                    "### Range Filters\n" .
                    "| Operator | Example | Description |\n" .
                    "|----------|---------|-------------|\n" .
                    "| `>` | `start_date:>2024-01-01` | After date |\n" .
                    "| `<` | `end_date:<2024-12-31` | Before date |\n" .
                    "| `>=` | `duration:>=2` | Greater than or equal |\n" .
                    "| `<=` | `duration:<=8` | Less than or equal |",
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(
                        type: 'array',
                        items: new OA\Items(
                            type: 'string',
                            example: 'status:confirmed'
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
                    description: "Returns a list of reservations matching the specified criteria.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'data', type: 'array',
                                items: new OA\Items(ref: new Model(type: Reservation::class,
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
            operationId: 'createReservation',
            description: "Creates a new reservation with the provided details.",
            summary: 'Create a new reservation',
            security: [['Bearer' => []]],
            requestBody: new OA\RequestBody(
                description: "Details of the reservation to be created",
                required: true,
                content: new OA\JsonContent(ref: new Model(type: Reservation::class,
                    groups: [ObjectModel::CREATE_PREFIX]))
            ),
            tags: ['reservations'],
            responses: [
                new OA\Response(
                    response: 201,
                    description: "Reservation successfully created.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'message', type: 'string',
                                example: 'Reservation created successfully'),
                            new OA\Property(property: 'reservation', ref: new Model(type: Reservation::class,
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
                    description: "Authentication is required to create a reservation.\n\n" .
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
            operationId: 'getReservation',
            description: "Retrieves detailed information about a specific reservation identified by its unique ID.",
            summary: 'Get reservation details',
            security: [['Bearer' => []]],
            tags: ['reservations'],
            parameters: [
                new OA\Parameter(
                    name: 'id',
                    description: 'Unique identifier of the reservation',
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer')
                )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Returns the requested reservation details',
                    content: new OA\JsonContent(ref: new Model(type: Reservation::class,
                        groups: [ObjectModel::READ_PREFIX]))
                ),
                new OA\Response(
                    response: 404,
                    description: 'Reservation not found',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'Reservation not found')
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
            operationId: 'updateReservation',
            description: "Updates an existing reservation with the provided details.",
            summary: 'Update a reservation',
            security: [['Bearer' => []]],
            requestBody: new OA\RequestBody(
                description: 'Updated reservation details',
                required: true,
                content: new OA\JsonContent(ref: new Model(type: Reservation::class,
                    groups: [ObjectModel::UPDATE_PREFIX]))
            ),
            tags: ['reservations'],
            parameters: [
                new OA\Parameter(
                    name: 'id',
                    description: 'Unique identifier of the reservation to update',
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer')
                )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Reservation successfully updated',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'message', type: 'string',
                                example: 'Reservation updated successfully'),
                            new OA\Property(property: 'reservation', ref: new Model(type: Reservation::class,
                                groups: [ObjectModel::READ_PREFIX]))
                        ]
                    )
                ),
                new OA\Response(
                    response: 404,
                    description: 'Reservation not found',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'Reservation not found')
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
            operationId: 'deleteReservation',
            description: "Deletes a specific reservation.",
            summary: 'Delete a reservation',
            security: [['Bearer' => []]],
            tags: ['reservations'],
            parameters: [
                new OA\Parameter(
                    name: 'id',
                    description: 'Unique identifier of the reservation to delete',
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer')
                )
            ],
            responses: [
                new OA\Response(
                    response: 204,
                    description: 'Reservation successfully deleted'
                ),
                new OA\Response(
                    response: 404,
                    description: 'Reservation not found',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'Reservation not found')
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
