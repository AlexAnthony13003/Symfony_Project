<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Flex\Recipe;

class RecetteController extends AbstractController
{
    /**
     * Ce controller fait un select de toutes les recettes avec pagination
     */
    #[Route('/recette', name: 'recette.index', methods: ['GET'])]
    public function index(EntityManagerInterface $recette, PaginatorInterface $paginator,
     RecetteRepository $repository, Request $request): Response
    {
        $recettes = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10 /*limit per page*/
        );
        return $this->render('pages/recette/index.html.twig', [
            'recettes' => $recettes,
        ]);
    }

    /**
     * Create Recipe
     */
    #[Route('/recette/nouveau', 'recette.new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $recette = $form->getData();
            
            $manager->persist($recette);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été créé avec succès'
            );

            return $this->redirectToRoute('recette.index');
        }

        return $this->render('pages/recette/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * UPDATE a recipe with a form
     */
    #[Route('/recette/edition/{id}', 'recette.edit', methods: ['GET', 'POST'])]
    public function edit(RecetteRepository $repository,int $id, Request $request, EntityManagerInterface $manager) : Response
    {
        $recette = $repository->findOneBy(["id" => $id]);
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form-> isValid()) {
            $recette = $form->getData();
            
            $manager->persist($recette);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été modifié avec succès'
            );

            return $this->redirectToRoute('recette.index');
        }

        return $this->render('pages/recette/edit.html.twig', [
            'form' =>$form->createView()
        ]);
    }

    #[Route('/recette/suppression/{id}', 'recette.delete', methods: ['GET'])]
    public function delete(int $id, RecetteRepository $repository, EntityManagerInterface $manager) : Response{
        
        $recette = $repository->findOneBy(["id" => $id]);
        $manager ->remove($recette);
        $manager ->flush();
        $this->addFlash(
            'success',
            'Votre recette a été supprimé avec succès'
        );

        return $this->redirectToRoute('recette.index');
    }
}
