<?php

namespace App\Controller\Api;

use App\Core\Services\Manager\DateSchedulesManager;
use App\Entity\DateSchedules;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * API Controller for managing date schedules
 */
#[Route('api/v1/date-schedules', name: 'api_date_schedules_')]
#[OA\Tag(name: 'date-schedules', description: 'Operations for managing date schedules')]
class DateSchedulesApiController extends AbstractApiController
{
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        DateSchedulesManager $manager,
    ) {
        parent::__construct($serializer, $validator, $manager, DateSchedules::class);
    }

    #[OA\Get(
        operationId: 'getDateSchedules',
        description: "Retrieves a list of all available date schedules with advanced filtering capabilities.",
        summary: 'List all date schedules',
        security: [['Bearer' => []]],
        tags: ['date-schedules'],
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
                "| `date` | `date:2024-01-01` | Filter by specific date |\n" .
                "| `status` | `status:active` | Filter by status |\n\n" .
                "### Range Filters\n" .
                "| Operator | Example | Description |\n" .
                "|----------|---------|-------------|\n" .
                "| `>` | `date:>2024-01-01` | After date |\n" .
                "| `<` | `date:<2024-12-31` | Before date |\n" .
                "| `>=` | `start_time:>=09:00` | After or at time |\n" .
                "| `<=` | `end_time:<=17:00` | Before or at time |",
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'array',
                    items: new OA\Items(
                        type: 'string',
                        example: 'date:2024-01-01'
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
                description: "Returns a list of date schedules matching the specified criteria.",
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: DateSchedules::class, groups: ['date_schedules:read']))
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
        return $this->listEntities($request, 'date_schedules:read');
    }

    #[OA\Post(
        operationId: 'createDateSchedule',
        description: "Creates a new date schedule with the provided details.",
        summary: 'Create a new date schedule',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            description: "Details of the date schedule to be created",
            required: true,
            content: new OA\JsonContent(ref: new Model(type: DateSchedules::class, groups: ['date_schedules:write']))
        ),
        tags: ['date-schedules'],
        responses: [
            new OA\Response(
                response: 201,
                description: "Date schedule successfully created.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Date schedule created successfully'),
                        new OA\Property(property: 'date_schedule', ref: new Model(type: DateSchedules::class, groups: ['date_schedules:read']))
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
                description: "Authentication is required to create a date schedule.\n\n" .
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
        operationId: 'getDateSchedule',
        description: "Retrieves detailed information about a specific date schedule identified by its unique ID.",
        summary: 'Get date schedule details',
        security: [['Bearer' => []]],
        tags: ['date-schedules'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Unique identifier of the date schedule',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the requested date schedule details',
                content: new OA\JsonContent(ref: new Model(type: DateSchedules::class, groups: ['date_schedules:read']))
            ),
            new OA\Response(
                response: 404,
                description: 'Date schedule not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Date schedule not found')
                    ]
                )
            )
        ]
    )]
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->showEntity($id, 'date_schedules:read');
    }

    #[OA\Put(
        operationId: 'updateDateSchedule',
        description: "Updates an existing date schedule with the provided details.",
        summary: 'Update a date schedule',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            description: 'Updated date schedule details',
            required: true,
            content: new OA\JsonContent(ref: new Model(type: DateSchedules::class, groups: ['date_schedules:write']))
        ),
        tags: ['date-schedules'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Unique identifier of the date schedule to update',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Date schedule successfully updated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Date schedule updated successfully'),
                        new OA\Property(property: 'date_schedule', ref: new Model(type: DateSchedules::class, groups: ['date_schedules:read']))
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Date schedule not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Date schedule not found')
                    ]
                )
            )
        ]
    )]
    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(Request $request, int $id): JsonResponse
    {
        return $this->updateEntity($request, $id);
    }

    #[OA\Delete(
        operationId: 'deleteDateSchedule',
        description: "Deletes a specific date schedule.",
        summary: 'Delete a date schedule',
        security: [['Bearer' => []]],
        tags: ['date-schedules'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Unique identifier of the date schedule to delete',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Date schedule successfully deleted'
            ),
            new OA\Response(
                response: 404,
                description: 'Date schedule not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Date schedule not found')
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
