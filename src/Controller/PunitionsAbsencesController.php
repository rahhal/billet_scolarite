<?php

namespace App\Controller;

use App\Entity\PunitionsAbsences;
use App\Entity\Eleve;
use App\Entity\JourFerie;
use App\Entity\AnneeScolaire;
use App\Service\PunitionsAbsenceService;
use App\Service\GeneraleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/punitions-absences")
 */
class PunitionsAbsencesController extends AbstractController
{
    /**
     * @Route("/notification-punitions-absences", name="notification_punitions_absences")
     */
    public function notificationPunitionsAbsencesAction(PunitionsAbsenceService $PunitionsAbsenceService)
    {
        return $this->render('punitions_absences:notification-punitions-absences.html.twig', [
            'response' => $PunitionsAbsenceService->getNotificationPunitionsAbsences()
        ]);
    }

    /**
     * @Route("/add-expultion", name="add_expultion")
     */
    public function addExpultionAction(PunitionsAbsenceService $PunitionsAbsenceService)
    {
        $em = $this->getDoctrine()->getManager();
        $avertiss = explode(',', $_GET['avertiss']);
        if ($_GET['jours'] == 1)
            $avertiss = array_slice($avertiss, 0, 3);
        if ($_GET['jours'] == 3)
            $avertiss = array_slice($avertiss, 0, 5);

        $punitionsAbsences = new PunitionsAbsences($this->getUser());
        $debut = new \DateTime($_GET['debut']);
        $fin = clone $debut;
        $eleve = $em->find(Eleve::class, $_GET['eleve']);
        $annee = $em->getRepository(AnneeScolaire::class)->findOneBy(['current' => true]);

        $punitionsAbsences
            ->setType(PunitionsAbsences::EXPULSION)
            ->setAnneeScolaire($annee)
            ->setEleve($eleve)
            ->setClasse($eleve->getClasse())
            ->setDateDebut($debut)
            ->setDateFin($fin->modify("+" . ($_GET['jours'] - 1) . " day"))
            ->setRaison("Obtenez " . count($avertiss) . " avertissement");
        $em->persist($punitionsAbsences);

        $expr = $em->getExpressionBuilder();
        $avertissements = $em->createQueryBuilder()
            ->select('pa')
            ->from(PunitionsAbsences::class, 'pa')
            ->where($expr->in('pa.id', $avertiss))
            ->getQuery()->getResult();

        /** @var PunitionsAbsences $avertissement */
        foreach ($avertissements as &$avertissement)
            $avertissement->setPunitionsAbsences($punitionsAbsences);

        $em->flush();
        return new Response(count($PunitionsAbsenceService->getNotificationPunitionsAbsences()));
    }

    /**
     * @Route("/reglement-punitions-absences", name="reglement_punitions_absences")
     */
    public function reglementPunitionsAbsencesAction()
    {
        $em = $this->getDoctrine()->getManager();
        try {
            // $espace_user = !$this->getUser()->hasRole('ROLE_ADMIN');
         //   $espace_user = $this->getUser()->hasRole('ROLE_ADMIN');
         $espace_user = !$this->getUser()->getRoles('ROLE_ADMIN');
            $listePunitionsAbsences = [];
            $ids = explode(',', $_POST['id']);

            foreach ($ids as $id) {
                /** @var PunitionsAbsences $punitionsAbsences */
                $punitionsAbsences = $em->find(PunitionsAbsences::class, $id);
                $punitionsAbsences->setModeReglement($_POST['mode']);
                $punitionsAbsences->setDateFin(new\DateTime($_POST['date']));
                $punitionsAbsences->setType(PunitionsAbsences::BILLET);
                if ($espace_user)
                    $punitionsAbsences->setDateImpression(new\DateTime());
                $listePunitionsAbsences[] = $punitionsAbsences;
            }

            $em->flush();

            if ($espace_user) {
                $content = $this->renderView('impression:billet.html.twig', [
                    'punitions' => $listePunitionsAbsences
                    ]);
                return new JsonResponse(['content' => $content, 'type' => $punitionsAbsences->getType(), 'index' => $ids]);
            }

            $resp = $punitionsAbsences->fullCalendar($this->getParameter('app.color'));
            return new JsonResponse(end($resp));
        } catch (\Exception $exception) {
            dd($exception->getMessage() . " - File: " . $exception->getFile() . " - Line: " . $exception->getLine());
        }
    }

    /**
     * @Route("/events", name="events")
     */
    public function eventsAction(GeneraleService $GeneraleService)
    {
        $em = $this->getDoctrine()->getManager();
        $annee = $em->getRepository(AnneeScolaire::class)->findOneBy(['current' => true]);
        $jourFerie = $GeneraleService->getJoursFeries($annee, $_GET['date']);
        $events = [];
        $id= $this-> getUser()->getId();
        
        if (count($jourFerie) > 0) {
            $eleves = $em->createQueryBuilder()
                ->select("e.id")
                ->from(Eleve::class, 'e')
                ->innerJoin('e.user', 'u')
                ->andWhere('u.id = :id')
                ->andwhere('e.classeAnneeActuelle = :classe')
                ->setParameter('id', $id)
                ->setParameter('classe', $_GET['classe'])
                ->getQuery()->getResult();
            $resourceIds = array_column($eleves, 'id');

            /** @var JourFerie $jf */
            foreach ($jourFerie as $jf)
                $events[] = $jf->fulCalendar($resourceIds);
        }
        $expr = $em->getExpressionBuilder();
        $punitionsAbsences = $em->createQueryBuilder()
            ->select('pa')
            ->from(PunitionsAbsences::class, 'pa')
            ->where('pa.annee_scolaire = :annee')
            ->andWhere('(pa.dateDebut BETWEEN :from AND :to) OR (pa.dateFin Is Null AND pa.type = :typAbsc)')
            ->andWhere($expr->in('pa.type', [PunitionsAbsences::ABSENCE, PunitionsAbsences::EXCLUSION, PunitionsAbsences::BILLET]))
            ->andWhere('pa.classe = :classe')
            ->setParameters([
                'annee' => $annee,
                'classe' => $_GET['classe'],
                'typAbsc' => PunitionsAbsences::ABSENCE,
                'from' => (new\DateTime($_GET['date']))->format('Y-m') . "-01",
                'to' => (new\DateTime($_GET['date']))->format('Y-m-t')
            ]);

        $punitionsAbsences = $punitionsAbsences->getQuery()->getResult();

        /** @var PunitionsAbsences $punitionAbsence */
        foreach ($punitionsAbsences as $punitionAbsence) {
            $events = array_merge($events, $punitionAbsence->fullCalendar($this->getParameter('app.color'), $jourFerie));
        }

        return new JsonResponse($events);
    }

    /**
     * @Route("/delete-event/{id}", name="delete_event")
     */
    public function deleteEventAction(PunitionsAbsences $punitionsAbsences, PunitionsAbsenceService $PunitionsAbsenceService)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($punitionsAbsences);
        $em->flush();
       // var_dump($punitionsAbsences);
       // die('error');
         return new Response(count($PunitionsAbsenceService->getNotificationPunitionsAbsences()));
    }

    /**
     * @Route("/load-punitions-absences", name="load_punitions_absences")
     */
    public function loadPunitionsAbsencesAction(PunitionsAbsenceService $PunitionsAbsenceService)
    {
        //$_GET['classe'] = '1';

        return new Response($this->renderView("punitions_absences/item.html.twig", [
            "punitionsAbsences" => $PunitionsAbsenceService->getPunitionsAbsencesByClasse($_GET['classe'])
        ]));
    }
}
