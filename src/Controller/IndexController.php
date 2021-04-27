<?php

namespace App\Controller;

use App\Entity\PunitionsAbsences;

use App\Entity\AnneeScolaire;
use App\Entity\Eleve;
use App\Entity\Etablissement;
use App\Entity\Classe;
use App\Entity\Niveau;
use App\Form\PunitionsAbsencesType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormBuilder;
use App\Form\EntityType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \PDO;
use \postgresSQL;
use Symfony\Component\DependencyInjection\Container;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactory;
use App\Service\GeneraleService;
use App\Service\PunitionsAbsenceService;

use Symfony\Contracts\Translation\TranslatorInterface;

class IndexController extends AbstractController
{
   

    /**
     * @Route("/check-cle-license", name="check_cle_license")
     */
    public function checkCleLicenseAction()
    {
        $em = $this->getDoctrine()->getManager();
        $check = $this->get('generale_service')->checkCleLicense($_GET['cle']);
        $response = 0;
        if ($check) {
            /** @var Etablissement $etab */
            $etab = $em->getRepository(Etablissement::class)->findOneBy([]);
            $etab->setCleLicense($_GET['cle']);
            $em->flush();
            $this->get('session')->set('check-license', true);
            $response = 1;
        }
        return new Response($response);
    }

