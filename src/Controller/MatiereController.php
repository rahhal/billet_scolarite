<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\Matiere;
use App\Form\MatiereType;

/**
 * @Route("/matiere")
 */
class MatiereController extends AbstractController
{
    /**
     * @Route("/", name="matiere_index", methods={"GET"})
     */
    public function indexMatiereAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(MatiereType::class,null);
        $search = null;

        $sort = $request->query->get("sort", "id");
        $order = $request->query->get("order", "DESC");
        $limit = $request->query->getInt("limit", 10);
        $id=$this->getUser()->getId();

        $_matieres = $em->getRepository(Matiere::class)->getListMatiere( $sort, $id, $order);
        $matieres = $_matieres;

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView("matiere/liste.html.twig", ["matieres" => $matieres]);
            $countItem = count($_matieres);

            return new JsonResponse(compact(["content", "data", "countItem"]));
        }

        return $this->render("matiere/index.html.twig", [
            "form" => $form->createView(),
            "matieres" => $matieres
        ]);
    }

    /**
     * @Route("/new", name="matiere_new", methods={"GET","POST"})
     */
    public function newMatiereAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $matiere = new Matiere();
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (method_exists($matiere, "setTranslatableLocale"))
                $matiere->setTranslatableLocale($request->getLocale());

                $user = $this->getUser();
                $matiere->setUser($user);

            $em->persist($matiere);
            $em->flush();

            $content = $this->renderView("matiere/item.html.twig",
             ["matiere" => $matiere]);
            return new Response($content);
        }
    }

    /**
     * @Route("/{id}/edit", name="matiere_edit", methods={"GET","POST"})
     */
    public function editMatiereAction(Request $request, Matiere $matiere)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (method_exists($matiere, "setTranslatableLocale"))
                $matiere->setTranslatableLocale($request->getLocale());
            $em->flush();

            $content = $this->renderView("matiere/item.html.twig",
             ["matiere" => $matiere,
             "index"=>$request->get("index")]);
            return new Response($content);
        }

        $content = $this->renderView("matiere/form.html.twig", [
            "form" => $form->createView(),
            "action" => $this->generateUrl("matiere_edit", [
            "id" => $matiere->getId()
            ])
        ]);
        return new Response($content);
    }

    /**
     * @Route("/{id}/delete", name="matiere_delete")
     */
    public function deleteMatiereAction(Matiere $matiere)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($matiere);
        $em->flush();

        return new Response(1);
    }
}
