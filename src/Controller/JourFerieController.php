<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\AnneeScolaire;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\JourFerie;
use App\Form\JourFerieType;

/**
 * @Route("/jourferie")
 */
class JourFerieController extends AbstractController
{
    /**
     * @Route("/", name="jourferie_index", methods={"GET"})
     */
    public function indexJourFerieAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(JourFerieType::class,null);
        $search = null;

        $sort = $request->query->get("sort", "id");
        $order = $request->query->get("order", "DESC");
        $limit = $request->query->getInt("limit", 10);
        $id= $this->getUser()->getId();

        $annee = $em->getRepository(AnneeScolaire::class)->findOneBy(['current' => true]);
        // $_jourferies = $em->getRepository(JourFerie::class)->getListJourFerie($sort, $order, $id, $annee);
      //  $_jourferies = $em->getRepository(JourFerie::class)->findAll();
      $_jourferies = $em->getRepository(JourFerie::class)->getListJourFerie($sort, $order, $id);

        $jourferies = $_jourferies;
  //dump($jourferies);die();

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView("jourFerie/liste.html.twig", ["jourferies" => $jourferies]);
            $countItem = count($_jourferies);

            return new JsonResponse(compact(["content", "data", "countItem"]));
        }

        return $this->render("jourFerie/index.html.twig", [
            "form" => $form->createView(),
            "jourferies" => $jourferies
        ]);
    }

    /**
     * @Route("/new", name="jourferie_new", methods={"GET","POST"})
     */
    public function newJourFerieAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $annee = $em->getRepository(AnneeScolaire::class)->findOneBy(['current' => true]);
        $jourferie = new JourFerie();
        $jourferie->setAnneeScolaire($annee);
        $form = $this->createForm(JourFerieType::class, $jourferie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (method_exists($jourferie, "setTranslatableLocale"))
                $jourferie->setTranslatableLocale($request->getLocale());
                $user= $this->getUser();
                $jourferie->setUser($user);
            $em->persist($jourferie);
            $em->flush();

            $content = $this->renderView("jourFerie/item.html.twig", ["jourferie" => $jourferie]);
            return new Response($content);
        }
    }

    /**
     * @Route("/{id}/edit", name="jourferie_edit", methods={"GET","POST"})
     */
    public function editJourFerieAction(Request $request, JourFerie $jourferie)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(JourFerieType::class, $jourferie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (method_exists($jourferie, "setTranslatableLocale"))
                $jourferie->setTranslatableLocale($request->getLocale());
            $em->flush();

            $content = $this->renderView("jourFerie/item.html.twig", ["jourferie" => $jourferie,"index"=>$request->get("index")]);
            return new Response($content);
        }

        $content = $this->renderView("jourFerie/form.html.twig", [
            "form" => $form->createView(),
            "action" => $this->generateUrl("jourferie_edit", [
                "id" => $jourferie->getId()
            ])
        ]);
        return new Response($content);
    }

    /**
     * @Route("/{id}/delete", name="jourferie_delete")
     */
    public function deleteJourFerieAction(JourFerie $jourferie)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($jourferie);
        $em->flush();

        return new Response(1);
    }
}
