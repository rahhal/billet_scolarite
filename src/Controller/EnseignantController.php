<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\Enseignant;
use App\Form\EnseignantType;

/**
 * @Route("/enseignant")
 */
class EnseignantController extends AbstractController
{
    /**
     * @Route("/", name="enseignant_index", methods={"GET"})
     */
    public function indexEnseignantAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EnseignantType::class,null);
        $search = null;

        $sort = $request->query->get("sort", "id");
        $order = $request->query->get("order", "DESC");
        $limit = $request->query->getInt("limit", 10);
        $id=$this->getUser()->getId();

       /*  $code="0082590648";
        $enseignant = $em->getRepository(Enseignant::class)
        ->getEnseignantBycurentUser($code, $id);

dump($enseignant);die(); */

        $_enseignants = $em->getRepository(Enseignant::class)->getListEnseignant( $sort, $id, $order);
        $enseignants = $_enseignants;

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView("enseignant/liste.html.twig", ["enseignants" => $enseignants]);
            $countItem = count($_enseignants);

            return new JsonResponse(compact(["content", "data", "countItem"]));
        }

        return $this->render("enseignant/index.html.twig", [
            "form" => $form->createView(),
            "enseignants" => $enseignants
        ]);
    }

    /**
     * @Route("/new", name="enseignant_new", methods={"GET","POST"})
     */
    public function newEnseignantAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $enseignant = new Enseignant();
        $form = $this->createForm(EnseignantType::class, $enseignant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (method_exists($enseignant, "setTranslatableLocale"))
                $enseignant->setTranslatableLocale($request->getLocale());
                $user = $this->getUser();
                $enseignant->setUser($user);

            $em->persist($enseignant);
            $em->flush();

            $content = $this->renderView("enseignant/item.html.twig", ["enseignant" => $enseignant]);
            return new Response($content);
        }
    }

    /**
     * @Route("/{id}/edit", name="enseignant_edit", methods={"GET","POST"})
     */
    public function editEnseignantAction(Request $request, Enseignant $enseignant)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EnseignantType::class, $enseignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (method_exists($enseignant, "setTranslatableLocale"))
                $enseignant->setTranslatableLocale($request->getLocale());
            $em->flush();

            $content = $this->renderView("enseignant/item.html.twig", ["enseignant" => $enseignant,"index"=>$request->get("index")]);
            return new Response($content);
        }

        $content = $this->renderView("enseignant/form.html.twig", [
            "form" => $form->createView(),
            "action" => $this->generateUrl("enseignant_edit", [
                "id" => $enseignant->getId()
            ])
        ]);
        return new Response($content);
    }

    /**
     * @Route("/{id}/delete", name="enseignant_delete")
     */
    public function deleteEnseignantAction(Enseignant $enseignant)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($enseignant);
        $em->flush();

        return new Response(1);
    }
}
