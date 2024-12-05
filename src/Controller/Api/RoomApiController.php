<?php

    namespace App\Controller\Api;

    use App\Core\Enum\Status;
    use App\Core\Services\Manager\RoomManager;
    use App\Entity\Room;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use OpenApi\Attributes as OA;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Serializer\SerializerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * API Controller for managing rooms
     */
    #[Route('api/v1/rooms', name: 'api_room_')]
    #[OA\Tag(name: 'rooms', description: 'Operations for managing meeting rooms and spaces')]
    class RoomApiController extends AbstractApiController
    {
        public function __construct(
            SerializerInterface $serializer,
            ValidatorInterface $validator,
            RoomManager $manager
        ) {
            parent::__construct($serializer, $validator, $manager, Room::class);
        }

        #[OA\Get(
            operationId: 'getRooms',
            description: "Retrieves a list of all available rooms with advanced filtering capabilities.",
            summary: 'List all rooms',
            security: [['Bearer' => []]],
            tags: ['rooms'],
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
                    "| `type` | `type:conference` | Filter by type |\n" .
                    "| `status` | `status:active` | Filter by status |\n\n" .
                    "### List Filters\n" .
                    "Use `[]` suffix for array values:\n" .
                    "```\n" .
                    "tags[]:meeting,important\n" .
                    "```\n\n" .
                    "### Range Filters\n" .
                    "| Operator | Example | Description |\n" .
                    "|----------|---------|-------------|\n" .
                    "| `>` | `capacity:>10` | Greater than |\n" .
                    "| `<` | `capacity:<20` | Less than |\n" .
                    "| `>=` | `capacity:>=15` | Greater than or equal |\n" .
                    "| `<=` | `capacity:<=25` | Less than or equal |",
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
                    description: "Returns a list of rooms matching the specified criteria.",
                    content: new OA\JsonContent(
                        type: 'array',
                        items: new OA\Items(ref: new Model(type: Room::class, groups: ['room:read']))
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
            return $this->listEntities($request, 'room:read');
        }

        #[OA\Post(
            operationId: 'createRoom',
            description: "Creates a new room with the provided details.",
            summary: 'Create a new room',
            security: [['Bearer' => []]],
            requestBody: new OA\RequestBody(
                description: "Details of the room to be created",
                required: true,
                content: new OA\JsonContent(ref: new Model(type: Room::class, groups: ['room:write']))
            ),
            tags: ['rooms'],
            responses: [
                new OA\Response(
                    response: 201,
                    description: "Room successfully created.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'Room created successfully'),
                            new OA\Property(property: 'room', ref: new Model(type: Room::class, groups: ['room:read']))
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
                    description: "Authentication is required to create a room.\n\n" .
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
            return $this->createEntity($request, Status::INACTIVE->value);
        }

        #[OA\Get(
            operationId: 'getRoom',
            description: "Retrieves detailed information about a specific room identified by its unique ID.",
            summary: 'Get room details',
            security: [['Bearer' => []]],
            tags: ['rooms'],
            parameters: [
                new OA\Parameter(
                    name: 'id',
                    description: "Numeric ID of the room to retrieve. Must be a positive integer.",
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer', format: 'int64', minimum: 1)
                )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: "Returns the requested room.",
                    content: new OA\JsonContent(ref: new Model(type: Room::class, groups: ['room:read']))
                ),
                new OA\Response(
                    response: 404,
                    description: "The requested room ID does not exist in the system.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'Room not found')
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
        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(int $id): JsonResponse
        {
            return $this->showEntity($id, 'room:read');
        }

        #[OA\Put(
            operationId: 'updateRoom',
            description: "Updates an existing room with the provided details. Partial updates are supported - only provided fields will be modified.",
            summary: 'Update room details',
            security: [['Bearer' => []]],
            requestBody: new OA\RequestBody(
                description: "Updated room details. Only provided fields will be modified.",
                required: true,
                content: new OA\JsonContent(ref: new Model(type: Room::class, groups: ['room:write']))
            ),
            tags: ['rooms'],
            parameters: [
                new OA\Parameter(
                    name: 'id',
                    description: "Numeric ID of the room to update. Must be a positive integer.",
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer', format: 'int64', minimum: 1)
                )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: "Room successfully updated.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'Room updated successfully')
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
                    response: 404,
                    description: "The requested room ID does not exist in the system.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'Room not found')
                        ]
                    )
                ),
                new OA\Response(
                    response: 401,
                    description: "Authentication is required to update a room.\n\n" .
                    "#### Common Causes\n" .
                    "- Missing authentication token\n" .
                    "- Invalid authentication token\n" .
                    "- Expired authentication token"
                )
            ]
        )]
        #[Route('/{id}', name: 'edit', methods: ['PUT'])]
        public function edit(Request $request, int $id): JsonResponse
        {
            return $this->updateEntity($request, $id);
        }

        #[OA\Delete(
            operationId: 'deleteRoom',
            description: "Permanently deletes a room from the system. This action cannot be undone.",
            summary: 'Delete a room',
            security: [['Bearer' => []]],
            tags: ['rooms'],
            parameters: [
                new OA\Parameter(
                    name: 'id',
                    description: "Numeric ID of the room to delete. Must be a positive integer.",
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer', format: 'int64', minimum: 1)
                )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: "Room successfully deleted.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'Room deleted successfully')
                        ]
                    )
                ),
                new OA\Response(
                    response: 404,
                    description: "The requested room ID does not exist in the system.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'Room not found')
                        ]
                    )
                ),
                new OA\Response(
                    response: 401,
                    description: "Authentication is required to delete a room.\n\n" .
                    "#### Common Causes\n" .
                    "- Missing authentication token\n" .
                    "- Invalid authentication token\n" .
                    "- Expired authentication token"
                )
            ]
        )]
        #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
        public function delete(int $id): JsonResponse
        {
            return $this->deleteEntity($id);
        }
    }
