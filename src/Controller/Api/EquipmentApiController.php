<?php

namespace App\Controller\Api;

use App\Core\Services\Manager\EquipmentManager;
use App\Entity\Equipment;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * API Controller for managing equipment
 */
#[Route('api/v1/equipments', name: 'api_equipment_')]
#[OA\Tag(name: 'equipments', description: 'Operations for managing equipment')]
class EquipmentApiController extends AbstractApiController
{
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EquipmentManager $manager,
    ) {
        parent::__construct($serializer, $validator, $manager, Equipment::class);
    }

    #[OA\Get(
        operationId: 'getEquipments',
        description: "Retrieves a list of all available equipment with advanced filtering capabilities.",
        summary: 'List all equipment',
        security: [['Bearer' => []]],
        tags: ['equipments'],
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
                "| `type` | `type:projector` | Filter by equipment type |\n" .
                "| `status` | `status:available` | Filter by status |\n" .
                "| `brand` | `brand:sony` | Filter by brand |\n\n" .
                "### List Filters\n" .
                "Use `[]` suffix for array values:\n" .
                "```\n" .
                "types[]:projector,screen\n" .
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
                        example: 'status:available'
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
                description: "Returns a list of equipment matching the specified criteria.",
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: Equipment::class, groups: ['equipment:read']))
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
        return $this->listEntities($request, 'equipment:read');
    }

    #[OA\Post(
        operationId: 'createEquipment',
        description: "Creates a new equipment with the provided details.",
        summary: 'Create a new equipment',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            description: "Details of the equipment to be created",
            required: true,
            content: new OA\JsonContent(ref: new Model(type: Equipment::class, groups: ['equipment:write']))
        ),
        tags: ['equipments'],
        responses: [
            new OA\Response(
                response: 201,
                description: "Equipment successfully created.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Equipment created successfully'),
                        new OA\Property(property: 'equipment', ref: new Model(type: Equipment::class, groups: ['equipment:read']))
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
                description: "Authentication is required to create equipment.\n\n" .
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
        operationId: 'getEquipment',
        description: "Retrieves detailed information about a specific equipment identified by its unique ID.",
        summary: 'Get equipment details',
        security: [['Bearer' => []]],
        tags: ['equipments'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Unique identifier of the equipment',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the requested equipment details',
                content: new OA\JsonContent(ref: new Model(type: Equipment::class, groups: ['equipment:read']))
            ),
            new OA\Response(
                response: 404,
                description: 'Equipment not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Equipment not found')
                    ]
                )
            )
        ]
    )]
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->showEntity($id, 'equipment:read');
    }

    #[OA\Put(
        operationId: 'updateEquipment',
        description: "Updates an existing equipment with the provided details.",
        summary: 'Update an equipment',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            description: 'Updated equipment details',
            required: true,
            content: new OA\JsonContent(ref: new Model(type: Equipment::class, groups: ['equipment:write']))
        ),
        tags: ['equipments'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Unique identifier of the equipment to update',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Equipment successfully updated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Equipment updated successfully'),
                        new OA\Property(property: 'equipment', ref: new Model(type: Equipment::class, groups: ['equipment:read']))
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Equipment not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Equipment not found')
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
        operationId: 'deleteEquipment',
        description: "Deletes a specific equipment.",
        summary: 'Delete an equipment',
        security: [['Bearer' => []]],
        tags: ['equipments'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Unique identifier of the equipment to delete',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Equipment successfully deleted'
            ),
            new OA\Response(
                response: 404,
                description: 'Equipment not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Equipment not found')
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
