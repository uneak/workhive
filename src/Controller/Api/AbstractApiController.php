<?php

    namespace App\Controller\Api;

    use App\Core\Services\Manager\CrudManagerInterface;
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
     *
     * @template T of \App\Core\Model\ObjectModel
     */
    abstract class AbstractApiController extends AbstractController
    {
        /**
         * The class name of the entity this controller manages
         *
         * @var class-string<T>
         */
        protected string $entityClass;

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
            string $entityClass,
        ) {
            $this->entityClass = $entityClass;
        }

        /**
         * List all entities
         *
         * @return JsonResponse List of entities serialized with the ':read' group
         */
        protected function listAction(): JsonResponse
        {
            $entities = $this->manager->all();
            $data = $this->serializer->serialize($entities, 'json', ['groups' => $this->entityClass::GROUP_PREFIX . ':read']);

            return new JsonResponse($data, 200, [], true);
        }

        /**
         * Create a new entity
         *
         * @param Request       $request            The HTTP request containing entity data
         * @param callable|null $preProcessCallback Optional callback to modify data before hydration
         *
         * @return JsonResponse The created entity or validation errors
         */
        protected function createAction(Request $request, ?callable $preProcessCallback = null): JsonResponse
        {
            $data = json_decode($request->getContent(), true);

            if (!$data) {
                return new JsonResponse(['error' => 'Invalid JSON'], 400);
            }

            $entity = new $this->entityClass();

            try {
                // Allow pre-processing of data if needed
                if ($preProcessCallback) {
                    $data = $preProcessCallback($data, $entity);
                }

                $this->hydrateEntity($entity, $data);

                // Validation
                $errors = $this->validator->validate($entity);

                if (count($errors) > 0) {
                    return $this->getValidationErrorResponse($errors);
                }

                // Persist to database
                $this->manager->save($entity, true);

                return new JsonResponse(['message' => $this->getEntityName() . ' created successfully'], 201);

            } catch (\Exception $e) {
                return new JsonResponse(
                    [
                        'error'   => 'Unable to create ' . strtolower($this->getEntityName()),
                        'details' => $e->getMessage()
                    ],
                    400
                );
            }
        }

        /**
         * Show a specific entity by ID
         *
         * @param int $id The entity ID
         *
         * @return JsonResponse The entity data or 404 if not found
         */
        protected function showAction(int $id): JsonResponse
        {
            $entity = $this->manager->get($id);

            if (!$entity) {
                return new JsonResponse(
                    ['error' => $this->getEntityName() . ' not found'],
                    404
                );
            }

            $data = $this->serializer->serialize($entity, 'json', ['groups' => $this->entityClass::GROUP_PREFIX . ':read']);

            return new JsonResponse($data, 200, [], true);
        }

        /**
         * Edit an existing entity
         *
         * @param Request       $request            The HTTP request containing updated data
         * @param int           $id                 The entity ID to update
         * @param callable|null $preProcessCallback Optional callback to modify data before hydration
         *
         * @return JsonResponse The updated entity or validation errors
         */
        protected function editAction(Request $request, int $id, ?callable $preProcessCallback = null): JsonResponse
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

            try {
                // Allow pre-processing of data if needed
                if ($preProcessCallback) {
                    $data = $preProcessCallback($data, $entity);
                }

                $this->hydrateEntity($entity, $data);

                // Validation
                $errors = $this->validator->validate($entity);

                if (count($errors) > 0) {
                    return $this->getValidationErrorResponse($errors);
                }

                // Persist to database
                $this->manager->save($entity, true);

                return new JsonResponse(['message' => $this->getEntityName() . ' updated successfully'], 200);

            } catch (\Exception $e) {
                return new JsonResponse(
                    [
                        'error'   => 'Unable to update ' . strtolower($this->getEntityName()),
                        'details' => $e->getMessage()
                    ],
                    400
                );
            }
        }

        /**
         * Delete an entity
         *
         * @param int $id The entity ID to delete
         *
         * @return JsonResponse Success message or error if deletion fails
         */
        protected function deleteAction(int $id): JsonResponse
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
                        'error'   => 'Unable to delete ' . strtolower($this->getEntityName()),
                        'details' => $e->getMessage()
                    ],
                    400
                );
            }
        }

        /**
         * Get the entity name from the class name
         *
         * @return string The short name of the entity class (e.g., "User" from "App\Entity\User")
         */
        protected function getEntityName(): string
        {
            $parts = explode('\\', $this->entityClass);

            return end($parts);
        }

        /**
         * Format validation errors into a structured response
         *
         * @param \Symfony\Component\Validator\ConstraintViolationListInterface $errors List of validation errors
         *
         * @return JsonResponse Formatted error response with details for each violation
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
         *
         * This method uses PropertyAccess to set values on the entity and automatically
         * converts string values to their corresponding Enum instances when the property
         * type is an Enum. It supports both BackedEnum and UnitEnum types.
         *
         * @param object              $entity The entity to hydrate
         * @param array<string,mixed> $data   The data to set on the entity
         */
        protected function hydrateEntity(object $entity, array $data): void
        {
            $propertyAccessor = PropertyAccess::createPropertyAccessor();
            $entityReflection = new \ReflectionClass($entity);

            foreach ($data as $key => $value) {
                if (!$propertyAccessor->isWritable($entity, $key)) {
                    continue;
                }

                // Get property type for Enum conversion
                try {
                    $property = $entityReflection->getProperty($key);
                    $type = $property->getType();

                    if ($type instanceof \ReflectionNamedType) {
                        $typeName = $type->getName();
                        if (enum_exists($typeName)) {
                            if (is_string($value)) {
                                // Handle BackedEnum
                                if (is_subclass_of($typeName, \BackedEnum::class)) {
                                    $value = $typeName::from($value);
                                } // Handle UnitEnum
                                else {
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
