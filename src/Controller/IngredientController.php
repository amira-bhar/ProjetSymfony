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
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class IngredientController extends AbstractController
{ /**
 * 
 * ce controlleur affiche tous les ingredients
 *
 * @param IngredientRepository $repository
 * @param PaginatorInterface $paginator
 * @param Request $request
 * @return Response
 */
    // #[IsGranted('ROLE_USER')]
    #[Route('/ingredient', name: 'ingredient.index' , methods :['GET'])]
    
    public function index(IngredientRepository $repository, PaginatorInterface $paginator,
     Request $request): Response
    {    
        $ingredients = $paginator->paginate(
            $repository->findBy(['user'=> $this->getUser()]),
            $request->query->getInt('page', 1), 
            10 
        );
        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients'=>$ingredients
        ]);
    }
    /**
     * 
     *ce controlleur affiche une formulaire pour creer un ingredient
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    
    //  #[IsGranted('ROLE_USER')]
     #[Route('/ingredient/creation', 'ingredient.new',methods:['GET','POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
        ) : Response
    {
       $ingredient=new Ingredient();
       $form=$this->createForm(IngredientType::class, $ingredient);
       
       $form->handleRequest($request);
       if($form->isSubmitted()&& $form->isValid()){
        $ingredient=$form->getData();
        $ingredient->setUser($this->getUser());

         $manager->persist($ingredient);
         $manager->flush();
         
         $this->addFlash(
            'success',
            'Votre ingredient a été créé avec succés !'
         );
         return $this->redirectToRoute('ingredient.index');
       }
        return $this->render('pages/ingredient/new.html.twig', ['form'=>$form->createView()]);
    }
    // #[Security("is_granted('ROLE_USER') and user === ingredient.getUser()")]
    #[Route('/ingredient/edition/{id}', 'ingredient.edit', methods:['GET','POST'])]
    public function edit(
    Ingredient $ingredient,
    Request $request, 
    EntityManagerInterface $manager
    ) : Response
    {  
        $form=$this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
       if($form->isSubmitted()&& $form->isValid()){
        $ingredient=$form->getData();
         $manager->persist($ingredient);
         $manager->flush();
         
         $this->addFlash(
            'success',
            'Votre ingredient a été modifié avec succés !'
         );
         return $this->redirectToRoute('ingredient.index');
       }
        return $this->render('pages/ingredient/edit.html.twig',['form'=>$form->createView()]);

    }
    // #[Security("is_granted('ROLE_USER') and user === ingredient.getUser()")]
    #[Route('/ingredient/suppression/{id}','ingredient.delete', methods:['GET'])]
    public function delete(EntityManagerInterface $manager,Ingredient $ingredient) : Response{
        if(!$ingredient){
            $this->addFlash(
                'success',
                'L\'ingredient en question n\'a pas été trouvé  !'
             );
            return $this->redirectToRoute('ingredient.index');
        }
            $manager->remove($ingredient);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre ingredient a été supprimé avec succés !'
             );
            return $this->redirectToRoute('ingredient.index');
    }
}
