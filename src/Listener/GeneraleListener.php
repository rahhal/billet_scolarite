<?php

namespace App\Listener;

use App\Entity\Etablissement;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Doctrine\ORM\EntityManager;


class GeneraleListener implements EventSubscriberInterface
{
    public $session;
    public $em;
    private $container;

    public function __construct(SessionInterface $session, EntityManager $em, Container $container)
    {
        $this->session = $session;
        $this->em = $em;
        $this->container=$container;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        // $service=$this->container->get('generale_service');
        // $punitionsAbsence=$this->container->get('punitions_absence_service');

        // $this->session->set('notification-punitions-absences', count($punitionsAbsence->getNotificationPunitionsAbsences()) > 0);

        // if (!$this->session->has('update-soft')) {
        //     $this->session->set('update-soft', false);
        //     $commit = $service->commitsBitbucket();

        //     if ($commit and isset($commit['hash'])) {
        //         exec("git show " . $commit['hash'], $output, $worked);
        //         if (empty($output))
        //             $this->session->set('update-soft', $commit['message']);
        //     }
        // }

        // if (!$this->session->has('check-license')) {
        //     $etab = $this->em->getRepository(Etablissement::class)->findOneBy([]);
        //     $check = false;
        //     if ($etab)
        //         $check = $service->checkCleLicense($etab->getCleLicense());
        //     $this->session->set('check-license', $check);
        // }

        // if (!$this->session->has('code-secret')) {
        //     $serialnumber = shell_exec('wmic bios get serialnumber');
        //     $codeSecret = trim(str_replace("SerialNumber", "", $serialnumber));
        //     //        $MAC = exec('getmac');
        //     //        $MAC = strtok($MAC, ' ');
        //     //        $MAC=shell_exec('wmic DISKDRIVE GET SerialNumber');
        //     $this->session->set('code-secret', $codeSecret);
        // }

        // $route = $request->attributes->get('_route');
        // if (strpos($route, "impression") !== false and !$this->session->get('check-license')) {

        // }

        // // On vérifie si la langue est passée en paramètre de l'URL
        // if ($locale = $request->query->get('_locale')) {
        //     $request->setLocale($locale);
        // } else {
        //     // Sinon on utilise celle de la session
        //     $request->setLocale($request->getSession()->get('_locale', $this->container->getParameter('locale')));
        // }
    }

    public static function getSubscribedEvents()
    {
        return [
            // On doit définir une priorité élevée
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}