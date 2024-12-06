<?php

    namespace App\Controller\Api;

    use App\Core\Model\ObjectModel;
    use App\Core\Services\Manager\WeekSchedulesManager;
    use App\Entity\User;
    use App\Entity\WeekSchedules;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use OpenApi\Attributes as OA;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Serializer\SerializerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * API Controller for managing week schedules
     */
    #[Route('api/v1/week-schedules', name: 'api_week_schedules_')]
    #[OA\Tag(name: 'week-schedules', description: 'Operations for managing week schedules')]
    class WeekSchedulesApiController extends AbstractApiController
    {
        public function __construct(
            SerializerInterface $serializer,
            ValidatorInterface $validator,
            WeekSchedulesManager $manager,
        ) {
            parent::__construct($serializer, $validator, $manager, WeekSchedules::class);
        }

        #[OA\Get(
            operationId: 'getWeekSchedules',
            description: "Retrieves a list of all available week schedules with advanced filtering capabilities.",
            summary: 'List all week schedules',
            security: [['Bearer' => []]],
            tags: ['week-schedules'],
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
                    description: "Returns a list of week schedules matching the specified criteria.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'data', type: 'array',
                                items: new OA\Items(ref: new Model(type: WeekSchedules::class,
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
            operationId: 'createWeekSchedule',
            description: "Creates a new week schedule with the provided details.",
            summary: 'Create a new week schedule',
            security: [['Bearer' => []]],
            requestBody: new OA\RequestBody(
                description: "Details of the week schedule to be created",
                required: true,
                content: new OA\JsonContent(ref: new Model(type: WeekSchedules::class,
                    groups: [ObjectModel::CREATE_PREFIX]))
            ),
            tags: ['week-schedules'],
            responses: [
                new OA\Response(
                    response: 201,
                    description: "Week schedule successfully created.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'message', type: 'string',
                                example: 'Week schedule created successfully'),
                            new OA\Property(property: 'week_schedule', ref: new Model(type: WeekSchedules::class,
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
                    description: "Authentication is required to create a week schedule.\n\n" .
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
            operationId: 'getWeekSchedule',
            description: "Retrieves detailed information about a specific week schedule identified by its unique ID.",
            summary: 'Get week schedule details',
            security: [['Bearer' => []]],
            tags: ['week-schedules'],
            parameters: [
                new OA\Parameter(
                    name: 'id',
                    description: 'Unique identifier of the week schedule',
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer')
                )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Returns the requested week schedule details',
                    content: new OA\JsonContent(ref: new Model(type: WeekSchedules::class,
                        groups: [ObjectModel::READ_PREFIX]))
                ),
                new OA\Response(
                    response: 404,
                    description: 'Week schedule not found',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'Week schedule not found')
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
            operationId: 'updateWeekSchedule',
            description: "Updates an existing week schedule with the provided details.",
            summary: 'Update a week schedule',
            security: [['Bearer' => []]],
            requestBody: new OA\RequestBody(
                description: 'Updated week schedule details',
                required: true,
                content: new OA\JsonContent(ref: new Model(type: WeekSchedules::class,
                    groups: [ObjectModel::UPDATE_PREFIX]))
            ),
            tags: ['week-schedules'],
            parameters: [
                new OA\Parameter(
                    name: 'id',
                    description: 'Unique identifier of the week schedule to update',
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer')
                )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Week schedule successfully updated',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'message', type: 'string',
                                example: 'Week schedule updated successfully'),
                            new OA\Property(property: 'week_schedule', ref: new Model(type: WeekSchedules::class,
                                groups: [ObjectModel::READ_PREFIX]))
                        ]
                    )
                ),
                new OA\Response(
                    response: 404,
                    description: 'Week schedule not found',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'Week schedule not found')
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
            operationId: 'deleteWeekSchedule',
            description: "Deletes a specific week schedule.",
            summary: 'Delete a week schedule',
            security: [['Bearer' => []]],
            tags: ['week-schedules'],
            parameters: [
                new OA\Parameter(
                    name: 'id',
                    description: 'Unique identifier of the week schedule to delete',
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer')
                )
            ],
            responses: [
                new OA\Response(
                    response: 204,
                    description: 'Week schedule successfully deleted'
                ),
                new OA\Response(
                    response: 404,
                    description: 'Week schedule not found',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'Week schedule not found')
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
