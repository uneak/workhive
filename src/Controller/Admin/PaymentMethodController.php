<?php

    namespace App\Controller\Admin;

    use App\Core\Model\ObjectModel;
    use App\Core\Services\Manager\PaymentMethodManager;
    use App\Core\Services\Manager\UserManager;
    use App\Entity\PaymentMethod;
    use App\Form\PaymentMethodType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/user/{idUser}/payment', name: 'app_admin_payment_method_')]
    class PaymentMethodController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(PaymentMethodManager $manager, UserManager $userManager, int $idUser): Response
        {
            return $this->render('admin/user/payment_method/list.html.twig', [
                'paymentMethods' => $manager->getByUser($idUser),
                'user'          => $userManager->get($idUser),
            ]);
        }

        #[Route('/new/{type}', name: 'new', methods: ['GET', 'POST'])]
        public function create(
            PaymentMethodManager $manager,
            UserManager $userManager,
            Request $request,
            int $idUser,
            string $type
        ): Response {
            $user = $userManager->get($idUser);
            $paymentMethod = new PaymentMethod();
            $paymentMethod->setUser($user);
            $paymentMethod->setType($type);

            /** @var PaymentMethod $data */
            [$isSubmitted, $form, $data] = $this->handleForm(
                $request,
                PaymentMethodType::class,
                $paymentMethod,
                [
                    'action' => $this->generateUrl('app_admin_payment_method_new',
                        ['idUser' => $user->getId(), 'type' => $type]),
                    'method' => 'POST'
                ]
            );

            if ($isSubmitted) {
                $manager->save($data, true);

                return $this->redirectToRoute('app_admin_payment_method_list',
                    ['idUser' => $user->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('admin/user/payment_method/form.html.twig', [
                'form'          => $form,
                'paymentMethod' => $data
            ]);
        }

        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(PaymentMethodManager $manager, int $id): Response
        {
            return $this->render('admin/user/payment_method/show.html.twig', [
                'payment_method' => $manager->get($id),
            ]);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(
            PaymentMethodManager $manager,
            Request $request,
            int $idUser,
            int $id
        ): Response {
            /** @var PaymentMethod $data */
            [$isSubmitted, $form, $data] = $this->handleForm(
                $request,
                PaymentMethodType::class,
                $manager->get($id),
                [
                    'action' => $this->generateUrl('app_admin_payment_method_edit',
                        ['idUser' => $idUser, 'id' => $id]),
                    'method' => 'POST'
                ]
            );

            if ($isSubmitted) {
                $manager->save($data, true);

                return $this->redirectToRoute('app_admin_payment_method_list',
                    ['idUser' => $idUser], Response::HTTP_SEE_OTHER);
            }

            return $this->render('admin/user/payment_method/form.html.twig', [
                'form'          => $form,
                'paymentMethod' => $data
            ]);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(
            PaymentMethodManager $manager,
            Request $request,
            int $idUser,
            int $id
        ): Response {
            if ($this->isCsrfTokenValid('delete' . $id, $request->getPayload()->getString('_token'))) {
                $manager->remove($manager->get($id), true);
            }

            return $this->redirectToRoute('app_admin_payment_method_list',
                ['idUser' => $idUser], Response::HTTP_SEE_OTHER);
        }

        /**
         * @param Request     $request
         * @param string      $type
         * @param ObjectModel $data
         * @param array       $options
         *
         * @return array{0: bool, 1: FormInterface, 2: ObjectModel}
         */
        protected function handleForm(
            Request $request,
            string $type,
            ObjectModel $data,
            array $options,
        ): array {
            $form = $this->createForm($type, $data, $options);
            $form->handleRequest($request);

            return [
                $form->isSubmitted() && $form->isValid(),
                $form,
                $form->getData()
            ];
        }
    }
