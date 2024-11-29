<?php

    namespace App\Controller;

    use App\Entity\Payment;
    use App\Entity\Reservation;
    use App\Entity\Room;
    use App\Enum\PaymentStatus;
    use App\Enum\ReservationStatus;
    use App\Form\PaymentChoiceType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    class RentController extends AbstractController
    {
        #[Route('/room/{id}/rent', name: 'app_rent')]
        public function rent(EntityManagerInterface $em, Request $request, int $id): Response
        {
            $room = $em->getRepository(Room::class)->find($id);
            $user = $this->getUser();

            if (!$this->getUser()) {
                throw $this->createAccessDeniedException('Vous devez être connecté pour louer une salle.');
            }

            $form = $this->createForm(PaymentChoiceType::class, null, ['user' => $user]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();

                $selectedPaymentMethod = $data['payment_method'];
                $startAt = $data['startAt'];
                $endAt = $data['endAt'];

                // Calcul du montant de la réservation
                $calculatedAmount = $this->calculateReservationAmount($room, $startAt, $endAt);

                // Créer une nouvelle réservation
                $reservation = new Reservation();
                $reservation->setRoom($room);
                $reservation->setUser($user);
                $reservation->setStartAt($startAt);
                $reservation->setEndAt($endAt);
                $reservation->setStatus(ReservationStatus::PENDING);
                $em->persist($reservation);
                $em->flush();

                // Créer un paiement
                $payment = new Payment();
                $payment->setReservation($reservation);
                $payment->setAmount($calculatedAmount);
                $payment->setPaymentMethod($selectedPaymentMethod);
                $payment->setStatus(PaymentStatus::COMPLETED);
                $em->persist($payment);
                $em->flush();

                $reservation->setStatus(ReservationStatus::CONFIRMED);
                $em->persist($reservation);
                $em->flush();

                // Ajout d'un message de confirmation
//                $this->addFlash('success', 'La salle a été réservée et le paiement a été effectué avec succès.');

                // Redirection vers une page de confirmation ou la liste des salles
                return $this->redirectToRoute('app_home');
            }

            return $this->render('front/rent.html.twig', [
                'room' => $room,
                'form' => $form,
            ]);
        }

        /**
         * Calcule le montant de la réservation en fonction de la durée et du tarif.
         *
         * @param Room $room
         * @param \DateTimeInterface $startAt
         * @param \DateTimeInterface $endAt
         * @return float
         */
        private function calculateReservationAmount(Room $room, \DateTimeInterface $startAt, \DateTimeInterface $endAt): float
        {
            $hourlyRate = 100; // Tarif horaire par défaut (vous pouvez le récupérer depuis la base de données)
            $duration = $endAt->getTimestamp() - $startAt->getTimestamp();
            $hours = max(1, ceil($duration / 3600)); // Arrondi à l'heure suivante
            return $hourlyRate * $hours;
        }
    }
