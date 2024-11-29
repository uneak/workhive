<?php

    namespace App\Controller\Admin;

    use App\Entity\PaymentMethod;
    use App\Entity\User;
    use App\Form\PaymentMethodType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/user/{idUser}/payment', name: 'app_admin_user_payment_')]
    class PaymentMethodController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(EntityManagerInterface $em, int $idUser): Response
        {
            $user = $em->getRepository(User::class)->find($idUser);
            $paymentMethods = $user->getPaymentMethods();

            return $this->render('admin/user/payment/list.html.twig', [
                'user' => $user,
                'paymentMethods' => $paymentMethods
            ]);
        }

        #[Route('/new/{type}', name: 'new', methods: ['GET', 'POST'])]
        public function create(EntityManagerInterface $em, Request $request, int $idUser, string $type): Response
        {
            $user = $em->getRepository(User::class)->find($idUser);
            $action = $this->generateUrl('app_admin_user_payment_new', ['type' => $type, 'idUser' => $idUser]);

            $paymentMethod = new PaymentMethod();
            $paymentMethod->setType($type);
            $paymentMethod->setUser($user);

            return $this->handleForm($em, $request, $action, $paymentMethod);
        }

        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(EntityManagerInterface $em, int $id): Response
        {
            $paymentMethod = $em->getRepository(PaymentMethod::class)->find($id);

            return $this->render('admin/user/payment/show.html.twig', [
                'paymentMethod' => $paymentMethod,
            ]);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(EntityManagerInterface $em, Request $request, int $idUser, int $id): Response
        {
            $paymentMethod = $em->getRepository(PaymentMethod::class)->find($id);
            $action = $this->generateUrl('app_admin_user_payment_edit', ['id' => $id, 'idUser' => $idUser]);

            return $this->handleForm($em, $request, $action, $paymentMethod);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(EntityManagerInterface $em, Request $request, int $idUser, int $id): Response
        {
            $paymentMethod = $em->getRepository(PaymentMethod::class)->find($id);

            if ($this->isCsrfTokenValid('delete' . $paymentMethod->getId(), $request->getPayload()->getString('_token'))) {
                $em->remove($paymentMethod);
                $em->flush();
            }

            return $this->redirectToRoute('app_admin_user_payment_list', ['idUser' => $idUser], Response::HTTP_SEE_OTHER);
        }

        protected function handleForm(
            EntityManagerInterface $em,
            Request $request,
            string $action,
            PaymentMethod $paymentMethod,
            ?string $redirect = null
        ): Response {
            $form = $this->createForm(PaymentMethodType::class, $paymentMethod, ['action' => $action, 'method' => 'POST']);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($paymentMethod);
                $em->flush();

                if ($redirect) {
                    return $this->redirect($redirect);
                } else {
                    return $this->redirectToRoute('app_admin_user_payment_list', ['idUser' => $paymentMethod->getUser()->getId()], Response::HTTP_SEE_OTHER);
                }
            }

            return $this->render('admin/user/payment/form.html.twig', [
                'form' => $form,
                'paymentMethod' => $paymentMethod,
            ]);
        }
    }
