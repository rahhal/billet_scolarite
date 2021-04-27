<?php

namespace App\Controller;

use User\Entity\User;
use User\Entity\UserSearch;
use User\Form\UserSearchType;
use User\Form\UserType;
use User\Form\AdminType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route ("/admin")
 *
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index", methods={"GET"})
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AdminType::class, null, ['mode' => 'add']);
        $search = new UserSearch();
        $form_search = $this->createForm(UserSearchType::class, $search);
        $form_search->handleRequest($request);

        $limit = $request->query->getInt("limit", 10);

       // $_users = $em->getRepository(User::class)->findAll();
        $_admins = $em->getRepository(User::class)->findBy(['roles' => 'ROLE_ADMIN']);
        $role = 'ROLE_ADMIN';
        $qb = $em->createQueryBuilder();
        $qb->select('u')
            ->from(User::class, 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"'.$role.'"%')
            ->getQuery()
            ->getResult();
        $admins = $this->get('knp_paginator')
                       ->paginate($qb, $request->query->getInt("page", 1), $limit);

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView("admin/liste.html.twig", ["admins" => $admins]);
            $pagination = $this->renderView(":knp_paginator:pagination_render.html.twig", ["items" => $admins]);
            $countItem = count($_admins);

            return new JsonResponse(compact(["content", "pagination", "data", "countItem"]));
        }

        return $this->render("admin/index.html.twig", [
            "form" => $form->createView(),
            "form_search" => $form_search->createView(),
            "admins" => $admins
        ]);
    }
    /**
     * @Route("/new", name="admin_new", methods={"GET","POST"})
     */
    public function newAction(Request $request)
    {
        $encoder = $this->get('security.password_encoder');
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $form = $this->createForm(AdminType::class, $user, ['mode' => 'add']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($em->getRepository(User::class)->findOneByUsername($user->getUsername()))
                throw new \Exception("Username exist déja!");

            if ($em->getRepository(User::class)->findOneByEmail($user->getEmail()))
                throw new \Exception("Email exist déja!");

            if (method_exists($user, "setTranslatableLocale"))
                $user->setTranslatableLocale($request->getLocale());

            $passEncoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($passEncoded);
            $user->setRoles(['ROLE_ADMIN']);

            $em->persist($user);
            $em->flush();

            $content = $this->renderView("admin/item.html.twig", ["user" => $user]);
            return new Response($content);
        }
    }
    /**
     * @Route("/{id}/edit", name="admin_edit", methods={"GET","POST"})
     */
    public function edit(User $user,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (method_exists($user, "setTranslatableLocale"))
                $user->setTranslatableLocale($request->getLocale());
            $em->flush();

            $content = $this->renderView("admin/item.html.twig", ["user" => $user]);
            return new Response($content);
        }

        $content = $this->renderView("admin/form.html.twig", [
            "form" => $form->createView(),
            "action" => $this->generateUrl("admin_edit", [
                "id" => $user->getId()
            ])
        ]);
        return new Response($content);
    }
    /**
     * @Route("/{id}/delete", name="admin_delete")
     */
    public function delete(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return new Response(1);
    }

}