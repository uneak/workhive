<?php

    namespace App\Controller\Admin;

    use App\Entity\Equipment;
    use App\Form\EquipmentType;
    use App\Repository\EquipmentRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('admin/equipment', name: 'app_admin_equipment_')]
    class EquipmentController extends AbstractController
    {
        #[Route('/', name: 'list', methods: ['GET'])]
        public function index(EquipmentRepository $repository): Response
        {
            return $this->render('admin/equipment/list.html.twig', [
                'equipments' => $repository->findAll()
            ]);
        }

        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function create(EntityManagerInterface $em, Request $request): Response
        {
            $equipment = new Equipment();
            $action = $this->generateUrl('app_admin_equipment_new');

            return $this->handleForm($em, $request, $action, $equipment);
        }

        #[Route('/{id}', name: 'show', methods: ['GET'])]
        public function show(EquipmentRepository $repository, int $id): Response
        {
            return $this->render('admin/equipment/show.html.twig', [
                'equipment' => $repository->find($id),
            ]);
        }

        #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(EntityManagerInterface $em, Request $request, int $id): Response
        {
            $equipment = $em->getRepository(Equipment::class)->find($id);
            $action = $this->generateUrl('app_admin_equipment_edit', ['id' => $id]);

            return $this->handleForm($em, $request, $action, $equipment);
        }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(EntityManagerInterface $em, Request $request, int $id): Response
        {
            $equipment = $em->getRepository(Equipment::class)->find($id);

            if ($this->isCsrfTokenValid('delete' . $equipment->getId(), $request->getPayload()->getString('_token'))) {
                $em->remove($equipment);
                $em->flush();
            }

            return $this->redirectToRoute('app_admin_equipment_list', [], Response::HTTP_SEE_OTHER);
        }

        protected function handleForm(
            EntityManagerInterface $em,
            Request $request,
            string $action,
            Equipment $equipment,
            ?string $redirect = null
        ): Response {
            $form = $this->createForm(EquipmentType::class, $equipment, ['action' => $action, 'method' => 'POST']);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($equipment);
                $em->flush();

                if ($redirect) {
                    return $this->redirect($redirect);
                } else {
                    return $this->redirectToRoute('app_admin_equipment_list');
                }
            }

            return $this->render('admin/equipment/form.html.twig', [
                'form' => $form,
                'equipment' => $equipment,
            ]);
        }
    }
