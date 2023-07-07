<?php

namespace App\Controller;

use App\Entity\FoodProduct;
use App\Form\FoodProductType;
use App\Repository\FoodProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/food-product')]
class FoodProductController extends AbstractController
{
    #[Route('/', name: 'app_food_product_index', methods: ['GET'])]
    public function index(FoodProductRepository $foodProductRepository): Response
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('food_product/index.html.twig', [
            'food_products' => $foodProductRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_food_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FoodProductRepository $foodProductRepository): Response
    {
        $foodProduct = new FoodProduct();
        $form = $this->createForm(FoodProductType::class, $foodProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $foodProductRepository->save($foodProduct, true);

            return $this->redirectToRoute('app_food_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('food_product/new.html.twig', [
            'food_product' => $foodProduct,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_food_product_show', methods: ['GET'])]
    public function show(FoodProduct $foodProduct): Response
    {
        return $this->render('food_product/show.html.twig', [
            'food_product' => $foodProduct,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_food_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FoodProduct $foodProduct, FoodProductRepository $foodProductRepository): Response
    {
        $form = $this->createForm(FoodProductType::class, $foodProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $foodProductRepository->save($foodProduct, true);

            return $this->redirectToRoute('app_food_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('food_product/edit.html.twig', [
            'food_product' => $foodProduct,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_food_product_delete', methods: ['POST'])]
    public function delete(Request $request, FoodProduct $foodProduct, FoodProductRepository $foodProductRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$foodProduct->getId(), $request->request->get('_token'))) {
            $foodProductRepository->remove($foodProduct, true);
        }

        return $this->redirectToRoute('app_food_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
