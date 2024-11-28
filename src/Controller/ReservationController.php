<?php

    namespace App\Controller;

    use App\Entity\Reservation;
    use App\Form\ReservationType;
    use App\Repository\ReservationRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/reservation', name: 'app_reservation_')]
    class ReservationController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(ReservationRepository $repository): Response
        {
            return $this->render('reservation/list.html.twig', [
                'reservations' => $repository->findAll()
            ]);
        }

        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function create(EntityManagerInterface $em, Request $request): Response
        {
            $reservation = new Reservation();
            $action = $this->generateUrl('app_reservation_new');

            return $this->handleForm($em, $request, $action, $reservation);
        }

        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(ReservationRepository $repository, int $id): Response
        {
            return $this->render('reservation/show.html.twig', [
                'reservation' => $repository->find($id),
            ]);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(EntityManagerInterface $em, Request $request, int $id): Response
        {
            $reservation = $em->getRepository(Reservation::class)->find($id);
            $action = $this->generateUrl('app_reservation_edit', ['id' => $id]);

            return $this->handleForm($em, $request, $action, $reservation);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(EntityManagerInterface $em, Request $request, int $id): Response
        {
            $reservation = $em->getRepository(Reservation::class)->find($id);

            if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->getPayload()->getString('_token'))) {
                $em->remove($reservation);
                $em->flush();
            }

            return $this->redirectToRoute('app_reservation_list', [], Response::HTTP_SEE_OTHER);
        }

        protected function handleForm(
            EntityManagerInterface $em,
            Request $request,
            string $action,
            Reservation $reservation,
            ?string $redirect = null
        ): Response {
            $form = $this->createForm(ReservationType::class, $reservation, ['action' => $action, 'method' => 'POST']);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($reservation);
                $em->flush();

                if ($redirect) {
                    return $this->redirect($redirect);
                } else {
                    return $this->redirectToRoute('app_reservation_list');
                }
            }

            return $this->render('reservation/form.html.twig', [
                'form' => $form,
                'reservation' => $reservation,
            ]);
        }
    }
