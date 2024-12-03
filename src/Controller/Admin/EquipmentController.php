<?php

    namespace App\Controller\Admin;

    use App\Core\Model\ObjectModel;
    use App\Core\Services\Manager\EquipmentManager;
    use App\Entity\Equipment;
    use App\Form\EquipmentType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/equipment', name: 'app_admin_equipment_')]
    class EquipmentController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(EquipmentManager $manager): Response
        {
            return $this->render('admin/equipment/list.html.twig', [
                'equipments' => $manager->all()
            ]);
        }

        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function create(EquipmentManager $manager, Request $request): Response
        {
            $equipment = new Equipment();

            /** @var Equipment $data */
            [$isSubmitted, $form, $data] = $this->handleForm(
                $request,
                EquipmentType::class,
                $equipment,
                [
                    'action' => $this->generateUrl('app_admin_equipment_new'),
                    'method' => 'POST'
                ]
            );

            if ($isSubmitted) {
                $manager->save($data, true);

                return $this->redirectToRoute('app_admin_equipment_list', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('admin/equipment/form.html.twig', [
                'form'      => $form,
                'equipment' => $data
            ]);
        }

        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(EquipmentManager $manager, int $id): Response
        {
            return $this->render('admin/equipment/show.html.twig', [
                'equipment' => $manager->get($id),
            ]);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(EquipmentManager $manager, Request $request, int $id): Response
        {
            /** @var Equipment $data */
            [$isSubmitted, $form, $data] = $this->handleForm(
                $request,
                EquipmentType::class,
                $manager->get($id),
                [
                    'action' => $this->generateUrl('app_admin_equipment_edit', ['id' => $id]),
                    'method' => 'POST'
                ]
            );

            if ($isSubmitted) {
                $manager->save($data, true);

                return $this->redirectToRoute('app_admin_equipment_list', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('admin/equipment/form.html.twig', [
                'form'      => $form,
                'equipment' => $data
            ]);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(EquipmentManager $manager, Request $request, int $id): Response
        {
            if ($this->isCsrfTokenValid('delete' . $id, $request->getPayload()->getString('_token'))) {
                $manager->remove($manager->get($id), true);
            }

            return $this->redirectToRoute('app_admin_equipment_list', [], Response::HTTP_SEE_OTHER);
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
