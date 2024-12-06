<?php

    namespace App\Controller\Api;

    use App\Core\Enum\Status;
    use App\Core\Model\ObjectModel;
    use App\Core\Services\Manager\UserManager;
    use App\Entity\User;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use OpenApi\Attributes as OA;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Serializer\SerializerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * API Controller for managing users
     */
    #[Route('api/v1/users', name: 'api_user_')]
    #[OA\Tag(name: 'users', description: 'Operations for managing users')]
    class UserApiController extends AbstractApiController
    {
        public function __construct(
            SerializerInterface $serializer,
            ValidatorInterface $validator,
            UserManager $manager,
            private readonly UserPasswordHasherInterface $passwordHasher,
        ) {
            parent::__construct($serializer, $validator, $manager, User::class);
        }

        #[OA\Get(
            operationId: 'getUsers',
            description: "Retrieves a list of all users with advanced filtering capabilities.",
            summary: 'List all users',
            security: [['Bearer' => []]],
            tags: ['users'],
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
                    description: "Returns a list of users matching the specified criteria.",
                    content: new OA\JsonContent(
                        type: 'array',
                        items: new OA\Items(ref: new Model(type: User::class, groups: [ObjectModel::READ_PREFIX]))
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
            operationId: 'createUser',
            description: "Creates a new user with the provided details.",
            summary: 'Create a new user',
            security: [['Bearer' => []]],
            requestBody: new OA\RequestBody(
                description: "Details of the user to be created",
                required: true,
                content: new OA\JsonContent(ref: new Model(type: User::class, groups: [ObjectModel::CREATE_PREFIX]))
            ),
            tags: ['users'],
            responses: [
                new OA\Response(
                    response: 201,
                    description: "User successfully created.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'User created successfully'),
                            new OA\Property(property: 'user', ref: new Model(type: User::class, groups: ['user:read']))
                        ]
                    )
                ),
                new OA\Response(
                    response: 400,
                    description: "The request contains invalid data.\n\n" .
                    "#### Common Causes\n" .
                    "- Missing required fields\n" .
                    "- Invalid field values\n" .
                    "- Validation constraints not met\n" .
                    "- Email already in use",
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
                    description: "Authentication is required to create a user.\n\n" .
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
            return $this->createEntity($request, Status::INACTIVE->value, [$this, 'processUserData']);
        }

        #[OA\Get(
            operationId: 'getUser',
            description: "Retrieves detailed information about a specific user identified by their unique ID.",
            summary: 'Get user details',
            security: [['Bearer' => []]],
            tags: ['users'],
            parameters: [
                new OA\Parameter(
                    name: 'id',
                    description: "Numeric ID of the user to retrieve. Must be a positive integer.",
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer', format: 'int64', minimum: 1)
                )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: "Returns the requested user.",
                    content: new OA\JsonContent(ref: new Model(type: User::class, groups: [ObjectModel::READ_PREFIX]))
                ),
                new OA\Response(
                    response: 404,
                    description: "The requested user ID does not exist in the system.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'User not found')
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
            return $this->showEntity($id, ObjectModel::READ_PREFIX);
        }

        #[OA\Put(
            operationId: 'updateUser',
            description: "Updates an existing user with the provided details. Partial updates are supported - only provided fields will be modified.",
            summary: 'Update user details',
            security: [['Bearer' => []]],
            requestBody: new OA\RequestBody(
                description: "Updated user details. Only provided fields will be modified.",
                required: true,
                content: new OA\JsonContent(ref: new Model(type: User::class, groups: [ObjectModel::UPDATE_PREFIX]))
            ),
            tags: ['users'],
            parameters: [
                new OA\Parameter(
                    name: 'id',
                    description: "Numeric ID of the user to update. Must be a positive integer.",
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer', format: 'int64', minimum: 1)
                )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: "User successfully updated.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'User updated successfully')
                        ]
                    )
                ),
                new OA\Response(
                    response: 400,
                    description: "The request contains invalid data.\n\n" .
                    "#### Common Causes\n" .
                    "- Missing required fields\n" .
                    "- Invalid field values\n" .
                    "- Validation constraints not met\n" .
                    "- Email already in use",
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
                    description: "The requested user ID does not exist in the system.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'User not found')
                        ]
                    )
                ),
                new OA\Response(
                    response: 401,
                    description: "Authentication is required to update a user.\n\n" .
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
            return $this->updateEntity($request, $id, [$this, 'processUserData']);
        }

        #[OA\Delete(
            operationId: 'deleteUser',
            description: "Permanently deletes a user from the system. This action cannot be undone.",
            summary: 'Delete a user',
            security: [['Bearer' => []]],
            tags: ['users'],
            parameters: [
                new OA\Parameter(
                    name: 'id',
                    description: "Numeric ID of the user to delete. Must be a positive integer.",
                    in: 'path',
                    required: true,
                    schema: new OA\Schema(type: 'integer', format: 'int64', minimum: 1)
                )
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: "User successfully deleted.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'User deleted successfully')
                        ]
                    )
                ),
                new OA\Response(
                    response: 404,
                    description: "The requested user ID does not exist in the system.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'User not found')
                        ]
                    )
                ),
                new OA\Response(
                    response: 401,
                    description: "Authentication is required to delete a user.\n\n" .
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

        /**
         * Process user data before entity hydration
         *
         * Handles password hashing and any other user-specific data transformations
         */
        public function processUserData(array $data, User $user): array
        {
            if (isset($data['plainPassword']) && $data['plainPassword']) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $data['plainPassword']));
                unset($data['plainPassword']); // Remove password from data to prevent double-setting
            }

            return $data;
        }
    }
