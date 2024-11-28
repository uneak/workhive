<?php

    namespace App\Controller;

    use App\Entity\User;
    use App\Form\UserType;
    use App\Repository\UserRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/user', name: 'app_user_')]
    class UserController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(UserRepository $repository): Response
        {
            return $this->render('user/list.html.twig', [
                'users' => $repository->findAll()
            ]);
        }

        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function create(EntityManagerInterface $em, Request $request): Response
        {
            $user = new User();
            $action = $this->generateUrl('app_user_new');

            return $this->handleForm($em, $request, $action, $user);
        }

        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(UserRepository $repository, int $id): Response
        {
            return $this->render('user/show.html.twig', [
                'user' => $repository->find($id),
            ]);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(EntityManagerInterface $em, Request $request, int $id): Response
        {
            $user = $em->getRepository(User::class)->find($id);
            $action = $this->generateUrl('app_user_edit', ['id' => $id]);

            return $this->handleForm($em, $request, $action, $user);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(EntityManagerInterface $em, Request $request, int $id): Response
        {
            $user = $em->getRepository(User::class)->find($id);

            if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->getPayload()->getString('_token'))) {
                $em->remove($user);
                $em->flush();
            }

            return $this->redirectToRoute('app_user_list', [], Response::HTTP_SEE_OTHER);
        }

        protected function handleForm(
            EntityManagerInterface $em,
            Request $request,
            string $action,
            User $user,
            ?string $redirect = null
        ): Response {
            $form = $this->createForm(UserType::class, $user, ['action' => $action, 'method' => 'POST']);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($user);
                $em->flush();

                if ($redirect) {
                    return $this->redirect($redirect);
                } else {
                    return $this->redirectToRoute('app_user_list');
                }
            }

            return $this->render('user/form.html.twig', [
                'form' => $form,
                'user' => $user,
            ]);
        }
    }
