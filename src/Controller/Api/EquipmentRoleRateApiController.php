<?php

    namespace App\Controller\Api;

    use App\Core\Model\ObjectModel;
    use App\Core\Services\Manager\EquipmentRoleRateManager;
    use App\Entity\EquipmentRoleRate;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use OpenApi\Attributes as OA;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Serializer\SerializerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * API Controller for managing equipment role rates
     */
    #[Route('api/v1/equipment-role-rates', name: 'api_equipment_role_rate_')]
    #[OA\Tag(name: 'equipment-role-rates', description: 'Operations for managing equipment role rates')]
    class EquipmentRoleRateApiController extends AbstractApiController
    {
        public function __construct(
            SerializerInterface $serializer,
            ValidatorInterface $validator,
            EquipmentRoleRateManager $manager,
        ) {
            parent::__construct($serializer, $validator, $manager, EquipmentRoleRate::class);
        }

        #[OA\Get(
            operationId: 'getEquipmentRoleRates',
            description: "Retrieves a list of all available equipment role rates with advanced filtering capabilities.",
            summary: 'List all equipment role rates',
            security: [['Bearer' => []]],
            tags: ['equipment-role-rates'],
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
                    description: "Returns a list of equipment role rates matching the specified criteria.",
                    content: new OA\JsonContent(
                        type: 'array',
                        items: new OA\Items(ref: new Model(type: EquipmentRoleRate::class,
                            groups: [ObjectModel::READ_PREFIX]))
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
            operationId: 'createEquipmentRoleRate',
            description: "Creates a new equipment role rate with the provided details.",
            summary: 'Create a new equipment role rate',
            security: [['Bearer' => []]],
            requestBody: new OA\RequestBody(
                description: "Details of the equipment role rate to be created",
                required: true,
                content: new OA\JsonContent(ref: new Model(type: EquipmentRoleRate::class,
                    groups: [ObjectModel::CREATE_PREFIX]))
            ),
            tags: ['equipment-role-rates'],
            responses: [
                new OA\Response(
                    response: 201,
                    description: "Equipment role rate successfully created.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'message', type: 'string',
                                example: 'Equipment role rate created successfully'),
                            new OA\Property(property: 'equipment_role_rate',
                                ref: new Model(type: EquipmentRoleRate::class, groups: [ObjectModel::READ_PREFIX]))
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
                    description: "Authentication is required to create an equipment role rate.\n\n" .
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
            operationId: 'getEquipmentRoleRate',
            description: "Retrieves detailed information about a specific equipment role rate identified by its unique ID.",
            summary: 'Get equipment role rate details',
            security: [['Bearer' => []]],
            tags: ['equipment-role-rates'],
            parameters: [
                new OA\Parameter(
                    name: 'id',
                    description: 'Unique identifier of the equipment role rate',
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer')
                )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Returns the requested equipment role rate details',
                    content: new OA\JsonContent(ref: new Model(type: EquipmentRoleRate::class,
                        groups: [ObjectModel::READ_PREFIX]))
                ),
                new OA\Response(
                    response: 404,
                    description: 'Equipment role rate not found',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'Equipment role rate not found')
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
            operationId: 'updateEquipmentRoleRate',
            description: "Updates an existing equipment role rate with the provided details.",
            summary: 'Update an equipment role rate',
            security: [['Bearer' => []]],
            requestBody: new OA\RequestBody(
                description: 'Updated equipment role rate details',
                required: true,
                content: new OA\JsonContent(ref: new Model(type: EquipmentRoleRate::class,
                    groups: [ObjectModel::UPDATE_PREFIX]))
            ),
            tags: ['equipment-role-rates'],
            parameters: [
                new OA\Parameter(
                    name: 'id',
                    description: 'Unique identifier of the equipment role rate to update',
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer')
                )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Equipment role rate successfully updated',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'message', type: 'string',
                                example: 'Equipment role rate updated successfully'),
                            new OA\Property(property: 'equipment_role_rate',
                                ref: new Model(type: EquipmentRoleRate::class, groups: [ObjectModel::READ_PREFIX]))
                        ]
                    )
                ),
                new OA\Response(
                    response: 404,
                    description: 'Equipment role rate not found',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'Equipment role rate not found')
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
            operationId: 'deleteEquipmentRoleRate',
            description: "Deletes a specific equipment role rate.",
            summary: 'Delete an equipment role rate',
            security: [['Bearer' => []]],
            tags: ['equipment-role-rates'],
            parameters: [
                new OA\Parameter(
                    name: 'id',
                    description: 'Unique identifier of the equipment role rate to delete',
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer')
                )
            ],
            responses: [
                new OA\Response(
                    response: 204,
                    description: 'Equipment role rate successfully deleted'
                ),
                new OA\Response(
                    response: 404,
                    description: 'Equipment role rate not found',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'Equipment role rate not found')
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
