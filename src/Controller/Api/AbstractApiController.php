<?php

    namespace App\Controller\Api;

    use App\Core\Model\ObjectModel;
    use App\Core\Services\Manager\CrudManagerInterface;
    use OpenApi\Attributes as OA;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\PropertyAccess\PropertyAccess;
    use Symfony\Component\Serializer\SerializerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * Abstract API Controller providing CRUD operations for entities
     *
     * This controller provides a standardized implementation of CRUD operations
     * (Create, Read, Update, Delete) for API endpoints. It includes:
     * - Automatic entity hydration with support for Enum types
     * - Validation handling with detailed error responses
     * - Serialization of entities with group support
     * - Pre-processing hooks for custom data manipulation
     * - Advanced filtering, sorting and pagination
     *
     * @template T of ObjectModel
     */
    abstract class AbstractApiController extends AbstractController
    {
        /**
         * Constructor
         *
         * @param SerializerInterface  $serializer  For JSON serialization/deserialization
         * @param ValidatorInterface   $validator   For entity validation
         * @param CrudManagerInterface $manager     For database operations
         * @param class-string<T>      $entityClass The entity class this controller manages
         */
        public function __construct(
            protected readonly SerializerInterface $serializer,
            protected readonly ValidatorInterface $validator,
            protected readonly CrudManagerInterface $manager,
            protected readonly string $entityClass,
        ) {
        }

        /**
         * List all entities with filtering, sorting and pagination
         */
        protected function listEntities(Request $request, string $serializationGroup = 'default'): JsonResponse
        {
            $filters = [];
            if ($request->query->has('filters')) {
                $filtersParam = $request->query->get('filters');
                $filters = is_array($filtersParam) ? $filtersParam : [$filtersParam];
            }

            $order = $request->query->get('order', 'asc');
            $page = (int)$request->query->get('page', 1);
            $limit = (int)$request->query->get('limit', 10);

            $entities = $this->manager->all(
                filters: $filters,
                orderBy: ['name' => strtoupper($order)],
                limit: $limit,
                offset: ($page - 1) * $limit
            );

            $data = $this->serializer->serialize($entities, 'array', ['groups' => $serializationGroup]);

            $data = [
                'data' => $data,
                'meta' => [
                    'total'    => '$this->manager->count(filters: $filters)',
                    'page'     => $page,
                    'per_page' => $limit,
                ],
            ];

            return new JsonResponse($data, 200, [], true);
        }

        /**
         * Create a new entity
         *
         * @param callable|null $dataProcessor Callback to process data before entity hydration
         */
        protected function createEntity(
            Request $request,
            ?string $defaultStatus = null,
            ?callable $dataProcessor = null
        ): JsonResponse {
            $data = json_decode($request->getContent(), true);

            if (!$data) {
                return new JsonResponse(['error' => 'Invalid JSON'], 400);
            }

            if ($defaultStatus && !isset($data['status'])) {
                $data['status'] = $defaultStatus;
            }

            if ($dataProcessor) {
                $data = $dataProcessor($data);
            }

            $entity = new $this->entityClass();

            try {
                $this->hydrateEntity($entity, $data);

                $errors = $this->validator->validate($entity);

                if (count($errors) > 0) {
                    return $this->getValidationErrorResponse($errors);
                }

                $this->manager->save($entity, true);

                return new JsonResponse(['message' => $this->getEntityName() . ' created successfully'], 201);

            } catch (\Exception $e) {
                return new JsonResponse(
                    [
                        'error'   => 'Unable to create ' . $this->getEntityName(),
                        'details' => $e->getMessage()
                    ],
                    400
                );
            }
        }

        /**
         * Show a specific entity
         */
        protected function showEntity(int $id, string $serializationGroup = 'default'): JsonResponse
        {
            $entity = $this->manager->get($id);

            if (!$entity) {
                return new JsonResponse(
                    ['error' => $this->getEntityName() . ' not found'],
                    404
                );
            }

            $data = $this->serializer->serialize($entity, 'json', ['groups' => $serializationGroup]);

            return new JsonResponse($data, 200, [], true);
        }

        /**
         * Update an existing entity
         *
         * @param callable|null $dataProcessor Callback to process data before entity hydration
         */
        protected function updateEntity(Request $request, int $id, ?callable $dataProcessor = null): JsonResponse
        {
            $data = json_decode($request->getContent(), true);

            if (!$data) {
                return new JsonResponse(['error' => 'Invalid JSON'], 400);
            }

            $entity = $this->manager->get($id);

            if (!$entity) {
                return new JsonResponse(
                    ['error' => $this->getEntityName() . ' not found'],
                    404
                );
            }

            if ($dataProcessor) {
                $data = $dataProcessor($data, $entity);
            }

            try {
                $this->hydrateEntity($entity, $data);

                $errors = $this->validator->validate($entity);

                if (count($errors) > 0) {
                    return $this->getValidationErrorResponse($errors);
                }

                $this->manager->save($entity, true);

                return new JsonResponse(['message' => $this->getEntityName() . ' updated successfully'], 200);

            } catch (\Exception $e) {
                return new JsonResponse(
                    [
                        'error'   => 'Unable to update ' . $this->getEntityName(),
                        'details' => $e->getMessage()
                    ],
                    400
                );
            }
        }

        /**
         * Delete an entity
         */
        protected function deleteEntity(int $id): JsonResponse
        {
            $entity = $this->manager->get($id);

            if (!$entity) {
                return new JsonResponse(
                    ['error' => $this->getEntityName() . ' not found'],
                    404
                );
            }

            try {
                $this->manager->remove($entity, true);

                return new JsonResponse(['message' => $this->getEntityName() . ' deleted successfully'], 200);
            } catch (\Exception $e) {
                return new JsonResponse(
                    [
                        'error'   => 'Unable to delete ' . $this->getEntityName(),
                        'details' => $e->getMessage()
                    ],
                    400
                );
            }
        }

        /**
         * Get the entity name from the class name
         */
        protected function getEntityName(): string
        {
            $parts = explode('\\', $this->entityClass);

            return end($parts);
        }

        /**
         * Format validation errors into a structured response
         */
        protected function getValidationErrorResponse($errors): JsonResponse
        {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = [
                    'message' => $error->getMessage(),
                    'path'    => $error->getPropertyPath(),
                    'cause'   => $error->getCause(),
                ];
            }

            return new JsonResponse(
                [
                    'error'   => 'Validation failed',
                    'details' => $errorMessages,
                ],
                400
            );
        }

        /**
         * Hydrate an entity with data, handling Enum conversions
         */
        protected function hydrateEntity(object $entity, array $data): void
        {
            $propertyAccessor = PropertyAccess::createPropertyAccessor();
            $entityReflection = new \ReflectionClass($entity);

            foreach ($data as $key => $value) {
                if (!$propertyAccessor->isWritable($entity, $key)) {
                    continue;
                }

                try {
                    $property = $entityReflection->getProperty($key);
                    $type = $property->getType();

                    if ($type instanceof \ReflectionNamedType) {
                        $typeName = $type->getName();
                        if (enum_exists($typeName)) {
                            if (is_string($value)) {
                                if (is_subclass_of($typeName, \BackedEnum::class)) {
                                    $value = $typeName::from($value);
                                } else {
                                    $cases = $typeName::cases();
                                    foreach ($cases as $case) {
                                        if ($case->name === $value) {
                                            $value = $case;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                } catch (\ReflectionException $e) {
                    // Property doesn't exist or is not accessible, skip Enum conversion
                }

                $propertyAccessor->setValue($entity, $key, $value);
            }
        }
    }
