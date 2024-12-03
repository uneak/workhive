<?php

    namespace App\Controller\Admin;

    use App\Core\Model\ObjectModel;
    use App\Core\Services\Manager\EquipmentManager;
    use App\Core\Services\Manager\EquipmentRoleRateManager;
    use App\Entity\EquipmentRoleRate;
    use App\Form\EquipmentRoleRateType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/equipment/{idEquipment}/rate', name: 'app_admin_equipment_rate_')]
    class EquipmentRoleRateController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(
            EquipmentManager $equipmentManager,
            EquipmentRoleRateManager $manager,
            int $idEquipment
        ): Response {
            $equipment = $equipmentManager->get($idEquipment);

            return $this->render('admin/equipment/rate/list.html.twig', [
                'equipment'          => $equipment,
                'equipmentRoleRates' => $manager->getByEquipment($equipment)
            ]);
        }

        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function create(
            EquipmentRoleRateManager $manager,
            EquipmentManager $equipmentManager,
            Request $request,
            int $idEquipment
        ): Response {
            $equipment = $equipmentManager->get($idEquipment);
            $equipmentRoleRate = new EquipmentRoleRate();
            $equipmentRoleRate->setEquipment($equipment);

            /** @var EquipmentRoleRate $data */
            [$isSubmitted, $form, $data] = $this->handleForm(
                $request,
                EquipmentRoleRateType::class,
                $equipmentRoleRate,
                [
                    'action' => $this->generateUrl('app_admin_equipment_rate_new',
                        ['idEquipment' => $equipment->getId()]),
                    'method' => 'POST'
                ]
            );

            if ($isSubmitted) {
                $manager->save($data, true);

                return $this->redirectToRoute('app_admin_equipment_rate_list',
                    ['idEquipment' => $equipment->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('admin/equipment/rate/form.html.twig', [
                'form'              => $form,
                'equipmentRoleRate' => $data
            ]);
        }

        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(EquipmentRoleRateManager $manager, int $id): Response
        {
            return $this->render('admin/equipment/rate/show.html.twig', [
                'equipmentRoleRate' => $manager->get($id),
            ]);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(
            EquipmentRoleRateManager $manager,
            Request $request,
            int $idEquipment,
            int $id
        ): Response {
            /** @var EquipmentRoleRate $data */
            [$isSubmitted, $form, $data] = $this->handleForm(
                $request,
                EquipmentRoleRateType::class,
                $manager->get($id),
                [
                    'action' => $this->generateUrl('app_admin_equipment_rate_edit',
                        ['id' => $id, 'idEquipment' => $idEquipment]),
                    'method' => 'POST'
                ]
            );

            if ($isSubmitted) {
                $manager->save($data, true);

                return $this->redirectToRoute('app_admin_equipment_rate_list',
                    ['idEquipment' => $idEquipment], Response::HTTP_SEE_OTHER);
            }

            return $this->render('admin/equipment/rate/form.html.twig', [
                'form'              => $form,
                'equipmentRoleRate' => $data
            ]);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(
            EquipmentRoleRateManager $manager,
            Request $request,
            int $idEquipment,
            int $id
        ): Response {
            if ($this->isCsrfTokenValid('delete' . $id, $request->getPayload()->getString('_token'))) {
                $manager->remove($manager->get($id), true);
            }

            return $this->redirectToRoute('app_admin_equipment_rate_list',
                ['idEquipment' => $idEquipment], Response::HTTP_SEE_OTHER);
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
