<?php

    namespace App\Controller\Admin;

    use App\Core\Model\ObjectModel;
    use App\Core\Services\Manager\EquipmentManager;
    use App\Core\Services\Manager\UserManager;
    use App\Entity\User;
    use App\Form\UserType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/user', name: 'app_admin_user_')]
    class UserController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(UserManager $manager): Response
        {
            return $this->render('admin/user/list.html.twig', [
                'users' => $manager->all()
            ]);
        }

        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function create(
            UserManager $manager,
            Request $request,
            UserPasswordHasherInterface $passwordHasher
        ): Response {
            $user = new User();

            /** @var User $data */
            [$isSubmitted, $form, $data] = $this->handleForm(
                $request,
                UserType::class,
                $user,
                [
                    'action' => $this->generateUrl('app_admin_user_new'),
                    'method' => 'POST'
                ],
            );

            if ($isSubmitted) {
                $plainPassword = $data->getPassword();
                $hashedPassword = $passwordHasher->hashPassword($data, $plainPassword);
                $data->setPassword($hashedPassword);

                $manager->save($data, true);

                return $this->redirectToRoute('app_admin_user_list', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('admin/user/form.html.twig', [
                'form' => $form,
                'user' => $data
            ]);
        }

        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(UserManager $manager, int $id): Response
        {
            return $this->render('admin/user/show.html.twig', [
                'user' => $manager->get($id),
            ]);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(
            UserManager $manager,
            Request $request,
            UserPasswordHasherInterface $passwordHasher,
            int $id
        ): Response {
            /** @var User $data */
            [$isSubmitted, $form, $data] = $this->handleForm(
                $request,
                UserType::class,
                $manager->get($id),
                [
                    'action' => $this->generateUrl('app_admin_user_edit', ['id' => $id]),
                    'method' => 'POST'
                ],
            );

            if ($isSubmitted) {
                $plainPassword = $data->getPassword();
                $hashedPassword = $passwordHasher->hashPassword($data, $plainPassword);
                $data->setPassword($hashedPassword);

                $manager->save($data, true);

                return $this->redirectToRoute('app_admin_user_list', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('admin/user/form.html.twig', [
                'form' => $form,
                'user' => $data
            ]);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(
            UserManager $manager,
            Request $request,
            int $id
        ): Response {
            if ($this->isCsrfTokenValid('delete' . $id, $request->getPayload()->getString('_token'))) {
                $manager->remove($manager->get($id), true);
            }

            return $this->redirectToRoute('app_admin_user_list', [], Response::HTTP_SEE_OTHER);
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