    /**
     * @Route("/", name="home")
     */
    public function indexAction( Request $request, GeneraleService $GeneraleService, PunitionsAbsenceService $PunitionsAbsenceService)
    {

        $em = $this->getDoctrine()->getManager();
        

        $espace_user = !$this->getUser()->getRoles('ROLE_ADMIN');//if ROLE_USER
     // $espace_user1 = $this->getUser()->getRoles();//if ROLE_ADMN
       // dump($espace_user);die();
        $colors = $this->getParameter('app.color');
        $annee = $em->getRepository(AnneeScolaire::class)->findOneBy(['current' => true]);
      // dump($espace_user);die();
        if (is_null($annee)) {
            $this->addFlash('danger', 'il faut trouver une année courent');
            return $this->redirectToRoute('anneescolaire_index');
        }

        $punitionsAbsences = new PunitionsAbsences($this->getUser());

        $form_absence = $this->get('form.factory')
                             ->createNamedBuilder("absence", PunitionsAbsencesType::class, $punitionsAbsences, [
                                                        'type' => PunitionsAbsences::ABSENCE,
                                                        'anneeScolaire' => $annee,
                                                        'espace_user' => $espace_user
                                                    ])
                             ->getForm();

        $form_absence->handleRequest($request);
        if ($form_absence->isSubmitted() && $form_absence->isValid()) {
            if ($punitionsAbsences->getNbrJour()) {
                $dateFin = clone $punitionsAbsences->getDateDebut();
                $nbr = $punitionsAbsences->getNbrJour() - 1;
                $punitionsAbsences->setDateFin($dateFin->modify("+$nbr day"));

               // dump($punitionsAbsences);die();
            }

            $listePunitionsAbsences = [];
            if ($request->get('eleves')) {
                foreach ($request->get('eleves') as $eleve) {
                    $_punitionsAbsences = clone $punitionsAbsences;
                    $_punitionsAbsences->setEleve($em->find(Eleve::class, $eleve));
                    if ($espace_user)
                        $_punitionsAbsences
                            ->setDateFin(new\DateTime())
                            ->setDateImpression(new\DateTime());
                    $listePunitionsAbsences[] = $_punitionsAbsences;
                    $em->persist($_punitionsAbsences);
                }
            } else {
                $em->persist($punitionsAbsences);
                $listePunitionsAbsences[] = $punitionsAbsences;
            }

            $em->flush();

             /*if ($espace_user) {
                 $content = $this->renderView('AppBundle:Impression:billet.html.twig', ['punitions' => $listePunitionsAbsences]);
                 return new Response($content);
             }*/
            $content = $this->renderView('impression/billet.html.twig',
                                            ['punitions' => $listePunitionsAbsences]);
            return new Response($content);

            $jourFerie = $this->$GeneraleService->getJoursFeries($annee, $punitionsAbsences->getDateDebut()->format('Y-m-d'));
            return new JsonResponse($punitionsAbsences->fullCalendar($colors, $jourFerie));
        }

        $form_exclusion = $this->get('form.factory')->createNamedBuilder("exclusion", PunitionsAbsencesType::class, $punitionsAbsences, [
            'type' => PunitionsAbsences::EXCLUSION,
            'anneeScolaire' => $annee,
            'espace_user' => $espace_user
        ])->getForm();

        $form_exclusion->handleRequest($request);
        if ($form_exclusion->isSubmitted() && $form_exclusion->isValid()) {

            $listePunitionsAbsences = [];
            if ($request->get('eleves')) {
                foreach ($request->get('eleves') as $eleve) {
                    $_punitionsAbsences = clone $punitionsAbsences;
                    $_punitionsAbsences->setEleve($em->find(Eleve::class, $eleve));
                    if ($espace_user)
                        $_punitionsAbsences
                            ->setDateFin(new\DateTime())
                            ->setDateImpression(new\DateTime());
                    $listePunitionsAbsences[] = $_punitionsAbsences;
                    $em->persist($_punitionsAbsences);
                }
            } else {
                $em->persist($punitionsAbsences);
                $listePunitionsAbsences[] = $punitionsAbsences;
            }

            $em->flush();

            // if ($espace_user) {
            //     $content = $this->renderView('AppBundle:Impression:billet.html.twig', ['punitions' => $listePunitionsAbsences]);
            //   //  $content = $this->renderView("AppBundle:PunitionsAbsences/item.html.twig", ["punitionsAbsences" => $listePunitionsAbsences,'autoImpression'=>true]);
            //     return new Response($content);
            // }
            $content = $this->renderView('impression/billet.html.twig', ['punitions' => $listePunitionsAbsences]);
            //                $content = $this->renderView("AppBundle:PunitionsAbsences/item.html.twig", ["punitionsAbsences" => $listePunitionsAbsences,'autoImpression'=>true]);
                            return new Response($content);
            return new JsonResponse($punitionsAbsences->fullCalendar($colors));
        }

        $form_retard = $this->get('form.factory')->createNamedBuilder("retard", PunitionsAbsencesType::class, $punitionsAbsences, [
            'type' => PunitionsAbsences::RETARD,
            'anneeScolaire' => $annee,
            'espace_user' => $espace_user
        ])->getForm();

        $form_retard->handleRequest($request);
        if ($form_retard->isSubmitted() && $form_retard->isValid()) {

            $listePunitionsAbsences = [];
            if ($request->get('eleves')) {
                foreach ($request->get('eleves') as $eleve) {
                    $_punitionsAbsences = clone $punitionsAbsences;
                    $_punitionsAbsences->setEleve($em->find(Eleve::class, $eleve));
                    if ($espace_user)
                        $_punitionsAbsences
                            ->setDateFin(new\DateTime())
                            ->setDateImpression(new\DateTime());
                    $listePunitionsAbsences[] = $_punitionsAbsences;
                    $em->persist($_punitionsAbsences);
                }
            } else {
                $em->persist($punitionsAbsences);
                $listePunitionsAbsences[] = $punitionsAbsences;
            }

            $em->flush();

            // if ($espace_user) {
            //     $content = $this->renderView('AppBundle:Impression:billet.html.twig', ['punitions' => $listePunitionsAbsences]);
            //     //$content = $this->renderView("AppBundle:PunitionsAbsences/item.html.twig", ["punitionsAbsences" => $listePunitionsAbsences,'autoImpression'=>true]);
            //     return new Response($content);
            // }
            $content = $this->renderView('impression/billet.html.twig', ['punitions' => $listePunitionsAbsences]);
            //$content = $this->renderView("AppBundle:PunitionsAbsences/item.html.twig", ["punitionsAbsences" => $listePunitionsAbsences,'autoImpression'=>true]);
            return new Response($content);
            
            return new JsonResponse($punitionsAbsences->fullCalendar($colors));
        }

        $form_avertissement = $this->get('form.factory')->createNamedBuilder("avertissement", PunitionsAbsencesType::class, $punitionsAbsences, [
            'type' => PunitionsAbsences::AVERTISSEMENT,
            'anneeScolaire' => $annee,
            'espace_user' => $espace_user
        ])->getForm();

        $form_avertissement->handleRequest($request);
        if ($form_avertissement->isSubmitted() && $form_avertissement->isValid()) {
            $em->persist($punitionsAbsences);
            $em->flush();

            return new JsonResponse([
                'notif' => count($this->$PunitionsAbsenceService->getNotificationPunitionsAbsences()) > 0,
                'events' => $punitionsAbsences->fullCalendar($colors)
            ]);
        }

        $form_expulsion = $this->get('form.factory')->createNamedBuilder("expulsion", PunitionsAbsencesType::class, $punitionsAbsences, [
            'type' => PunitionsAbsences::EXPULSION,
            'anneeScolaire' => $annee,
            'espace_user' => $espace_user
        ])->getForm();

        $form_expulsion->handleRequest($request);
        if ($form_expulsion->isSubmitted() && $form_expulsion->isValid()) {
            if ($punitionsAbsences->getNbrJour()) {
                $dateFin = clone $punitionsAbsences->getDateDebut();
                $nbr = $punitionsAbsences->getNbrJour() - 1;
                $punitionsAbsences->setDateFin($dateFin->modify("+$nbr day"));
            }

            $em->persist($punitionsAbsences);
            $em->flush();
            return new JsonResponse($punitionsAbsences->fullCalendar($colors));
        }

        $form_conseil = $this->get('form.factory')->createNamedBuilder("conseil", PunitionsAbsencesType::class, $punitionsAbsences, [
            'type' => PunitionsAbsences::CONSEIL,
            'anneeScolaire' => $annee,
            'espace_user' => $espace_user
        ])->getForm();

        $form_conseil->handleRequest($request);
        if ($form_conseil->isSubmitted() && $form_conseil->isValid()) {
            $em->persist($punitionsAbsences);
            $em->flush();
            return new JsonResponse($punitionsAbsences->fullCalendar($colors));
        }

        $form_elimine = $this->get('form.factory')->createNamedBuilder("elimine", PunitionsAbsencesType::class, $punitionsAbsences, [
            'type' => PunitionsAbsences::ELIMINE,
            'anneeScolaire' => $annee,
            'espace_user' => $espace_user
        ])->getForm();

        $form_elimine->handleRequest($request);
        if ($form_elimine->isSubmitted() && $form_elimine->isValid()) {
            $em->persist($punitionsAbsences);
            $em->flush();
            return new JsonResponse($punitionsAbsences->fullCalendar($colors));
        }

        return $this->render('index/index.html.twig', [
            'form_absence' => $form_absence->createView(),
            'form_exclusion' => $form_exclusion->createView(),
            'form_retard' => $form_retard->createView(),
            'form_avertissement' => $form_avertissement->createView(),
            'form_expulsion' => $form_expulsion->createView(),
            'form_conseil' => $form_conseil->createView(),
            'form_elimine' => $form_elimine->createView()
        ]);
    }

/**
     * @Route("/absences", name="absences")
     */
     public function absencesAction( Request $request, GeneraleService $GeneraleService, PunitionsAbsenceService $PunitionsAbsenceService)
     {
        $em = $this->getDoctrine()->getManager();
          $espace_user = !$this->getUser()->getRoles('ROLE_ADMIN');//if ROLE_USER

         //dump($espace_user);die();
         $colors = $this->getParameter('app.color');
         $annee = $em->getRepository(AnneeScolaire::class)->findOneBy(['current' => true]);
         if (is_null($annee)) {
             $this->addFlash('danger', 'il faut trouver une année courent');
             return $this->redirectToRoute('anneescolaire_index');
         }
 
         $punitionsAbsences = new PunitionsAbsences($this->getUser());
 
         $form_absence = $this->get('form.factory')
                              ->createNamedBuilder("absence", PunitionsAbsencesType::class, $punitionsAbsences, [
                                                         'type' => PunitionsAbsences::ABSENCE,
                                                         'anneeScolaire' => $annee,
                                                         'espace_user' => $espace_user
                                                     ])
                              ->getForm();
 
         $form_absence->handleRequest($request);
         if ($form_absence->isSubmitted() && $form_absence->isValid()) {
             if ($punitionsAbsences->getNbrJour()) {
                 $dateFin = clone $punitionsAbsences->getDateDebut();
                 $nbr = $punitionsAbsences->getNbrJour() - 1;
                 $punitionsAbsences->setDateFin($dateFin->modify("+$nbr day"));
 
                // dump($punitionsAbsences);die();
             }
 
             $listePunitionsAbsences = [];
             if ($request->get('eleves')) {
                 foreach ($request->get('eleves') as $eleve) {
                     $_punitionsAbsences = clone $punitionsAbsences;
                     $_punitionsAbsences->setEleve($em->find(Eleve::class, $eleve));
                     if ($espace_user)
                         $_punitionsAbsences
                             ->setDateFin(new\DateTime())
                             ->setDateImpression(new\DateTime())
                             ->setDateImpression(new\DateTime());
                     $listePunitionsAbsences[] = $_punitionsAbsences;
                     $em->persist($_punitionsAbsences);
                 }
             } else {
                 $em->persist($punitionsAbsences);
                 $listePunitionsAbsences[] = $punitionsAbsences;
             }
 
             $em->flush();
 
             if ($espace_user) {
                 $content = $this->renderView('impression:billet.html.twig', ['punitions' => $listePunitionsAbsences]);
                 return new Response($content);
             }
 
             $jourFerie = $GeneraleService->getJoursFeries($annee, $punitionsAbsences->getDateDebut()->format('Y-m-d'));
             return new JsonResponse($punitionsAbsences->fullCalendar($colors, $jourFerie));
         }
 
         $form_exclusion = $this->get('form.factory')->createNamedBuilder("exclusion", PunitionsAbsencesType::class, $punitionsAbsences, [
             'type' => PunitionsAbsences::EXCLUSION,
             'anneeScolaire' => $annee,
             'espace_user' => $espace_user
         ])->getForm();
 
         $form_exclusion->handleRequest($request);
         if ($form_exclusion->isSubmitted() && $form_exclusion->isValid()) {
 
             $listePunitionsAbsences = [];
             if ($request->get('eleves')) {
                 foreach ($request->get('eleves') as $eleve) {
                     $_punitionsAbsences = clone $punitionsAbsences;
                     $_punitionsAbsences->setEleve($em->find(Eleve::class, $eleve));
                     if ($espace_user)
                         $_punitionsAbsences
                             ->setDateFin(new\DateTime())
                             ->setDateImpression(new\DateTime());
                     $listePunitionsAbsences[] = $_punitionsAbsences;
                     $em->persist($_punitionsAbsences);
                 }
             } else {
                 $em->persist($punitionsAbsences);
                 $listePunitionsAbsences[] = $punitionsAbsences;
             }
 
             $em->flush();
 
           if ($espace_user) {
                 $content = $this->renderView('impression:billet.html.twig', ['punitions' => $listePunitionsAbsences]);
 //                $content = $this->renderView("AppBundle:PunitionsAbsences/item.html.twig", ["punitionsAbsences" => $listePunitionsAbsences,'autoImpression'=>true]);
                 return new Response($content);
             }
 
             return new JsonResponse($punitionsAbsences->fullCalendar($colors));
         }
 
        /* $form_retard = $this->get('form.factory')->createNamedBuilder("retard", PunitionsAbsencesType::class, $punitionsAbsences, [
             'type' => PunitionsAbsences::RETARD,
             'anneeScolaire' => $annee,
             'espace_user' => $espace_user
         ])->getForm();
 
         $form_retard->handleRequest($request);
         if ($form_retard->isSubmitted() && $form_retard->isValid()) {
 
             $listePunitionsAbsences = [];
             if ($request->get('eleves')) {
                 foreach ($request->get('eleves') as $eleve) {
                     $_punitionsAbsences = clone $punitionsAbsences;
                     $_punitionsAbsences->setEleve($em->find(Eleve::class, $eleve));
                     if ($espace_user)
                         $_punitionsAbsences
                             ->setDateFin(new\DateTime())
                             ->setDateImpression(new\DateTime());
                     $listePunitionsAbsences[] = $_punitionsAbsences;
                     $em->persist($_punitionsAbsences);
                 }
             } else {
                 $em->persist($punitionsAbsences);
                 $listePunitionsAbsences[] = $punitionsAbsences;
             }
 
             $em->flush();
 */
           /*  if ($espace_user) {
                 $content = $this->renderView('AppBundle:Impression:billet.html.twig', ['punitions' => $listePunitionsAbsences]);
                 //$content = $this->renderView("AppBundle:PunitionsAbsences/item.html.twig", ["punitionsAbsences" => $listePunitionsAbsences,'autoImpression'=>true]);
                 return new Response($content);
             }*/
 /*
             return new JsonResponse($punitionsAbsences->fullCalendar($colors));
         }
 */
         $form_avertissement = $this->get('form.factory')->createNamedBuilder("avertissement", PunitionsAbsencesType::class, $punitionsAbsences, [
             'type' => PunitionsAbsences::AVERTISSEMENT,
             'anneeScolaire' => $annee,
             'espace_user' => $espace_user
         ])->getForm();
 
         $form_avertissement->handleRequest($request);
         if ($form_avertissement->isSubmitted() && $form_avertissement->isValid()) {
             $em->persist($punitionsAbsences);
             $em->flush();
 
             return new JsonResponse([
                 'notif' => count($PunitionsAbsenceService
                         ->getNotificationPunitionsAbsences()) > 0,
                 'events' => $punitionsAbsences->fullCalendar($colors)
             ]);
         }
 
         $form_expulsion = $this->get('form.factory')->createNamedBuilder("expulsion", PunitionsAbsencesType::class, $punitionsAbsences, [
             'type' => PunitionsAbsences::EXPULSION,
             'anneeScolaire' => $annee,
             'espace_user' => $espace_user
         ])->getForm();
 
         $form_expulsion->handleRequest($request);
         if ($form_expulsion->isSubmitted() && $form_expulsion->isValid()) {
             if ($punitionsAbsences->getNbrJour()) {
                 $dateFin = clone $punitionsAbsences->getDateDebut();
                 $nbr = $punitionsAbsences->getNbrJour() - 1;
                 $punitionsAbsences->setDateFin($dateFin->modify("+$nbr day"));
             }
 
             $em->persist($punitionsAbsences);
             $em->flush();
             return new JsonResponse($punitionsAbsences->fullCalendar($colors));
         }
 
         $form_conseil = $this->get('form.factory')->createNamedBuilder("conseil", PunitionsAbsencesType::class, $punitionsAbsences, [
             'type' => PunitionsAbsences::CONSEIL,
             'anneeScolaire' => $annee,
             'espace_user' => $espace_user
         ])->getForm();
 
         $form_conseil->handleRequest($request);
         if ($form_conseil->isSubmitted() && $form_conseil->isValid()) {
             $em->persist($punitionsAbsences);
             $em->flush();
             return new JsonResponse($punitionsAbsences->fullCalendar($colors));
         }
 
         $form_elimine = $this->get('form.factory')->createNamedBuilder("elimine", PunitionsAbsencesType::class, $punitionsAbsences, [
             'type' => PunitionsAbsences::ELIMINE,
             'anneeScolaire' => $annee,
             'espace_user' => $espace_user
         ])->getForm();
 
         $form_elimine->handleRequest($request);
         if ($form_elimine->isSubmitted() && $form_elimine->isValid()) {
             $em->persist($punitionsAbsences);
             $em->flush();
             return new JsonResponse($punitionsAbsences->fullCalendar($colors));
         }
    /*$ident=$em->getRepository(PunitionsAbsences::class)
     ->getListPunition();*/
         $qb = $em->createQueryBuilder()
         ->select('pa.id')
             ->from(PunitionsAbsences::class, 'pa')
             ->Andwhere('pa.type =:r')
             ->setParameter('r', 'RETARD')
             ->getQuery()
             ->getResult();
        // dump($qb);die();
         return $this->render('index/index1.html.twig', [
             'form_absence' => $form_absence->createView(),
             'form_exclusion' => $form_exclusion->createView(),
            // 'form_retard' => $form_retard->createView(),
             'form_avertissement' => $form_avertissement->createView(),
             'form_expulsion' => $form_expulsion->createView(),
             'form_conseil' => $form_conseil->createView(),
             'form_elimine' => $form_elimine->createView(),
             'ident' => $qb
         ]);
     }
 


