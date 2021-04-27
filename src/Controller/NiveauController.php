<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\Niveau;
use App\Form\NiveauType;

   /**
 * @Route("/niveau")
 */
class NiveauController extends AbstractController
{
    /**
     * @Route("/", name="niveau_index", methods={"GET"})
     */
    public function indexNiveauAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(NiveauType::class,null);
        $search = null;

        $sort = $request->query->get("sort", "id");
        $order = $request->query->get("order", "DESC");
        $limit = $request->query->getInt("limit", 10);
        $id=$this->getUser()->getId();

        $_niveaus = $em->getRepository(Niveau::class)->getListNiveau( $sort, $id, $order);
        $niveaus = $_niveaus;

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView("niveau/liste.html.twig", ["niveaus" => $niveaus]);
            $countItem = count($_niveaus);

            return new JsonResponse(compact(["content", "data", "countItem"]));
        }

        return $this->render("niveau/index.html.twig", [
            "form" => $form->createView(),
            "niveaus" => $niveaus
        ]);
    }

    /**
     * @Route("/new", name="niveau_new", methods={"GET","POST"})
     */
    public function newNiveauAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $niveau = new Niveau();
        $form = $this->createForm(NiveauType::class, $niveau);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (method_exists($niveau, "setTranslatableLocale"))
                $niveau->setTranslatableLocale($request->getLocale());
                $user= $this->getUser();
                $niveau->setUser($user);

            $em->persist($niveau);
            $em->flush();

            $content = $this->renderView("niveau/item.html.twig", ["niveau" => $niveau]);
            return new Response($content);
        }
    }

    /**
     * @Route("/{id}/edit", name="niveau_edit", methods={"GET","POST"})
     */
    public function editNiveauAction(Request $request, Niveau $niveau)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(NiveauType::class, $niveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (method_exists($niveau, "setTranslatableLocale"))
                $niveau->setTranslatableLocale($request->getLocale());
            $em->flush();

            $content = $this->renderView("niveau/item.html.twig", ["niveau" => $niveau,"index"=>$request->get("index")]);
            return new Response($content);
        }

        $content = $this->renderView("niveau/form.html.twig", [
            "form" => $form->createView(),
            "action" => $this->generateUrl("niveau_edit", [
                "id" => $niveau->getId()
            ])
        ]);
        return new Response($content);
    }

    /**
     * @Route("/{id}/delete", name="niveau_delete")
     */
    public function deleteNiveauAction(Niveau $niveau)
    {
        $em = $this->getDoctrine()->getManager();
                $em->remove($niveau);
        $em->flush();

        return new Response(1);
    }
}

