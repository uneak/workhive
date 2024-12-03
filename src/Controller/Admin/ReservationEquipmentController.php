<?php

    namespace App\Controller\Admin;

    use App\Core\Model\ObjectModel;
    use App\Core\Services\Manager\ReservationEquipmentManager;
    use App\Core\Services\Manager\ReservationManager;
    use App\Entity\ReservationEquipment;
    use App\Form\ReservationEquipmentType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/reservation/{idReservation}/equipment', name: 'app_admin_reservation_equipment_')]
    class ReservationEquipmentController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(
            ReservationEquipmentManager $manager,
            ReservationManager $reservationManager,
            int $idReservation
        ): Response {
            return $this->render('admin/reservation/equipment/list.html.twig', [
                'reservationEquipments' => $manager->getByReservation($idReservation),
                'reservation'          => $reservationManager->get($idReservation),
            ]);
        }

        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function create(
            ReservationEquipmentManager $manager,
            ReservationManager $reservationManager,
            Request $request,
            int $idReservation
        ): Response {
            $reservation = $reservationManager->get($idReservation);
            $reservationEquipment = new ReservationEquipment();
            $reservationEquipment->setReservation($reservation);

            /** @var ReservationEquipment $data */
            [$isSubmitted, $form, $data] = $this->handleForm(
                $request,
                ReservationEquipmentType::class,
                $reservationEquipment,
                [
                    'action' => $this->generateUrl('app_admin_reservation_equipment_new', ['idReservation' => $reservation->getId()]),
                    'method' => 'POST'
                ]
            );

            if ($isSubmitted) {
                $manager->save($data, true);

                return $this->redirectToRoute('app_admin_reservation_equipment_list',
                    ['idReservation' => $reservation->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('admin/reservation/equipment/form.html.twig', [
                'form'                 => $form,
                'reservationEquipment' => $data
            ]);
        }

        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(ReservationEquipmentManager $manager, int $id): Response
        {
            return $this->render('admin/reservation/equipment/show.html.twig', [
                'reservationEquipment' => $manager->get($id),
            ]);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(
            ReservationEquipmentManager $manager,
            Request $request,
            int $idReservation,
            int $id
        ): Response {
            /** @var ReservationEquipment $data */
            [$isSubmitted, $form, $data] = $this->handleForm(
                $request,
                ReservationEquipmentType::class,
                $manager->get($id),
                [
                    'action' => $this->generateUrl('app_admin_reservation_equipment_edit',
                        ['idReservation' => $idReservation, 'id' => $id]),
                    'method' => 'POST'
                ]
            );

            if ($isSubmitted) {
                $manager->save($data, true);

                return $this->redirectToRoute('app_admin_reservation_equipment_list',
                    ['idReservation' => $idReservation], Response::HTTP_SEE_OTHER);
            }

            return $this->render('admin/reservation/equipment/form.html.twig', [
                'form'                 => $form,
                'reservationEquipment' => $data
            ]);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(
            ReservationEquipmentManager $manager,
            Request $request,
            int $idReservation,
            int $id
        ): Response {
            if ($this->isCsrfTokenValid('delete' . $id, $request->getPayload()->getString('_token'))) {
                $manager->remove($manager->get($id), true);
            }

            return $this->redirectToRoute('app_admin_reservation_equipment_list',
                ['idReservation' => $idReservation], Response::HTTP_SEE_OTHER);
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
