<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\PunitionsAbsences;
use App\Entity\AnneeScolaire;
use App\Entity\Niveau;
use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\Etablissement;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\GeneraleService;


/**
 * @Route("/impression")
 */
class ImpressionController extends AbstractController
{
    /**
     * @Route("/punition", name="impression_punition")
     * https://wkhtmltopdf.org/usage/wkhtmltopdf.txt
     */
    public function impressionPunition(EntityManagerInterface $em, \Knp\Snappy\Pdf $snappy, GeneraleService $GeneraleService)
    {
        $user=$this->getUser();
       // $snappy = $this->get('knp_snappy.pdf');
       // $em = $this->getDoctrine()->getManager();

        $etab = $em->getRepository(Etablissement::class)
             ->findOneBy(['user' => $this->getUser()->getId()]);
        $options = [
//            'header-html' => $this->renderView('impression:header.html.twig'),
            'user-style-sheet' => realpath('assets/css/impression.css')
        ];
        $expr = $em->getExpressionBuilder();

        switch ($_GET['etat']) {
            //--------------------------------carte eleve------------
            case "carte-eleve":
               // $etab = $em->getRepository(Etablissement::class)->findOneBy([]);
                $em->flush();
                $param = [
                    'eleve' => $em->find(Eleve::class, $_GET['form']['eleve'])
                ];
                /** @var Etablissement $etab */
                break;
             //--------------------------------engagement------------
             case "engagement":
              //  $etab = $em->getRepository(Etablissement::class)->findOneBy([]);
                $em->flush();
                $param = [
                    'eleve' => $em->find(Eleve::class, $_GET['form']['eleve'])
                ];
                /** @var Etablissement $etab */
                break;
                 //--------------------------------questionnaire------------
             case "questionnaire":
               // $etab = $em->getRepository(Etablissement::class)->findOneBy([]);
                $em->flush();
                $param = [
                    'eleve' => $em->find(Eleve::class, $_GET['form']['eleve'])
                ];
                /** @var Etablissement $etab */
                break;
                 //--------------------------------convocation parent------------
             case "convocation-parent":
               // $etab = $em->getRepository(Etablissement::class)->findOneBy([]);
                $em->flush();
                $param = [
                    'eleve' => $em->find(Eleve::class, $_GET['form']['eleve'])
                ];
                /** @var Etablissement $etab */
                break;
                 //--------------------------------convocation eleve------------
             case "convocation-eleve":
              //  $etab = $em->getRepository(Etablissement::class)->findOneBy([]);
                $em->flush();
                $param = [
                    'eleve' => $em->find(Eleve::class, $_GET['form']['eleve'])
                ];
                /** @var Etablissement $etab */
                break;
                 //--------------------------------seance supplimentaire------------
             case "seance-supplimentaire":
              //  $etab = $em->getRepository(Etablissement::class)->findOneBy([]);
                $em->flush();
                $param = [
                    'eleve' => $em->find(Eleve::class, $_GET['form']['eleve'])
                ];
                /** @var Etablissement $etab */
                break;
            //-------------------------------attestation-presence---------------------------
            case "attestation-presence":
               // $etab = $em->getRepository(Etablissement::class)->findOneBy([]);
                $etab->setNbrAttestationPresence((int)$etab->getNbrAttestationPresence() + 1);
                $em->flush();
                $param = [
                    'eleve' => $em->find(Eleve::class, $_GET['form']['eleve']),
                    'raison' => $_GET['raison']
                ];
                /** @var Etablissement $etab */
                break;
            //-------------------------------absence-non-reglee---------------------------
            case "absence-non-reglee":
                $listPunitionsAbsences = $em->createQueryBuilder()
                    ->select('pa')
                    ->from(PunitionsAbsences::class, 'pa')
                    ->innerJoin('pa.created_by', 'u')
                    ->andWhere('pa.created_by = :user')
                    ->andWhere('pa.date_debut = :date')
                    ->andWhere($expr->in('pa.type', [PunitionsAbsences::ABSENCE]))
                    ->setParameter('user', $user)
                    ->setParameter('date', $_GET['jour'])
                    ->getQuery()->getResult();
                $param = ['punitions' => $listPunitionsAbsences];
                unset($options['header-html']);
                break;
                //------------------------carte renseignement-------------
            case "carte-renseignement":
               // $etab = $em->getRepository(Etablissement::class)->findOneBy([]);
                $em->flush();
                $param = [
                    'eleve' => $em->find(Eleve::class, $_GET['form']['eleve'])
                ];

                /** @var Etablissement $etab */
              break;
            //-------------------------------rapport-journalier---------------------------
            case "rapport-journalier":
                $listPunitionsAbsences = $em->createQueryBuilder()
                    ->select('pa')
                    ->from(PunitionsAbsences::class, 'pa')
                    ->innerJoin('pa.created_by', 'u')
                    ->andWhere('pa.created_by = :user')
                    ->andWhere('pa.date_debut = :date')
                    ->andWhere($expr->in('pa.type', [PunitionsAbsences::ABSENCE, PunitionsAbsences::EXCLUSION, PunitionsAbsences::EXPULSION]))
                    ->setParameter('user', $user)
                    ->setParameter('date', $_GET['jour'])
                    ->getQuery()->getResult();
                $annee = $em->getRepository(AnneeScolaire::class)->findOneBy(['current' => true]);
               $jourFerie = $GeneraleService->getJoursFeries($annee);
                $param = ['punitions' => $listPunitionsAbsences, 'jour' => new\DateTime($_GET['jour']), 'jourFerie' => $jourFerie];
             // unset($options['header-html']);
                $options['orientation'] = 'Landscape';
                break;
            default:
                /** @var PunitionsAbsences $punition */
                $punition = $em->find(PunitionsAbsences::class, $_GET['id']);
                switch ($_GET['etat']) {
                    case 'notification-absence1':
                        $punition->setDateNotification1(new\DateTime());
                        $em->flush();
                        break;
                    case 'notification-absence2':
                        $punition->setDateNotification2(new\DateTime());
                        $em->flush();
                        break;
                    case 'notification-absence3':
                        $punition->setDateNotification3(new\DateTime());
                        $em->flush();
                        break;
                }
                $param = ['punition' => $punition];
        }

        return $this->render("impression/{$_GET['etat']}.html.twig", $param);
        $html = $this->renderView("impression/{$_GET['etat']}.html.twig", $param);
        $filename = $_GET['etat'];

        /*$options = [
            'header-html' => $this->renderView('Impression/header.html.twig', [
                'anneescolaire' => false,
                'etablissement' => false
            ]),
            'footer-html' => $this->renderView('Impression/footer.html.twig'),
            'footer-right' => '[page]/[toPage]',
            'footer-font-size' => '11',
        ];*/

        $session = $this->get('session');

        // if (!$session->get('check-license'))
        //     $html = '<!doctype html>
        //                 <html lang="fr">
        //                 <head>
        //                     <meta charset="UTF-8">
        //                     <meta name="viewport"
        //                           content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        //                     <meta http-equiv="X-UA-Compatible" content="ie=edge">
        //                     <title>Code Secret</title>
        //                 </head>
        //                 <body>
        //                     <p>Envoyer le code secret  <b style=\'color:blue\'>' . $session->get('code-secret') . '</b> au service technique de DIP pour génére votre propre clé de license</p>
        //                 </body>
        //                 </html>';


        /*$htd = new \HTML_TO_DOC();
        $htd->createDoc($html, "my-document", 1);
        return new Response(" ");
        $file = "uploads/" . $filename . ".docx";
        $txt = fopen($file, "w") or die("Unable to open file!");
        fwrite($txt, $html);
        fclose($txt);
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        header("Content-Type: application/vnd.ms-word");
        readfile($file);
        unlink($file);
        return new Response(" ");*/
        return new Response(
            $snappy->getOutputFromHtml($html, $options),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '.pdf"'
            )
        );
    }

    /**
     * @Route("/print-billet-entree/{id}", defaults={"id"=null}, name="print_billet_entree")
     */
    public function printBilletEntree(PunitionsAbsences $punitionsAbsences = null, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $listPunitionsAbsences = [];
        if ($request->get('ids')) {
            foreach ($request->get('ids') as $id) {
                $_punitionsAbsences = $em->find(PunitionsAbsences::class, $id);
                $_punitionsAbsences->setDateImpression(new \DateTime());
                $listPunitionsAbsences[] = $_punitionsAbsences;
            }
        } else {
            $punitionsAbsences->setDateImpression(new \DateTime());
            $listPunitionsAbsences[] = $punitionsAbsences;
        }

        $em->flush();
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($this->renderView('impression:billet.html.twig', ['punitions' => $listPunitionsAbsences]));
        return $response;
    }

     /**
     * @Route("/document", name="impression_document")
     * https://wkhtmltopdf.org/usage/wkhtmltopdf.txt
     */
     public function impressionDocument(EntityManagerInterface $em, \Knp\Snappy\Pdf $snappy, GeneraleService $GeneraleService)
     {
        // $snappy = $this->get('knp_snappy.pdf');
        // $em = $this->getDoctrine()->getManager();
         $etab = $em->getRepository(Etablissement::class)
             ->findOneBy(['user' => $this->getUser()->getId()]);
         $options = [
 //            'header-html' => $this->renderView('impression:header.html.twig'),
             'user-style-sheet' => realpath('assets/css/impression.css')
         ];
         $expr = $em->getExpressionBuilder();
 
         switch ($_GET['etat']) {
             //--------------------------------
             case "proposition-avertissement":
               //  $etab = $em->getRepository(Etablissement::class)->findOneBy([]);
                 $em->flush();
                    
                    /** @var Etablissement $etab */
                 break;
             case "demande-parent":
                   // $etab = $em->getRepository(Etablissement::class)->findOneBy([]);
                    $em->flush();
                       
                       /** @var Etablissement $etab */
                    break;
             case "rapport":
                       // $etab = $em->getRepository(Etablissement::class)->findOneBy([]);
                        $em->flush();
                           
                           /** @var Etablissement $etab */
                        break;
             case "accident":
                          //  $etab = $em->getRepository(Etablissement::class)->findOneBy([]);
                            $em->flush();
                               
                               /** @var Etablissement $etab */
                            break;

             default:


                 $id=249;
                 /** @var PunitionsAbsences $punition */
                 $s= null;
                 $punition = $em->createQueryBuilder()
                      ->select('pa')
                      ->from(PunitionsAbsences::class, 'pa')
                     ->innerJoin('pa.eleve','e')
                      ->andWhere('e.id = :id')
                    // ->andWhere('pa.modeReglement = :s')
                      ->setParameter('id', $id)
                   //  ->setParameter('s', $s)
                      ->getQuery()->getResult();
                // dump($punition);die();
                 switch ($_GET['etat']) {
                     case 'notification-absence1':
                        // $punition->setDateNotification1(new\DateTime());
                         $em->flush();
                         break;
                     case 'notification-absence2':
                        // $punition->setDateNotification2(new\DateTime());
                         $em->flush();
                         break;
                     case 'notification-absence3':
                        // $punition->setDateNotification3(new\DateTime());
                         $em->flush();
                         break;
                 }
                 $param = ['punition' => $punition];
            //  case "absence-non-reglee":
            //      $listPunitionsAbsences = $em->createQueryBuilder()
            //          ->select('pa')
            //          ->from(PunitionsAbsences::class, 'pa')
            //          ->where('pa.dateDebut = :date')
            //          ->andWhere($expr->in('pa.type', [PunitionsAbsences::ABSENCE]))
            //          ->setParameter('date', $_GET['jour'])
            //          ->getQuery()->getResult();
            //      $param = ['punitions' => $listPunitionsAbsences];
            //      unset($options['header-html']);
            //      break;
            //  case "rapport-journalier":
            //      $listPunitionsAbsences = $em->createQueryBuilder()
            //          ->select('pa')
            //          ->from(PunitionsAbsences::class, 'pa')
            //          ->where('pa.dateDebut = :date')
            //          ->andWhere($expr->in('pa.type', [PunitionsAbsences::ABSENCE, PunitionsAbsences::EXCLUSION, PunitionsAbsences::EXPULSION]))
            //          ->setParameter('date', $_GET['jour'])
            //          ->getQuery()->getResult();
            //      $annee = $em->getRepository(AnneeScolaire::class)->findOneBy(['current' => true]);
            //      $jourFerie = $this->get('generale_service')->getJoursFeries($annee);
            //      $param = [ 'jour' => new\DateTime($_GET['jour']), 'jourFerie' => $jourFerie];
            //      unset($options['header-html']);
            //      $options['orientation'] = 'Landscape';
            //      break;
         }
 
         return $this->render("impression/{$_GET['etat']}.html.twig");
         $html = $this->renderView("impression/{$_GET['etat']}.html.twig");
         $filename = $_GET['etat'];






         $session = $this->get('session');
 
       
         return new Response(
             $snappy->getOutputFromHtml($html, $options),
             200,
             array(
                 'Content-Type' => 'application/pdf',
                 'Content-Disposition' => 'inline; filename="' . $filename . '.pdf"'
             )
         );
     }
}
