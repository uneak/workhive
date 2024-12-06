<?php

namespace App\Controller\Api;

use App\Core\Model\ObjectModel;
use App\Core\Services\Manager\ReservationEquipmentManager;
use App\Entity\ReservationEquipment;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * API Controller for managing reservation equipment
 */
#[Route('api/v1/reservation-equipment', name: 'api_reservation_equipment_')]
#[OA\Tag(name: 'reservation-equipment', description: 'Operations for managing reservation equipment')]
class ReservationEquipmentApiController extends AbstractApiController
{
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        ReservationEquipmentManager $manager,
    ) {
        parent::__construct($serializer, $validator, $manager, ReservationEquipment::class);
    }

    #[OA\Get(
        operationId: 'getReservationEquipments',
        description: "Retrieves a list of all available reservation equipment with advanced filtering capabilities.",
        summary: 'List all reservation equipment',
        security: [['Bearer' => []]],
        tags: ['reservation-equipment'],
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
                "| `reservation` | `reservation:123` | Filter by reservation ID |\n" .
                "| `equipment` | `equipment:projector` | Filter by equipment name |\n" .
                "| `status` | `status:confirmed` | Filter by status |\n\n" .
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
                description: "Returns a list of reservation equipment matching the specified criteria.",
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: ReservationEquipment::class, groups: [ObjectModel::READ_PREFIX]))
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
        operationId: 'createReservationEquipment',
        description: "Creates a new reservation equipment with the provided details.",
        summary: 'Create a new reservation equipment',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            description: "Details of the reservation equipment to be created",
            required: true,
            content: new OA\JsonContent(ref: new Model(type: ReservationEquipment::class, groups: [ObjectModel::CREATE_PREFIX]))
        ),
        tags: ['reservation-equipment'],
        responses: [
            new OA\Response(
                response: 201,
                description: "Reservation equipment successfully created.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Reservation equipment created successfully'),
                        new OA\Property(property: 'reservation_equipment', ref: new Model(type: ReservationEquipment::class, groups: [ObjectModel::READ_PREFIX]))
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
                description: "Authentication is required to create reservation equipment.\n\n" .
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
        operationId: 'getReservationEquipmentItem',
        description: "Retrieves detailed information about a specific reservation equipment identified by its unique ID.",
        summary: 'Get reservation equipment details',
        security: [['Bearer' => []]],
        tags: ['reservation-equipment'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Unique identifier of the reservation equipment',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the requested reservation equipment details',
                content: new OA\JsonContent(ref: new Model(type: ReservationEquipment::class, groups: [ObjectModel::READ_PREFIX]))
            ),
            new OA\Response(
                response: 404,
                description: 'Reservation equipment not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Reservation equipment not found')
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
        operationId: 'updateReservationEquipment',
        description: "Updates an existing reservation equipment with the provided details.",
        summary: 'Update a reservation equipment',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            description: 'Updated reservation equipment details',
            required: true,
            content: new OA\JsonContent(ref: new Model(type: ReservationEquipment::class, groups: [ObjectModel::UPDATE_PREFIX]))
        ),
        tags: ['reservation-equipment'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Unique identifier of the reservation equipment to update',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Reservation equipment successfully updated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Reservation equipment updated successfully'),
                        new OA\Property(property: 'reservation_equipment', ref: new Model(type: ReservationEquipment::class, groups: [ObjectModel::READ_PREFIX]))
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Reservation equipment not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Reservation equipment not found')
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
        operationId: 'deleteReservationEquipment',
        description: "Deletes a specific reservation equipment.",
        summary: 'Delete a reservation equipment',
        security: [['Bearer' => []]],
        tags: ['reservation-equipment'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Unique identifier of the reservation equipment to delete',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Reservation equipment successfully deleted'
            ),
            new OA\Response(
                response: 404,
                description: 'Reservation equipment not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Reservation equipment not found')
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
