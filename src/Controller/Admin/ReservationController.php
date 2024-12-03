<?php

    namespace App\Controller\Admin;

    use App\Core\Model\ObjectModel;
    use App\Core\Services\Manager\ReservationManager;
    use App\Entity\Reservation;
    use App\Form\ReservationType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/reservation', name: 'app_admin_reservation_')]
    class ReservationController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(ReservationManager $manager): Response
        {
            return $this->render('admin/reservation/list.html.twig', [
                'reservations' => $manager->all()
            ]);
        }

        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function create(ReservationManager $manager, Request $request): Response
        {
            $reservation = new Reservation();

            /** @var Reservation $data */
            [$isSubmitted, $form, $data] = $this->handleForm(
                $request,
                ReservationType::class,
                $reservation,
                [
                    'action' => $this->generateUrl('app_admin_reservation_new'),
                    'method' => 'POST'
                ]
            );

            if ($isSubmitted) {
                $manager->save($data, true);

                return $this->redirectToRoute('app_admin_reservation_list', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('admin/reservation/form.html.twig', [
                'form'        => $form,
                'reservation' => $data
            ]);
        }

        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(ReservationManager $manager, int $id): Response
        {
            return $this->render('admin/reservation/show.html.twig', [
                'reservation' => $manager->get($id),
            ]);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(ReservationManager $manager, Request $request, int $id): Response
        {
            /** @var Reservation $data */
            [$isSubmitted, $form, $data] = $this->handleForm(
                $request,
                ReservationType::class,
                $manager->get($id),
                [
                    'action' => $this->generateUrl('app_admin_reservation_edit', ['id' => $id]),
                    'method' => 'POST'
                ]
            );

            if ($isSubmitted) {
                $manager->save($data, true);

                return $this->redirectToRoute('app_admin_reservation_list', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('admin/reservation/form.html.twig', [
                'form'        => $form,
                'reservation' => $data
            ]);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(ReservationManager $manager, Request $request, int $id): Response
        {
            if ($this->isCsrfTokenValid('delete' . $id, $request->getPayload()->getString('_token'))) {
                $manager->remove($manager->get($id), true);
            }

            return $this->redirectToRoute('app_admin_reservation_list', [], Response::HTTP_SEE_OTHER);
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
