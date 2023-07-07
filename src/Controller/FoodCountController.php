<?php

namespace App\Controller;

use App\Entity\FoodCount;
use App\Form\FoodCountType;
use App\Repository\EntryRepository;
use App\Repository\FoodCountRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/food/count')]
class FoodCountController extends AbstractController
{
    #[Route('/', name: 'app_food_count_index', methods: ['GET'])]
    public function index(FoodCountRepository $foodCountRepository): Response
    {
        return $this->render('food_count/index.html.twig', [
            'food_counts' => $foodCountRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_food_count_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FoodCountRepository $foodCountRepository, EntryRepository $entryRepository): Response
    {
        $entryId = $request->query->get('entry');
        $foodCount = new FoodCount();
        $entry = $entryRepository->find($entryId);
        $foodCount->setEntry($entry);
        $form = $this->createForm(FoodCountType::class, $foodCount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $foodCountRepository->save($foodCount, true);

            return $this->redirectToRoute('app_entry_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('food_count/new.html.twig', [
            'food_count' => $foodCount,
            'form' => $form,
            'entry' => $entry
        ]);
    }

    #[Route('/{id}', name: 'app_food_count_show', methods: ['GET'])]
    public function show(FoodCount $foodCount): Response
    {
        return $this->render('food_count/show.html.twig', [
            'food_count' => $foodCount,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_food_count_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FoodCount $foodCount, FoodCountRepository $foodCountRepository): Response
    {
        $form = $this->createForm(FoodCountType::class, $foodCount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $foodCountRepository->save($foodCount, true);

            return $this->redirectToRoute('app_food_count_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('food_count/edit.html.twig', [
            'food_count' => $foodCount,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_food_count_delete', methods: ['POST'])]
    public function delete(Request $request, FoodCount $foodCount, FoodCountRepository $foodCountRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$foodCount->getId(), $request->request->get('_token'))) {
            $foodCountRepository->remove($foodCount, true);
        }

        return $this->redirectToRoute('app_food_count_index', [], Response::HTTP_SEE_OTHER);
    }
}
