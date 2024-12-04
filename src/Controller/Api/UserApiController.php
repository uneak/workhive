<?php

    namespace App\Controller\Api;

    use App\Core\Services\Manager\UserManager;
    use App\Entity\User;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Serializer\SerializerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * Controller for managing Users through API endpoints
     *
     * @template-extends AbstractApiController<User>
     */
    #[Route('api/v1/users', name: 'api_user_')]
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

        /**
         * List all users
         */
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(): JsonResponse
        {
            return $this->listAction();
        }

        /**
         * Create a new user
         */
        #[Route('/', name: 'new', methods: ['POST'])]
        public function create(Request $request): JsonResponse
        {
            return $this->createAction($request, [$this, 'processUserData']);
        }

        /**
         * Show a specific user
         */
        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(int $id): JsonResponse
        {
            return $this->showAction($id);
        }

        /**
         * Update a user
         */
        #[Route('/{id}', name: 'edit', methods: ['PUT'])]
        public function edit(Request $request, int $id): JsonResponse
        {
            return $this->editAction($request, $id, [$this, 'processUserData']);
        }

        /**
         * Delete a user
         */
        #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
        public function delete(int $id): JsonResponse
        {
            return $this->deleteAction($id);
        }

        /**
         * Process user data before entity hydration
         *
         * Handles password hashing and any other user-specific data transformations
         */
        public function processUserData(array $data, User $user): array
        {
            if (isset($data['password']) && $data['password']) {
                $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
                $user->setPassword($hashedPassword);
                unset($data['password']); // Remove password from data to prevent double-setting
            }

            return $data;
        }
    }
