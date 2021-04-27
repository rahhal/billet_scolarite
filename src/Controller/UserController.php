<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Entity\Search\UserSearch;
use App\Form\Search\UserSearchType;
use App\Form\UserType;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_user_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserType::class, null, ['mode' => 'add']);
        $search = new UserSearch();
        $form_search = $this->createForm(UserSearchType::class, $search);
        $form_search->handleRequest($request);

        $limit = $request->query->getInt("limit", 10);

        $_users = $em->getRepository(User::class)->findAll();
        // $role = 'ROLE_ADMIN';
        $role = 'ROLE_USER';
        $qb = $em->createQueryBuilder();
        $qb->select('u')
            ->from(User::class, 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"'.$role.'"%')
            ->getQuery()
            ->getResult();
        $users = $paginator->paginate($qb, $request->query->getInt("page", 1), $limit);

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView("user/liste.html.twig", ["users" => $users]);
            $pagination = $this->renderView("knp_paginator/pagination_render.html.twig", ["items" => $users]);
            $countItem = count($_users);

            return new JsonResponse(compact(["content", "pagination", "data", "countItem"]));
        }

        return $this->render("user/index.html.twig", [
            "form" => $form->createView(),
            "form_search" => $form_search->createView(),
            "users" => $users
        ]);
    }

    /**
     * @Route("/new", name="user_user_new", methods={"GET","POST"})
     */
    public function newAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
       // $encoder = $this->get('security.password_encoder');
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['mode' => 'add']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($em->getRepository(User::class)->findOneByUsername($user->getUsername()))
                throw new \Exception("Username exist déja!");

            if ($em->getRepository(User::class)->findOneByEmail($user->getEmail()))
                throw new \Exception("Email exist déja!");

            if (method_exists($user, "setTranslatableLocale"))
                $user->setTranslatableLocale($request->getLocale());

            // $passEncoded = $encoder->encodePassword($user, $user->getPassword());
            $passEncoded = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($passEncoded);
          //  $user->setRoles(['ROLE_USER']);
          $user->setRoles(['ROLE_ADMIN']);
            $em->persist($user);
            $em->flush();

            $content = $this->renderView("user/item.html.twig", ["user" => $user]);
            return new Response($content);
        }
    }

    /**
     * @Route("/{id}/edit", name="user_user_edit", methods={"GET","POST"})
     */
    public function edit(User $user,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (method_exists($user, "setTranslatableLocale"))
                $user->setTranslatableLocale($request->getLocale());
            $em->flush();

            $content = $this->renderView("user/item.html.twig", ["user" => $user]);
            return new Response($content);
        }

        $content = $this->renderView("user/form.html.twig", [
            "form" => $form->createView(),
            "action" => $this->generateUrl("user_user_edit", [
                "id" => $user->getId()
            ])
        ]);
        return new Response($content);
    }

    /**
     * @Route("/{id}/delete", name="user_user_delete")
     */
    public function delete(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return new Response(1);
    }
}
