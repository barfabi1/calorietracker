<?php

namespace App\Controller;

use DateTime;
use App\Entity\Entry;
use App\Entity\FoodCount;
use App\Form\EntryType;
use App\Repository\EntryRepository;
use App\Repository\FoodCountRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/entry')]
class EntryController extends AbstractController
{
    #[Route('/', name: 'app_entry_index', methods: ['GET'])]
    public function index(EntryRepository $entryRepository, Request $request): Response
    {
        return $this->render('entry/index.html.twig', [
            // 'entries' => $entryRepository->findAll(),  
            'entries' => $entryRepository->findBy(["user" => $this->getUser()]),  
        ]);
    }

    #[Route('/new', name: 'app_entry_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntryRepository $entryRepository): Response
    {
        $user = $this->getUser();
        $entry = new Entry();
        $entry->setUser($user);
        $entry->setDate(new DateTime("now"));
        $form = $this->createForm(EntryType::class, $entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entryRepository->save($entry, true);

            return $this->redirectToRoute('app_entry_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entry/new.html.twig', [
            'entry' => $entry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_entry_show', methods: ['GET'])]
    public function show(Entry $entry): Response
    {
        return $this->render('entry/show.html.twig', [
            'entry' => $entry,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_entry_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entry $entry, EntryRepository $entryRepository): Response
    {
        $form = $this->createForm(EntryType::class, $entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entryRepository->save($entry, true);

            return $this->redirectToRoute('app_entry_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entry/edit.html.twig', [
            'entry' => $entry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_entry_delete', methods: ['POST'])]
    public function delete(Request $request, Entry $entry, EntryRepository $entryRepository, FoodCountRepository $foodCountRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entry->getId(), $request->request->get('_token'))) {
            $foodCounts = $foodCountRepository->findBy(['entry' => $entry]);
            foreach ($foodCounts as $item) {
                $foodCountRepository->remove($item, true);
            }
            $entryRepository->remove($entry, true);
        }

        return $this->redirectToRoute('app_entry_index', [], Response::HTTP_SEE_OTHER);
    }
}
