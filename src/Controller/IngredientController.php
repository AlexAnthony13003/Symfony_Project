<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    /**
     * FindAll Ingredients
     */
    #[Route('/ingredient', name: 'ingredient.index', methods: ['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        $ingredients = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1),
            10 /*limit per page*/
        );
        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

    /**
     * Form POST CreateIngredient
     */
    #[Route('/ingredient/nouveau', 'ingredient.new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager) : Response
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form-> isValid()) {
            $ingredient = $form->getData();
            $ingredient->setUser($this->getUser());
            
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a été créé avec succès'
            );

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * UPDATE an ingredient with a form
     */
    #[Route('/ingredient/edition/{id}', 'ingredient.edit', methods: ['GET', 'POST'])]
    public function edit(IngredientRepository $repository, int $id, Request $request, EntityManagerInterface $manager) : Response
    {
        $this->denyAccessUnlessGranted("ROLE_USER", "user === ingredient.getUser()");
        $ingredient = $repository->findOneBy(["id" => $id]);
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form-> isValid()) {
            $ingredient = $form->getData();
            
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a été modifié avec succès'
            );

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/edit.html.twig', [
            'form' =>$form->createView()
        ]);
    }

    #[Route('/ingredient/suppression/{id}', 'ingredient.delete', methods: ['GET'])]
    public function delete(int $id, IngredientRepository $repository, EntityManagerInterface $manager) : Response{
        
        $ingredient = $repository->findOneBy(["id" => $id]);
        $manager ->remove($ingredient);
        $manager ->flush();
        $this->addFlash(
            'success',
            'Votre ingrédient a été supprimé avec succès'
        );

        return $this->redirectToRoute('ingredient.index');
    }
}
