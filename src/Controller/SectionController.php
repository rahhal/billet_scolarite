<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\Section;
use App\Form\SectionType;

/**
 * @Route("/section")
 */
class SectionController extends AbstractController
{
    /**
     * @Route("/", name="section_index", methods={"GET"})
     */
    public function indexSection(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(SectionType::class,null);
        $search = null;

        $sort = $request->query->get("sort", "id");
        $order = $request->query->get("order", "DESC");
        $limit = $request->query->getInt("limit", 10);
        $id=$this->getUser()->getId();

        $_sections = $em->getRepository(Section::class)->getListSection( $sort, $id, $order);
        $sections = $_sections;

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView("section/liste.html.twig", ["sections" => $sections]);
            $countItem = count($_sections);

            return new JsonResponse(compact(["content", "data", "countItem"]));
        }

        return $this->render("section/index.html.twig", [
            "form" => $form->createView(),
            "sections" => $sections
        ]);
    }

    /**
     * @Route("/new", name="section_new", methods={"GET","POST"})
     */
    public function newSection(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (method_exists($section, "setTranslatableLocale"))
                $section->setTranslatableLocale($request->getLocale());
                $user= $this->getUser();
                $section->setUser($user);

            $em->persist($section);
            $em->flush();

            $content = $this->renderView("section/item.html.twig", ["section" => $section]);
            return new Response($content);
        }
    }

    /**
     * @Route("/{id}/edit", name="section_edit", methods={"GET","POST"})
     */
    public function editSection(Request $request, Section $section)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (method_exists($section, "setTranslatableLocale"))
                $section->setTranslatableLocale($request->getLocale());
            $em->flush();

            $content = $this->renderView("section/item.html.twig", ["section" => $section,"index"=>$request->get("index")]);
            return new Response($content);
        }

        $content = $this->renderView("section/form.html.twig", [
            "form" => $form->createView(),
            "action" => $this->generateUrl("section_edit", [
                "id" => $section->getId()
            ])
        ]);
        return new Response($content);
    }

    /**
     * @Route("/{id}/delete", name="section_delete")
     */
    public function deleteSection(Section $section)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($section);
        $em->flush();

        return new Response(1);
    }
}

