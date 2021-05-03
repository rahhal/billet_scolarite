<?php

namespace App\Controller;

use App\Entity\PunitionsAbsences;
use Doctrine\ORM\EntityRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Niveau;
use App\Entity\Section;
use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\Search\EleveSearch;
use App\Form\Search\EleveSearchType;
use App\Form\EleveType;
use Knp\Component\Pager\PaginatorInterface;

use App\Service\PunitionsAbsenceService;
/**
 * @Route("/eleve")
 */
class EleveController extends AbstractController
{
    /**
     * @Route("/", name="eleve_index", methods={"GET"})
     */
    public function indexEleveAction(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EleveType::class, null);
        $search = new EleveSearch();
        $form_search = $this->createForm(EleveSearchType::class, $search);
        $form_search->handleRequest($request);

        $sort = $request->query->get("sort", "id");
        $order = $request->query->get("order", "DESC");
        $limit = $request->query->getInt("limit", 10);
        $id=$this->getUser()->getId();

        $_eleves = $em->getRepository(Eleve::class)->getListEleve($search, $id, $sort, $order);
      //  $paginator=$this->get('knp_paginator');
        $eleves = $paginator->paginate($_eleves, $request->query->getInt("page", 1), $limit);
                        

        if ($request->isXmlHttpRequest()) {

            $content = $this->renderView("eleve/liste.html.twig", ["eleves" => $eleves]);
            $pagination = $this->renderView("knp_paginator/pagination_render.html.twig", ["items" => $eleves]);
            $countItem = count($_eleves);

            // return new JsonResponse(compact(["content", "pagination", "data", "countItem"]));
            return new JsonResponse(compact(["content", "pagination", "countItem"]));

        }

