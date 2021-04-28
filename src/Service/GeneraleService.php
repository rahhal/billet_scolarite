<?php

namespace App\Service;

use App\Entity\Etablissements;
use App\Entity\Niveau;
use App\Entity\Section;
use App\Entity\AnneeScolaire;
use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\JourFerie;
use App\Entity\Gouvernorat;
use App\Entity\Matiere;
use App\Entity\Nationalite;
use App\Entity\Enseignant;
use App\Entity\Etablissement;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use \PDO;
class GeneraleService
{
    private $em;
    private $container;
    private $ciphering = 'AES-128-CTR';// Store the cipher method
    private $encryption_iv = '0734221652719037';// Non-NULL Initialization Vector for encryption


    /**
     * GeneraleExtension constructor.
     * @param $em
     * @param $container
     */
    public function __construct(EntityManager $em, Container $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    function array_group_by($key, $array)
    {
        return array_reduce($array, function (array $accumulator, array $element) use ($key) {
            $accumulator[$element[$key]][] = $element;

            return $accumulator;
        }, []);
    }

    public function getJoursFeries($annee, $date = null)
    {
        $joursFeries = $this->em->createQueryBuilder()
            ->select('jour_ferie')
            ->from(JourFerie::class, 'jour_ferie')
            ->where('jour_ferie.annee_scolaire = :annee')
            ->setParameter('annee', $annee);

        if ($date)
            $joursFeries
                ->andWhere("Not (jour_ferie.debut > :to Or jour_ferie.fin < :from)")
                ->setParameter('from', (new\DateTime($date))->format('Y-m') . "-01")
                ->setParameter('to', (new\DateTime($date))->format('Y-m-t'));

        return $joursFeries
            ->orderBy('jour_ferie.debut', 'ASC')
            ->getQuery()->getResult();
    }

    public function getDataPG($table, $save = false)
    {
         $eduserve = include("C:\\eduserve.php");
        return $eduserve[$table]; 
    }
    function uploadData()
    { 
        
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
       $id= $user->getId();
       
       $gouvernorat = self::getDataPG('gouvernorat');
       //var_dump($gouvernorat);die();

       /*if (isset($gouvernorat['Error']))
           return ['Error' => $gouvernorat['Error']];*/

       foreach ($gouvernorat as $item) {
           $gouvernorat = $this->em->getRepository(Gouvernorat::class)->findOneByCode($item['codegouv']);
           if (is_null($gouvernorat))
               $gouvernorat = new Gouvernorat();

           $gouvernorat->setCode($item['codegouv'])
               ->setLibelleAR($item['libegouvar'])
               ->setLibelle($item['libegouvfr'])
               ->setLibelleFR($item['libegouvfr']);
           $this->em->persist($gouvernorat);
       }
       $this->em->flush();
       
       //==============================================================================================================
       foreach (self::getDataPG('sectense') as $item) {
           
           $section = $this->em->getRepository(Section::class)
                           //->findOneByCode($item['codesect']);
                            // ->getSectionBycurentUser($item['codesect'], $id);
                            ->findOneByUser($user);
                       
           if (is_null($section))
               $section = new Section();

           $section->setCode($item['codesect'])
               ->setLibelleAR($item['libesectar'])
               ->setLibelleFR($item['libesectfr'])
               ->setUser($user);
           $this->em->persist($section);
       }
       $this->em->flush();
       
       //==============================================================================================================
       foreach (self::getDataPG('nivescol') as $item) {
           $niveau = $this->em->getRepository(Niveau::class)
                               // ->findOneByCode($item['codenive']);
                               // ->getNiveauBycurentUser($item['codenive'], $id);
                               ->findOneByUser($user);
           if (is_null($niveau))
               $niveau = new Niveau();

           $niveau->setCode($item['codenive'])
               ->setSection($this->em->getRepository(Section::class)->findOneByCode($item['codesect']))
               ->setLibelle($item['libenivear'])
               ->setNiveauEtude($item['niveetud'])
               ->setUser($user);
           $this->em->persist($niveau);
       }
       //==============================================================================================================
       foreach (self::getDataPG('nationalite') as $item) {
           $nationalite = $this->em->getRepository(Nationalite::class)->findOneByCode($item['codenati']);
           if (is_null($nationalite))
               $nationalite = new Nationalite();

           $nationalite->setCode($item['codenati'])
               ->setLibelleAR($item['libenatiar'])
               ->setLibelleFR($item['libenatifr'])
               ->setLibelleCourtAR($item['libecournatiar'])
               ->setCodeBAC($item['codenatibac']);
           $this->em->persist($nationalite);
       }
       //==============================================================================================================
        foreach (self::getDataPG('matiere') as $item) {
           $matiere = $this->em->getRepository(Matiere::class)
                          //->findOneByCode($item['codemati']);
                           //  ->getMatiereBycurentUser($item['codemati'], $id);
                           ->findOneByUser($user);

           if (is_null($matiere))
               $matiere = new Matiere();

           $matiere->setCode($item['codemati'])
               ->setLibelle($item['libematiar'])
               ->setLibelleFR($item['libematifr'])
               ->setUser($user);
           $this->em->persist($matiere);
       }
       $this->em->flush(); 
       
       //==============================================================================================================
       $affeclas = self::getDataPG('affeclas');
       $affeclas = self::array_group_by('iuense', $affeclas);

       $current_enseignant = $this->em->getRepository(Enseignant::class)->findBy(['user' => $user]);

       foreach (self::getDataPG('enseignant') as $item) {
           $enseignant = $this->em->getRepository(Enseignant::class)
                               // ->findOneByCode($item['iuense']);
                               // ->getEnseignantBycurentUser($item['iuense'], $id);
                               ->findOneByUser($user);

                               //var_dump(isset($affeclas[$item['iuense']]));die();

           if (is_null($enseignant)) {
           
               if (false !== isset($affeclas[$item['iuense']])) {
                   $enseignant = new Enseignant();
                   $enseignant
                   ->setCode($item['iuense'])
                   ->setNom($item['prenensear'] . " " . $item['nomensear'])
                   ->setUser($user)
                   ->setMatiere($this->em->getRepository(Matiere::class)->findOneByCode($affeclas[$item['iuense']][0]['codemati']));

                  $this->em->persist($enseignant);
               }
           }
       }

       $this->em->flush();

       //==============================================================================================================
        foreach (self::getDataPG('etablissement') as $item) {
           $etablissements = $this->em->getRepository(Etablissements::class)->findOneBy([]);
           if (is_null($etablissements))
               $etablissements = new Etablissements();

           $etablissements->setCode($item['codeetab'])
               ->setLibelle($item['libeetabar'])
               ->setGouvernorat($this->em->getRepository(Gouvernorat::class)->findOneByCode($item['codegouv']));
           $this->em->persist($etablissements); 
       }
       
       $this->em->flush(); 
       //==============================================================================================================
       //==============================================================================================================

       //==============================================================================================================
        foreach (self::getDataPG('classe') as $item) {
           $classe = $this->em->getRepository(Classe::class)
                               //->findOneByCode($item['codeclas']);
                               //  ->getClasseBycurentUser($item['codeclas'], $id);
                               ->findOneByUser($user);

           if (is_null($classe))
               $classe = new Classe();

           $classe
               ->setCode($item['codeclas'])
               ->setNiveau($this->em->getRepository(Niveau::class)->findOneByCode($item['codenive']))
               ->setLibelle($item['libeclasar'])
               ->setUser($user);
           $this->em->persist($classe);
       }
       $this->em->flush(); 

       //==============================================================================================================
       $inscription = self::getDataPG('inscription');
       $inscription = array_column($inscription, null, 'idenelev');
       //dump($inscription);die();

       foreach (self::getDataPG('eleve') as $item) {
          
           $eleve = $this->em->getRepository(Eleve::class)
                       //->findOneByIdentifiant($item['idenelev']);
                        ->findOneByUser($user);
           if (is_null($eleve))
               $eleve = new Eleve();

           $eleve->setIdentifiant($item['idenelev'])
               ->setNomprenom($item['prenelevar'] . " " . $item['nomelevar'])
               ->setDateNaissance(new \DateTime($item['datenaiselev']))
               ->setLieuNaissance($item['lieunaiselev'])
               ->setNationalite($this->em->getRepository(Nationalite::class)->findOneByCode($item['codenati']))
               ->setNomPere($item['prenpere'])
               ->setSexe($item['codesexe'])
               ->setUser($user)
           ;
           
           if (isset($inscription[$item['idenelev']]))
               $eleve
                   ->setNumOrdre($inscription[$item['idenelev']]['numeordr'])
                   ->setClasseAnneeActuelle($this->em->getRepository(Classe::class)->findOneByCode($inscription[$item['idenelev']]['codeclas']))
                   ->setClasseAnneeDerniere($this->em->getRepository(Classe::class)->findOneByCode($inscription[$item['idenelev']]['codeclas']))
                   ;
           //dump($this->em->getRepository(Nationalite::class)->findOneByCode($item['codenati']));die();
           $this->em->persist($eleve);
            
       }

       $this->em->flush();

    }
    
}

