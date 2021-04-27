<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\Classe;
use App\Form\ClasseType;

/**
 * @Route("/classe")
 */
class ClasseController extends AbstractController
{
    /**
     * @Route("/", name="classe_index", methods={"GET"})
     */
    public function indexClasseAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ClasseType::class,null);
        $search = null;

        $sort = $request->query->get("sort", "id");
        $order = $request->query->get("order", "DESC");
        $limit = $request->query->getInt("limit", 10);
        $id=$this->getUser()->getId();

        $_classes = $em->getRepository(Classe::class)->getListClasse($sort, $id, $order);
        $classes = $_classes;

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView("classe/liste.html.twig", ["classes" => $classes]);
            $countItem = count($_classes);

            return new JsonResponse(compact(["content", "data", "countItem"]));
        }

        return $this->render("classe/index.html.twig", [
            "form" => $form->createView(),
            "classes" => $classes
        ]);
    }

    /**
     * @Route("/new", name="classe_new", methods={"GET","POST"})
     */
    public function newClasseAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $classe = new Classe();
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (method_exists($classe, "setTranslatableLocale"))
                $classe->setTranslatableLocale($request->getLocale());

            $user = $this->getUser();
            $classe->setUser($user);

            $em->persist($classe);
            $em->flush();

            $content = $this->renderView("classe/item.html.twig", ["classe" => $classe]);
            return new Response($content);
        }
    }

    /**
     * @Route("/{id}/edit", name="classe_edit", methods={"GET","POST"})
     */
    public function editClasseAction(Request $request, Classe $classe)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (method_exists($classe, "setTranslatableLocale"))
                $classe->setTranslatableLocale($request->getLocale());
            $em->flush();

            $content = $this->renderView("classe/item.html.twig", ["classe" => $classe,"index"=>$request->get("index")]);
            return new Response($content);
        }

        $content = $this->renderView("classe/form.html.twig", [
            "form" => $form->createView(),
            "action" => $this->generateUrl("classe_edit", [
                "id" => $classe->getId()
            ])
        ]);
        return new Response($content);
    }

    /**
     * @Route("/{id}/delete", name="classe_delete")
     */
    public function deleteClasseAction(Classe $classe)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($classe);
        $em->flush();

        return new Response(1);
    }
}