        return $this->render("eleve/index.html.twig", [
            "form" => $form->createView(),
            "form_search" => $form_search->createView(),
            "eleves" => $eleves
        ]);
    }

    /**
     * @Route("/new", name="eleve_new", methods={"GET","POST"})
     */
    public function newEleveAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $eleve = new Eleve();
        $form = $this->createForm(EleveType::class, $eleve);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (method_exists($eleve, "setTranslatableLocale"))
                $eleve->setTranslatableLocale($request->getLocale());
            $user = $this->getUser();
            $eleve->setUser($user);

            $em->persist($eleve);
            $em->flush();

            $content = $this->renderView("eleve/item.html.twig", ["eleve" => $eleve]);
            return new Response($content);
        }
    }

    /**
     * @Route("/{id}/edit", name="eleve_edit", methods={"GET","POST"})
     */
    public function editEleveAction(Request $request, Eleve $eleve)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EleveType::class, $eleve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (method_exists($eleve, "setTranslatableLocale"))
                $eleve->setTranslatableLocale($request->getLocale());
            $em->flush();

            $content = $this->renderView("eleve/item.html.twig", ["eleve" => $eleve]);
            return new Response($content);
        }

        $content = $this->renderView("eleve/form.html.twig", [
            "form" => $form->createView(),
            "action" => $this->generateUrl("eleve_edit", [
                "id" => $eleve->getId()
            ])
        ]);
        return new Response($content);
    }

    /**
     * @Route("/{id}/delete", name="eleve_delete")
     */
    public function deleteEleveAction(Eleve $eleve)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($eleve);
        $em->flush();

        return new Response(1);
    }

    /**
     * @Route("/eleves", name="eleves")
     */
    public function elevesAction(PunitionsAbsenceService $PunitionsAbsenceService)
    {
        $em = $this->getDoctrine()->getManager();
        if(isset($_GET['filtre'])){
            $punitionsAbsenceByClasse = $PunitionsAbsenceService ->getPunitionsAbsencesByClasse($_GET['classe']);
            $ids = [];
            /** @var PunitionsAbsences $item */
            foreach ($punitionsAbsenceByClasse as $item)
                if ($item->getType() == PunitionsAbsences::ABSENCE)
                    $ids[] = $item->getEleve()->getId();
                   
                    $id= $this-> getUser()->getId();

            $eleves = $em->createQueryBuilder()
                ->select("e")
                ->from(Eleve::class, 'e')
                ->innerJoin('e.user', 'u')
                ->andWhere('u.id = :id')
                ->andwhere('e.classeAnneeActuelle = :classe')
                ->setParameter('classe', $_GET['classe'])
                ->setParameter('id', $id)
                ->orderBy('e.num_ordre', 'ASC');
            if (!empty($ids))
                $eleves = $eleves->andWhere($eleves->expr()->notIn('e.id', $ids));

            $_eleves=[];
            /** @var Eleve $eleve */
            foreach ($eleves->getQuery()->getResult() as $eleve)
                $_eleves[]=[
                    'id'=>$eleve->getId(),
                    'x'=>'<input type="checkbox" class="styled chk-eleves" value="'.$eleve->getId().'">',
                    'ordre'=>$eleve->getNumOrdre(),
                    'eleve'=>$eleve->getNomprenom(),
                ];

            return new JsonResponse($_eleves);
        }
        $id= $this-> getUser()->getId();

        $eleves = $em->createQueryBuilder()
            ->select("e.id , CONCAT(e.num_ordre, ' - ', e.nomprenom) As title")
            ->from(Eleve::class, 'e')
            ->innerJoin('e.user', 'u')
             ->andWhere('u.id = :id')
            ->andwhere('e.classeAnneeActuelle = :classe')
            ->setParameter('classe', $_GET['classe'])
            ->setParameter('id', $id)
            ->orderBy('e.num_ordre', 'ASC')
            ->getQuery()->getResult();
        return new JsonResponse($eleves);
    }

    public function formFiltreEleveAction(Request $request)
    {
        $user= $this->getUser();
        $form = $this->createFormBuilder();
        if ($request->get('section'))
            $form->add('section', EntityType::class, [
                'class' => Section::class,
                'query_builder' => function (EntityRepository $er) use ($user)  {
                    return $er->createQueryBuilder('s')
                        ->join('s.niveaux', 'n')
                        ->join('n.classes', 'c')
                        ->join('c.eleves', 'e')
                        ->join('e.user', 'u')
                        ->andWhere('e.user = :user')
                        ->setParameter('user', $user);
                },
                "required" => true,
                "attr" => [
                    "class" => "select-search"
                ]
            ]);

        $form->add('niveau', EntityType::class, [
            'class' => Niveau::class,
            'query_builder' => function (EntityRepository $er) use ($user) {
                return $er->createQueryBuilder('n')
                    ->join('n.classes', 'c')
                    ->join('c.eleves', 'e')
                    ->join('e.user', 'u')
                    ->andWhere('e.user = :user')
                    ->setParameter('user', $user);

            },
            "required" => true,
            "attr" => [
                "class" => "select-search"
            ]
        ])
            ->add('classe', EntityType::class, [
                'class' => Classe::class,
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('c')
                        ->join('c.eleves', 'e')
                        ->join('e.user', 'u')
                        ->andWhere('e.user = :user')
                        ->setParameter('user', $user);

                },
                "required" => true,
                "attr" => [
                    "class" => "select-search"
                ]
            ]);

        if ($request->get('eleve'))
            $form->add('eleve', EntityType::class, [
                'class' => Eleve::class,
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('e')
                        ->join('e.user', 'u')
                        ->andWhere('e.user = :user')
                        ->setParameter('user', $user);
    
                },
                "required" => true,
                "attr" => [
                    "class" => "select-search"
                ]
            ]);

        return $this->render("eleve/form-filtre-eleve{$request->get('version')}.html.twig", [
            'idDiv' => $request->get('idDiv'),
            'mdSection' => $request->get('mdSection'),
            'mdNiveau' => $request->get('mdNiveau'),
            'mdClasse' => $request->get('mdClasse'),
            'mdEleve' => $request->get('mdEleve'),
            'form' => $form->getForm()->createView(),
        ]);
    }
}
