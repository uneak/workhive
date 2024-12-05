<?php

namespace App\Controller\Api;

use App\Core\Services\Manager\RoomRoleRateManager;
use App\Entity\RoomRoleRate;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * API Controller for managing room role rates
 */
#[Route('api/v1/room-role-rates', name: 'api_room_role_rate_')]
#[OA\Tag(name: 'room-role-rates', description: 'Operations for managing room role rates')]
class RoomRoleRateApiController extends AbstractApiController
{
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        RoomRoleRateManager $manager,
    ) {
        parent::__construct($serializer, $validator, $manager, RoomRoleRate::class);
    }

    #[OA\Get(
        operationId: 'getRoomRoleRates',
        description: "Retrieves a list of all available room role rates with advanced filtering capabilities.",
        summary: 'List all room role rates',
        security: [['Bearer' => []]],
        tags: ['room-role-rates'],
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
                description: "Returns a list of room role rates matching the specified criteria.",
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: RoomRoleRate::class, groups: ['room_role_rate:read']))
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
        return $this->listEntities($request, 'room_role_rate:read');
    }

    #[OA\Post(
        operationId: 'createRoomRoleRate',
        description: "Creates a new room role rate with the provided details.",
        summary: 'Create a new room role rate',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            description: "Details of the room role rate to be created",
            required: true,
            content: new OA\JsonContent(ref: new Model(type: RoomRoleRate::class, groups: ['room_role_rate:write']))
        ),
        tags: ['room-role-rates'],
        responses: [
            new OA\Response(
                response: 201,
                description: "Room role rate successfully created.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Room role rate created successfully'),
                        new OA\Property(property: 'room_role_rate', ref: new Model(type: RoomRoleRate::class, groups: ['room_role_rate:read']))
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
                description: "Authentication is required to create a room role rate.\n\n" .
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
        operationId: 'getRoomRoleRate',
        description: "Retrieves detailed information about a specific room role rate identified by its unique ID.",
        summary: 'Get room role rate details',
        security: [['Bearer' => []]],
        tags: ['room-role-rates'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Unique identifier of the room role rate',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the requested room role rate details',
                content: new OA\JsonContent(ref: new Model(type: RoomRoleRate::class, groups: ['room_role_rate:read']))
            ),
            new OA\Response(
                response: 404,
                description: 'Room role rate not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Room role rate not found')
                    ]
                )
            )
        ]
    )]
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->showEntity($id, 'room_role_rate:read');
    }

    #[OA\Put(
        operationId: 'updateRoomRoleRate',
        description: "Updates an existing room role rate with the provided details.",
        summary: 'Update a room role rate',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            description: 'Updated room role rate details',
            required: true,
            content: new OA\JsonContent(ref: new Model(type: RoomRoleRate::class, groups: ['room_role_rate:write']))
        ),
        tags: ['room-role-rates'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Unique identifier of the room role rate to update',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Room role rate successfully updated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Room role rate updated successfully'),
                        new OA\Property(property: 'room_role_rate', ref: new Model(type: RoomRoleRate::class, groups: ['room_role_rate:read']))
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Room role rate not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Room role rate not found')
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
        operationId: 'deleteRoomRoleRate',
        description: "Deletes a specific room role rate.",
        summary: 'Delete a room role rate',
        security: [['Bearer' => []]],
        tags: ['room-role-rates'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Unique identifier of the room role rate to delete',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Room role rate successfully deleted'
            ),
            new OA\Response(
                response: 404,
                description: 'Room role rate not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Room role rate not found')
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
