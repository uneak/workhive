<?php

    namespace App\Controller\Admin;

    use App\Entity\Reservation;
    use App\Entity\ReservationEquipment;
    use App\Form\ReservationEquipmentType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/reservation/{idReservation}/equipment', name: 'app_admin_reservation_equipment_')]
    class ReservationEquipmentController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(EntityManagerInterface $em, int $idReservation): Response
        {
            $reservation = $em->getRepository(Reservation::class)->find($idReservation);
            $reservationEquipments = $em->getRepository(ReservationEquipment::class)->findBy(['reservation' => $reservation]);

            return $this->render('admin/reservation/equipment/list.html.twig', [
                'reservation' => $reservation,
                'reservationEquipments' => $reservationEquipments
            ]);
        }

        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function create(EntityManagerInterface $em, Request $request, int $idReservation): Response
        {
            $reservation = $em->getRepository(Reservation::class)->find($idReservation);
            $action = $this->generateUrl('app_admin_reservation_equipment_new', ['idReservation' => $idReservation]);

            $reservationEquipment = new ReservationEquipment();
            $reservationEquipment->setReservation($reservation);

            return $this->handleForm($em, $request, $action, $reservationEquipment);
        }

        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(EntityManagerInterface $em, int $id): Response
        {
            $reservationEquipment = $em->getRepository(ReservationEquipment::class)->find($id);

            return $this->render('admin/reservation/equipment/show.html.twig', [
                'reservationEquipment' => $reservationEquipment,
            ]);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(EntityManagerInterface $em, Request $request, int $idReservation, int $id): Response
        {
            $reservationEquipment = $em->getRepository(ReservationEquipment::class)->find($id);
            $action = $this->generateUrl('app_admin_reservation_equipment_edit', ['id' => $id, 'idReservation' => $idReservation]);

            return $this->handleForm($em, $request, $action, $reservationEquipment);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(EntityManagerInterface $em, Request $request, int $idReservation, int $id): Response
        {
            $reservationEquipment = $em->getRepository(ReservationEquipment::class)->find($id);

            if ($this->isCsrfTokenValid('delete' . $reservationEquipment->getId(), $request->getPayload()->getString('_token'))) {
                $em->remove($reservationEquipment);
                $em->flush();
            }

            return $this->redirectToRoute('app_admin_reservation_equipment_list', ['idReservation' => $idReservation], Response::HTTP_SEE_OTHER);
        }

        protected function handleForm(
            EntityManagerInterface $em,
            Request $request,
            string $action,
            ReservationEquipment $reservationEquipment,
            ?string $redirect = null
        ): Response {
            $form = $this->createForm(ReservationEquipmentType::class, $reservationEquipment, ['action' => $action, 'method' => 'POST']);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($reservationEquipment);
                $em->flush();

                if ($redirect) {
                    return $this->redirect($redirect);
                } else {
                    return $this->redirectToRoute('app_admin_reservation_equipment_list', ['idReservation' => $reservationEquipment->getReservation()->getId()], Response::HTTP_SEE_OTHER);
                }
            }

            return $this->render('admin/reservation/equipment/form.html.twig', [
                'form' => $form,
                'reservationEquipment' => $reservationEquipment,
            ]);
        }
    }
