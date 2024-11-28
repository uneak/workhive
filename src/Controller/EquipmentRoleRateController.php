<?php

    namespace App\Controller;

    use App\Entity\Equipment;
    use App\Entity\EquipmentRoleRate;
    use App\Form\EquipmentRoleRateType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/equipment/{idEquipment}/rate', name: 'app_equipment_rate_')]
    class EquipmentRoleRateController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(EntityManagerInterface $em, int $idEquipment): Response
        {
            $equipment = $em->getRepository(Equipment::class)->find($idEquipment);
            $equipmentRoleRates = $em->getRepository(EquipmentRoleRate::class)->findBy(['equipment' => $equipment]);

            return $this->render('equipment/rate/list.html.twig', [
                'equipment' => $equipment,
                'equipmentRoleRates' => $equipmentRoleRates
            ]);
        }

        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function create(EntityManagerInterface $em, Request $request, int $idEquipment): Response
        {
            $equipment = $em->getRepository(Equipment::class)->find($idEquipment);
            $action = $this->generateUrl('app_equipment_rate_new', ['idEquipment' => $idEquipment]);

            $equipmentRoleRate = new EquipmentRoleRate();
            $equipmentRoleRate->setEquipment($equipment);

            return $this->handleForm($em, $request, $action, $equipmentRoleRate);
        }

        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(EntityManagerInterface $em, int $id): Response
        {
            $equipmentRoleRate = $em->getRepository(EquipmentRoleRate::class)->find($id);

            return $this->render('equipment/rate/show.html.twig', [
                'equipmentRoleRate' => $equipmentRoleRate,
            ]);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(EntityManagerInterface $em, Request $request, int $idEquipment, int $id): Response
        {
            $equipmentRoleRate = $em->getRepository(EquipmentRoleRate::class)->find($id);
            $action = $this->generateUrl('app_equipment_rate_edit', ['id' => $id, 'idEquipment' => $idEquipment]);

            return $this->handleForm($em, $request, $action, $equipmentRoleRate);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(EntityManagerInterface $em, Request $request, int $idEquipment, int $id): Response
        {
            $equipmentRoleRate = $em->getRepository(EquipmentRoleRate::class)->find($id);

            if ($this->isCsrfTokenValid('delete' . $equipmentRoleRate->getId(), $request->getPayload()->getString('_token'))) {
                $em->remove($equipmentRoleRate);
                $em->flush();
            }

            return $this->redirectToRoute('app_equipment_rate_list', ['idEquipment' => $idEquipment], Response::HTTP_SEE_OTHER);
        }

        protected function handleForm(
            EntityManagerInterface $em,
            Request $request,
            string $action,
            EquipmentRoleRate $equipmentRoleRate,
            ?string $redirect = null
        ): Response {
            $form = $this->createForm(EquipmentRoleRateType::class, $equipmentRoleRate, ['action' => $action, 'method' => 'POST']);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($equipmentRoleRate);
                $em->flush();

                if ($redirect) {
                    return $this->redirect($redirect);
                } else {
                    return $this->redirectToRoute('app_equipment_rate_list', ['idEquipment' => $equipmentRoleRate->getEquipment()->getId()], Response::HTTP_SEE_OTHER);
                }
            }

            return $this->render('equipment/rate/form.html.twig', [
                'form' => $form,
                'equipmentRoleRate' => $equipmentRoleRate,
            ]);
        }
    }