    /**
     * @Route("/export-database", name="export_database")
     */
    public function exportDataBaseAction()
    {
        $mysqlDatabaseName = 'db_scolarite';
        $mysqlUserName = 'root';
    $mysqlExportPath = 'c:\\filesSQL/dip-scolarite.sql';


        if (file_exists($mysqlExportPath))
            unlink($mysqlExportPath);

        exec("mysqldump -u$mysqlUserName $mysqlDatabaseName > $mysqlExportPath", $output, $worked);

        $response = $this->get('translator')->trans("Une erreur s est produite lors de l'exportation") . "\n" . implode('<br>', $output);
        if ($worked == 0)
            $response = 1;
        else {
        
            $response = $this->get('translator')
           ->trans("l'operation est terminée avec succé ") . "\n" . implode('<br>', $output);

        }
        return new Response($response);
    }

    /**
     * @Route("/import-database", name="import_database")
     */
    public function importDataBaseAction()
    {
        $target_dir = "filesSQL/";
        $mysqlImportFilename = $target_dir . basename($_FILES["fileSQL"]["name"]);
        $imageFileType = strtolower(pathinfo($mysqlImportFilename, PATHINFO_EXTENSION));

        if (file_exists($mysqlImportFilename))
            unlink($mysqlImportFilename);

        if ($imageFileType != "sql")
            return new JsonResponse([
                'status' => 'danger',
                'msg' => $this->get('translator')->trans('Désolé, seuls les fichiers SQL sont autorisés')
            ]);

        if (!move_uploaded_file($_FILES["fileSQL"]["tmp_name"], $mysqlImportFilename))
            return new JsonResponse([
                'status' => 'danger',
                'msg' => $this->get('translator')->trans("Désolé, une erreur s'est produite lors du l'importation de votre fichier.")
            ]);
        else {
            return new JsonResponse([
                'status' => 'success',
                'msg' => $this->get('translator')->trans("L'importation de votre fichier est terminée avec success.")
            ]);
        }

        $mysqlDatabaseName = 'db_scolarite';
        $mysqlUserName = 'root';
        $mysqlImportFilename = realpath($mysqlImportFilename);

        exec("mysql -u$mysqlUserName $mysqlDatabaseName < $mysqlImportFilename", $output, $worked);

        $response = [
            'status' => 'danger',
            'msg' => $this->get('translator')->trans('Une erreur est produite lors de l importation') . "\n" . implode('<br>', $output)
        ];

        if ($worked == 0)
            $response = [
                'status' => 'success',
                'msg' => $this->get('translator')->trans('Les données du fichier <b>%mysqlImportFilename%</b>ont été importées avec succès dans la base de données.', ['mysqlImportFilename' => $mysqlImportFilename])
            ];

        return new JsonResponse($response);
    }

