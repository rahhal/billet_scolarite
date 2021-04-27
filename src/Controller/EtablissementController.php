<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\Etablissement;
use App\Entity\Search\EtablissementSearch;
use App\Form\Search\EtablissementSearchType;
use App\Form\EtablissementType;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/etablissement")
 */

class EtablissementController extends AbstractController
{
    /**
     * @Route("/", name="etablissement_new")
     */

     public function newEtablissementAction(Request $request)
     {
        $em = $this->getDoctrine()->getManager();
         $etablissement = $em->getRepository(Etablissement::class)
             ->findOneBy(['user' => $this->getUser()->getId()]);
         if (!$etablissement)
         {
                $etablissement = new Etablissement();
                $form = $this->createForm(EtablissementType::class, $etablissement);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    if (method_exists($etablissement, "setTranslatableLocale"))
                        $etablissement->setTranslatableLocale($request->getLocale());
                    $user = $this->getUser();
                    $etablissement->setUser($user);

                    $em->persist($etablissement);
                    $em->flush();
        
                    return $this->redirectToRoute('etablissement_index');
                }
                return $this->render("etablissement/new.html.twig", [
                    "form" => $form->createView(),
                    "etablissement" => $etablissement ]);
         }
         else
         // return $this->renderView("AppBundle:StaticData:Etablissement/index.html.twig", [
         //     "etablissement" => $etablissement ]);

             return $this->redirectToRoute('etablissement_index');
    
     }
    /**
     * @Route("/ajout", name="etablissement_index", methods={"GET"})
     */
    public function indexEtablissementAction(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(EtablissementType::class,null);
        $search = new EtablissementSearch();
        $form_search = $this->createForm(EtablissementSearchType::class, $search);
        $form_search->handleRequest($request);

        $user=$this->getUser();
        $sort = $request->query->get("sort", "id");
        $order = $request->query->get("order", "DESC");
        $limit = $request->query->getInt("limit", 10);
        $id=$this->getUser()->getId();
        $_etablissements = $em->getRepository(Etablissement::class)
                              ->getListEtablissement($search, $id, $sort, $order);
       // var_dump($id);die();
        $etablissements = $paginator
        //->get('knp_paginator')
                               ->paginate($_etablissements, $request->query->getInt("page", 1), $limit);

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView("etablissement/liste.html.twig", ["etablissements" => $etablissements]);
            $pagination = $this->renderView(":knp_paginator:pagination_render.html.twig", ["items" => $etablissements]);
           $countItem = count($_etablissements);

            return new JsonResponse(compact(["content", "pagination", "data", "countItem"]));
        }
       // $etablissement = $em->getRepository(Etablissement::class)->findAll();
        return $this->render("etablissement/index.html.twig", [
            "form" => $form->createView(),
            "form_search" => $form_search->createView(),
            "etablissements" => $etablissements,
            //"etablissement" => $etablissement
        ]);
    }
    /**
     * @Route("/{id}/edit", name="etablissement_edit", methods={"GET","POST"})
     */
    public function editEtablissementAction(Request $request, Etablissement $etablissement)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (method_exists($etablissement, "setTranslatableLocale"))
                $etablissement->setTranslatableLocale($request->getLocale());
            $em->flush();

            $content = $this->renderView("etablissement/item.html.twig", ["etablissement" => $etablissement]);
            return new Response($content);
        }

        $content = $this->renderView("etablissement/form.html.twig", [
            "form" => $form->createView(),
            "action" => $this->generateUrl("etablissement_edit", [
                "id" => $etablissement->getId()
            ])
        ]);
        return new Response($content);
    }
 /**
     * @Route("/show", name="etablissement_show", methods={"GET"})
     */
     public function show( )
     {
        $em = $this->getDoctrine()->getManager();
         $etablissement = $em->getRepository(Etablissement::class)->find($id);
 
         if ($etablissement)
             return $this->renderView("etablissement/show.html.twig", [
                 "etablissement" => $etablissement,
                 
             ]);
         else
             return $this->redirectToRoute('etablissement_new');
 
     }
    /**
     * @Route("/{id}/delete", name="etablissement_delete")
     */
    public function deleteEtablissementAction(Etablissement $etablissement)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($etablissement);
        $em->flush();

        return new Response(1);
    }
}

