<?php

namespace App\Controller\Api;

use App\Core\Model\ObjectModel;
use App\Core\Services\Manager\RoomEquipmentManager;
use App\Entity\RoomEquipment;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * API Controller for managing room equipment
 */
#[Route('api/v1/room-equipment', name: 'api_room_equipment_')]
#[OA\Tag(name: 'room-equipment', description: 'Operations for managing room equipment')]
class RoomEquipmentApiController extends AbstractApiController
{
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        RoomEquipmentManager $manager,
    ) {
        parent::__construct($serializer, $validator, $manager, RoomEquipment::class);
    }

    #[OA\Get(
        operationId: 'getRoomEquipments',
        description: "Retrieves a list of all available room equipment with advanced filtering capabilities.",
        summary: 'List all room equipment',
        security: [['Bearer' => []]],
        tags: ['room-equipment'],
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
                "| `equipment` | `equipment:projector` | Filter by equipment name |\n" .
                "| `status` | `status:active` | Filter by status |\n\n" .
                "### List Filters\n" .
                "Use `[]` suffix for array values:\n" .
                "```\n" .
                "equipment[]:projector,screen\n" .
                "```\n\n" .
                "### Range Filters\n" .
                "| Operator | Example | Description |\n" .
                "|----------|---------|-------------|\n" .
                "| `>` | `quantity:>2` | Greater than |\n" .
                "| `<` | `quantity:<10` | Less than |\n" .
                "| `>=` | `quantity:>=5` | Greater than or equal |\n" .
                "| `<=` | `quantity:<=15` | Less than or equal |",
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
                description: "Returns a list of room equipment matching the specified criteria.",
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: RoomEquipment::class, groups: [ObjectModel::READ_PREFIX]))
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
        operationId: 'createRoomEquipment',
        description: "Creates a new room equipment with the provided details.",
        summary: 'Create a new room equipment',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            description: "Details of the room equipment to be created",
            required: true,
            content: new OA\JsonContent(ref: new Model(type: RoomEquipment::class, groups: [ObjectModel::CREATE_PREFIX]))
        ),
        tags: ['room-equipment'],
        responses: [
            new OA\Response(
                response: 201,
                description: "Room equipment successfully created.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Room equipment created successfully'),
                        new OA\Property(property: 'room_equipment', ref: new Model(type: RoomEquipment::class, groups: [ObjectModel::READ_PREFIX]))
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
                description: "Authentication is required to create room equipment.\n\n" .
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
        operationId: 'getRoomEquipmentItem',
        description: "Retrieves detailed information about a specific room equipment identified by its unique ID.",
        summary: 'Get room equipment details',
        security: [['Bearer' => []]],
        tags: ['room-equipment'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Unique identifier of the room equipment',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the requested room equipment details',
                content: new OA\JsonContent(ref: new Model(type: RoomEquipment::class, groups: [ObjectModel::READ_PREFIX]))
            ),
            new OA\Response(
                response: 404,
                description: 'Room equipment not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Room equipment not found')
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
        operationId: 'updateRoomEquipment',
        description: "Updates an existing room equipment with the provided details.",
        summary: 'Update a room equipment',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            description: 'Updated room equipment details',
            required: true,
            content: new OA\JsonContent(ref: new Model(type: RoomEquipment::class, groups: [ObjectModel::UPDATE_PREFIX]))
        ),
        tags: ['room-equipment'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Unique identifier of the room equipment to update',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Room equipment successfully updated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Room equipment updated successfully'),
                        new OA\Property(property: 'room_equipment', ref: new Model(type: RoomEquipment::class, groups: [ObjectModel::READ_PREFIX]))
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Room equipment not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Room equipment not found')
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
        operationId: 'deleteRoomEquipment',
        description: "Deletes a specific room equipment.",
        summary: 'Delete a room equipment',
        security: [['Bearer' => []]],
        tags: ['room-equipment'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Unique identifier of the room equipment to delete',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Room equipment successfully deleted'
            ),
            new OA\Response(
                response: 404,
                description: 'Room equipment not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Room equipment not found')
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