    /**
     * @Route("/update-database", name="update_database")
     */
    public function updateDataBaseAction(GeneraleService $GeneraleService, TranslatorInterface $translator)
    {
      /*   $filePHP = basename($_FILES["eduservePHP"]["name"]);
        $imageFileType = strtolower(pathinfo($filePHP, PATHINFO_EXTENSION));

        if (file_exists($filePHP))
            unlink($filePHP);

        if ($imageFileType != "php")
            return new JsonResponse([
                'status' => 'danger',
                'msg' => $this->get('translator')->trans('Désolé, seuls les fichiers PHP sont autorisés')
            ]);

        $response = [
            'status' => 'danger',
            'msg' => $this->get('translator')->trans("Désolé, une erreur s'est produite lors du l'importation de votre fichier.")
        ];

        if (!move_uploaded_file($_FILES["eduservePHP"]["tmp_name"], "eduserve.php"))
            if (!file_exists("C:\\eduserve.php"))
                return new JsonResponse($response); */

         if (!file_exists("C:\\eduserve.php")) {
            $this->addFlash('danger', $translator ->trans("Veuillez vérifier l'existence d'un fichier 'eduserve.php'  dans C:\\"));
            return $this->redirect("/");
        } 


        $result = $GeneraleService->uploadData();
        if (isset($result['Error']))
            $this->addFlash('danger', $result['Error']);
        else
            $this->addFlash('success', $translator->trans("Opération terminée avec succès"));


        return $this->redirect("/");
    }

