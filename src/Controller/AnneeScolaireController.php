<?php

namespace App\Controller;

use App\Entity\AnneeScolaire;
use App\Form\AnneeScolaireType;
use App\Repository\AnneeScolaireRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;


/**
 * @Route("/anneescolaire")
 */
class AnneeScolaireController extends AbstractController
{
    
     /**
     * @Route("/", name="anneescolaire_index", methods={"GET"})
     */

     public function indexAnneeScolaireAction(Request $request, AnneeScolaireRepository $anneescolaireRepository ): Response
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AnneeScolaireType::class,null);
        $search = null;

        $sort = $request->query->get("sort", "id");
        $order = $request->query->get("order", "DESC");
        $limit = $request->query->getInt("limit", 10);
        $id=$this->getUser()->getId();

        $_anneescolaires = $em->getRepository(AnneeScolaire::class)
                            ->getListAnneeScolaire( $sort, $id, $order);
        $anneescolaires = $_anneescolaires;

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView("AnneeScolaire/liste.html.twig",[
                                        "anneescolaires" => $anneescolaires
                                        ] );
            $countItem = count($_anneescolaires);

            return new JsonResponse(compact(["content", "data", "countItem"]));
        }

        return $this->render("AnneeScolaire/index.html.twig", [
            "form" => $form->createView(),
            "anneescolaires" => $anneescolaires
        ]);
    }
 /**
     * @Route("/new", name="anneescolaire_new", methods={"GET","POST"})
     */
     public function newAnneeScolaire(Request $request, EntityManagerInterface $em): Response
     {
         $anneescolaire = new AnneeScolaire();
         $form = $this->createForm(AnneeScolaireType::class, $anneescolaire);
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
             if (method_exists($anneescolaire, "setTranslatableLocale"))
                 $anneescolaire->setTranslatableLocale($request->getLocale());
                 $user= $this->getUser();
                 $anneescolaire->setUser($user);
             $em->persist($anneescolaire);
             $em->flush();
 
             $content = $this->renderView("AnneeScolaire/item.html.twig", ["anneescolaire" => $anneescolaire]);
             return new Response($content);
         }
     }
 
     /**
      * @Route("/{id}/edit", name="anneescolaire_edit", methods={"GET","POST"})
      */
     public function editAnneeScolaire(Request $request, AnneeScolaire $anneescolaire, EntityManagerInterface $em): Response
     {
         $form = $this->createForm(AnneeScolaireType::class, $anneescolaire);
         $form->handleRequest($request);
 
         if ($form->isSubmitted() && $form->isValid()) {
 
             if (method_exists($anneescolaire, "setTranslatableLocale"))
                 $anneescolaire->setTranslatableLocale($request->getLocale());
             $em->flush();
 
             $content = $this->renderView("AnneeScolaire/item.html.twig", ["anneescolaire" => $anneescolaire,"index"=>$request->get("index")]);
             return new Response($content);
         }
 
         $content = $this->renderView("AnneeScolaire/form.html.twig", [
             "form" => $form->createView(),
             "action" => $this->generateUrl("anneescolaire_edit", [
                 "id" => $anneescolaire->getId()
             ])
         ]);
         return new Response($content);
     }
 
     /**
      * @Route("/{id}/delete", name="anneescolaire_delete")
      */
     public function deleteAnneeScolaire(AnneeScolaire $anneescolaire, EntityManagerInterface $em): Response
     {
         $em->remove($anneescolaire);
         $em->flush();
 
         return new Response(1);
     }
}
