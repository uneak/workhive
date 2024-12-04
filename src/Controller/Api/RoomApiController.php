<?php

    namespace App\Controller\Api;

    use App\Core\Enum\Status;
    use App\Core\Services\Manager\RoomManager;
    use App\Entity\Room;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\PropertyAccess\PropertyAccess;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Serializer\SerializerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    #[Route('api/v1/rooms', name: 'api_room_')]
    class RoomApiController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(RoomManager $manager, SerializerInterface $serializer): JsonResponse
        {
            $rooms = $manager->all();
            $data = $serializer->serialize($rooms, 'json', ['groups' => 'room:read']);

            return new JsonResponse($data, 200, [], true);
        }

        #[Route('/', name: 'new', methods: ['POST'])]
        public function create(RoomManager $manager, ValidatorInterface $validator, Request $request): JsonResponse
        {
            $propertyAccessor = PropertyAccess::createPropertyAccessor();

            $data = json_decode($request->getContent(), true);

            if (!$data) {
                return new JsonResponse(['error' => 'Invalid JSON'], 400);
            }

            $data['status'] = isset($data['status']) ? Status::from($data['status']) : Status::INACTIVE;

            $room = new Room();

            try {
                // Hydrate dynamiquement l'entité room
                foreach ($data as $key => $value) {
                    if ($propertyAccessor->isWritable($room, $key)) {
                        $propertyAccessor->setValue($room, $key, $value);
                    }
                }

                // Validation
                $errors = $validator->validate($room);

                if (count($errors) > 0) {
                    $errorMessages = [];
                    foreach ($errors as $error) {
                        $errorMessages[] = [
                            'message' => $error->getMessage(),
                            'path'    => $error->getPropertyPath(),
                            'cause'    => $error->getCause(),
                        ];
                    }

                    return new JsonResponse(
                        [
                            'error'   => 'Validation failed',
                            'details' => $errorMessages,
                        ],
                        400);
                }


                // Persist en base de données
                $manager->save($room, true);

                return new JsonResponse(['message' => 'Room created successfully'], 201);

            } catch (\Exception $e) {
                return new JsonResponse(['error' => 'Unable to create room', 'details' => $e->getMessage()], 400);
            }
        }

        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(RoomManager $manager, SerializerInterface $serializer, int $id): JsonResponse
        {
            $room = $manager->get($id);
            $data = $serializer->serialize($room, 'json', ['groups' => 'room:read']);

            return new JsonResponse($data, 200, [], true);
        }

        #[Route('/{id}', name: 'edit', methods: ['PUT'])]
        public function edit(RoomManager $manager, ValidatorInterface $validator, Request $request, int $id): JsonResponse
        {
            $propertyAccessor = PropertyAccess::createPropertyAccessor();

            $data = json_decode($request->getContent(), true);

            if (!$data) {
                return new JsonResponse(['error' => 'Invalid JSON'], 400);
            }

            $room = $manager->get($id);

            if (!$room) {
                return new JsonResponse(['error' => 'Room not found'], 404);
            }

            try {
                // Hydrate dynamiquement l'entité room
                foreach ($data as $key => $value) {
                    if ($propertyAccessor->isWritable($room, $key)) {
                        $propertyAccessor->setValue($room, $key, $value);
                    }
                }

                // Validation
                $errors = $validator->validate($room);

                if (count($errors) > 0) {
                    $errorMessages = [];
                    foreach ($errors as $error) {
                        $errorMessages[] = [
                            'message' => $error->getMessage(),
                            'path'    => $error->getPropertyPath(),
                            'cause'    => $error->getCause(),
                        ];
                    }

                    return new JsonResponse(
                        [
                            'error'   => 'Validation failed',
                            'details' => $errorMessages,
                        ],
                        400);
                }

                // Persist en base de données
                $manager->save($room, true);

                return new JsonResponse(['message' => 'Room updated successfully'], 200);

            } catch (\Exception $e) {
                return new JsonResponse(['error' => 'Unable to update room', 'details' => $e->getMessage()], 400);
            }

        }

        #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
        public function delete(RoomManager $manager, Request $request, int $id): JsonResponse
        {
            $room = $manager->get($id);

            if (!$room) {
                return new JsonResponse(['error' => 'Room not found'], 404);
            }

            $manager->remove($room, true);

            return new JsonResponse(['message' => 'Room deleted successfully'], 200);
        }

    }