    /**
     * @Route("/change_locale/{locale}", name="change_locale")
     */
    public function changeLocaleAction($locale, Request $request)
    {
        $request->getSession()->set('_locale', $locale);
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/traduction", name="traduction")
     */
    public function traductionAction(Request $request)
    {
        if ($request->isMethod('POST'))
            file_put_contents($this->getParameter('kernel.project_dir') . '/translations/messages+intl-icu.ar.yaml', Yaml::dump($request->get('transAR')));
        $translations = Yaml::parse(file_get_contents($this->getParameter('kernel.project_dir') . '/translations/messages+intl-icu.ar.yaml'));
        return $this->render('traduction.html.twig', array('translations' => $translations));
    }

    /**
     * @Route("/genere-cle-license", name="genere_cle_license")
     */
    public function genereCleLicenseAction(Request $request)
    {
        return new Response($this->get('generale_service')->genereCleLicense($request->get('code')));
    }

    /**
     * @Route("/git-pull", name="git_pull")
     */
    public function gitPullAction( )
    {
       
    }

    function getVerPHP($base)
    {
        // if ($dir = opendir($base))
        //     while ($entry = readdir($dir))
        //         if (is_dir($base . "/" . $entry) && !in_array($entry, array('.', '..')) and strpos($entry, "php7.") !== false)
        //             return $entry;
    }

    /**
     * @Route("/env/{env}", name="set_environement")
     */
    public function setEnvironementAction($env)
    {
        // $envSrc = ".env.local";
        // $envDest = ".env.local0";
        // if ($env == 'prod') {
        //     $envSrc = ".env.local0";
        //     $envDest = ".env.local";
        // }
        // if (file_exists($envSrc) and !file_exists($envDest))
        //     rename($envSrc, $envDest);
        // return new Response(1);
    }
}
