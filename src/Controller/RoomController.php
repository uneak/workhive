<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/room', name: 'app_room_')]
class RoomController extends AbstractController
{
    #[Route('/', name: 'list', methods: ['GET'])]
    public function index(RoomRepository $repository): Response
    {
        return $this->render('room/list.html.twig', [
            'rooms' => $repository->findAll()
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($room);
            $em->flush();

            return $this->redirectToRoute('app_room_list');
        }

        return $this->render('room/form.html.twig', [
            'form' => $form,
            'room' => $room,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(RoomRepository $repository, int $id): Response
    {
        return $this->render('room/show.html.twig', [
            'room' => $repository->find($id),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $room = $em->getRepository(Room::class)->find($id);
        $form = $this->createForm(RoomType::class, $room);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($room);
            $em->flush();

            return $this->redirectToRoute('app_room_list');
        }

        return $this->render('room/form.html.twig', [
            'form' => $form,
            'room' => $room,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $room = $em->getRepository(Room::class)->find($id);

        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($room);
            $em->flush();
        }

        return $this->redirectToRoute('app_room_list', [], Response::HTTP_SEE_OTHER);
    }

}
