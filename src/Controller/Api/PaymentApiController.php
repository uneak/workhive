<?php

namespace App\Controller\Api;

use App\Core\Model\ObjectModel;
use App\Core\Services\Manager\PaymentManager;
use App\Entity\Payment;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * API Controller for managing payments
 */
#[Route('api/v1/payments', name: 'api_payment_')]
#[OA\Tag(name: 'payments', description: 'Operations for managing payments')]
class PaymentApiController extends AbstractApiController
{
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        PaymentManager $manager,
    ) {
        parent::__construct($serializer, $validator, $manager, Payment::class);
    }

    #[OA\Get(
        operationId: 'getPayments',
        description: "Retrieves a list of all available payments with advanced filtering capabilities.",
        summary: 'List all payments',
        security: [['Bearer' => []]],
        tags: ['payments'],
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
                "| `status` | `status:pending` | Filter by payment status |\n" .
                "| `method` | `method:credit_card` | Filter by payment method |\n\n" .
                "### List Filters\n" .
                "Use `[]` suffix for array values:\n" .
                "```\n" .
                "methods[]:credit_card,cash\n" .
                "```\n\n" .
                "### Range Filters\n" .
                "| Operator | Example | Description |\n" .
                "|----------|---------|-------------|\n" .
                "| `>` | `amount:>100` | Greater than |\n" .
                "| `<` | `amount:<500` | Less than |\n" .
                "| `>=` | `amount:>=200` | Greater than or equal |\n" .
                "| `<=` | `amount:<=1000` | Less than or equal |",
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'array',
                    items: new OA\Items(
                        type: 'string',
                        example: 'status:pending'
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
                description: "Returns a list of payments matching the specified criteria.",
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: Payment::class, groups: [ObjectModel::READ_PREFIX]))
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
        operationId: 'createPayment',
        description: "Creates a new payment with the provided details.",
        summary: 'Create a new payment',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            description: "Details of the payment to be created",
            required: true,
            content: new OA\JsonContent(ref: new Model(type: Payment::class, groups: [ObjectModel::CREATE_PREFIX]))
        ),
        tags: ['payments'],
        responses: [
            new OA\Response(
                response: 201,
                description: "Payment successfully created.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Payment created successfully'),
                        new OA\Property(property: 'payment', ref: new Model(type: Payment::class, groups: [ObjectModel::READ_PREFIX]))
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
                description: "Authentication is required to create a payment.\n\n" .
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
        operationId: 'getPayment',
        description: "Retrieves detailed information about a specific payment identified by its unique ID.",
        summary: 'Get payment details',
        security: [['Bearer' => []]],
        tags: ['payments'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Unique identifier of the payment',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the requested payment details',
                content: new OA\JsonContent(ref: new Model(type: Payment::class, groups: [ObjectModel::READ_PREFIX]))
            ),
            new OA\Response(
                response: 404,
                description: 'Payment not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Payment not found')
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
        operationId: 'updatePayment',
        description: "Updates an existing payment with the provided details.",
        summary: 'Update a payment',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            description: 'Updated payment details',
            required: true,
            content: new OA\JsonContent(ref: new Model(type: Payment::class, groups: [ObjectModel::UPDATE_PREFIX]))
        ),
        tags: ['payments'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Unique identifier of the payment to update',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Payment successfully updated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Payment updated successfully'),
                        new OA\Property(property: 'payment', ref: new Model(type: Payment::class, groups: [ObjectModel::READ_PREFIX]))
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Payment not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Payment not found')
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
        operationId: 'deletePayment',
        description: "Deletes a specific payment.",
        summary: 'Delete a payment',
        security: [['Bearer' => []]],
        tags: ['payments'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Unique identifier of the payment to delete',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Payment successfully deleted'
            ),
            new OA\Response(
                response: 404,
                description: 'Payment not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Payment not found')
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
