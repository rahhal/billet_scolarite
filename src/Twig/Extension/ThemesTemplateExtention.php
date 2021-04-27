<?php

namespace App\Twig\Extension;

use Twig\Environment;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class ThemesTemplateExtention extends AbstractExtension
{
    protected $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function getFunctions()
    {
        return array(
            //===================== MACROS =================================
            new TwigFunction('macro_boutton', [$this, 'macro_boutton']),
            new TwigFunction('macro_bouttonBlank', [$this, 'macro_bouttonBlank']),
            new TwigFunction('macro_bouttonModal', [$this, 'macro_bouttonModal']),
            new TwigFunction('macro_bouttonModalTow', [$this, 'macro_bouttonModalTow']),
            new TwigFunction('macro_tablePassword', [$this, 'macro_tablePassword']),
            new TwigFunction('macro_tableReload', [$this, 'macro_tableReload']),
            new TwigFunction('macro_saisonAmicaleGenerer', [$this, 'macro_saisonAmicaleGenerer']),
            new TwigFunction('macro_tableValider', [$this, 'macro_tableValider']),
            new TwigFunction('macro_tableStopSales', [$this, 'macro_tableStopSales']),
            new TwigFunction('macro_tableDelete', [$this, 'macro_tableDelete']),
            new TwigFunction('macro_tableClone', [$this, 'macro_tableClone']),
            new TwigFunction('macro_generate', [$this, 'macro_generate']),
            new TwigFunction('macro_generateTarif', [$this, 'macro_generateTarif']),
            new TwigFunction('macro_returnPiece', [$this, 'macro_returnPiece']),
            new TwigFunction('macro_tableEdit', [$this, 'macro_tableEdit']),
            new TwigFunction('macro_addLigneToEncaissement', [$this, 'macro_addLigneToEncaissement']),
            new TwigFunction('macro_tablePdf', [$this, 'macro_tablePdf']),
            new TwigFunction('macro_tablePhoto', [$this, 'macro_tablePhoto']),
            new TwigFunction('macro_albumPhoto', [$this, 'macro_albumPhoto']),
            new TwigFunction('macro_bouttonValider', [$this, 'macro_bouttonValider']),
            new TwigFunction('macro_bouttonFacturer', [$this, 'macro_bouttonFacturer']),
            new TwigFunction('macro_bouttonImpayer', [$this, 'macro_bouttonImpayer']),
            new TwigFunction('macro_bouttonSupprimer', [$this, 'macro_bouttonSupprimer']),
            new TwigFunction('macro_bouttonAnnuler', [$this, 'macro_bouttonAnnuler']),
            new TwigFunction('macro_bouttonPaiement', [$this, 'macro_bouttonPaiement']),
            new TwigFunction('macro_bouttonModifier', [$this, 'macro_bouttonModifier']),
            new TwigFunction('macro_bouttonRecu', [$this, 'macro_bouttonRecu']),
            new TwigFunction('macro_bouttonPriseCharge', [$this, 'macro_bouttonPriseCharge']),
            new TwigFunction('macro_PayerPiece', [$this, 'macro_PayerPiece']),
            //=============== FORM ============================================
            new TwigFunction('form_text', [$this, 'form_text']),
            new TwigFunction('form_file', [$this, 'form_file']),
            new TwigFunction('form_read_only', [$this, 'form_read_only']),
            new TwigFunction('form_input', [$this, 'form_input']),
            new TwigFunction('form_select2', [$this, 'form_select2']),
            new TwigFunction('form_submit', [$this, 'form_submit']),
            new TwigFunction('form_submit2', [$this, 'form_submit2']),
            new TwigFunction('form_formPhoto', [$this, 'form_formPhoto']),
            new TwigFunction('form_htmlInput', [$this, 'form_htmlInput']),
            new TwigFunction('form_htmlDate', [$this, 'form_htmlDate']),
            new TwigFunction('form_checkbox', [$this, 'form_checkbox']),
            new TwigFunction('form_uploadImage', [$this, 'form_uploadImage']),
            //================== Template =====================================
            new TwigFunction('tpl_wizard', [$this, 'tpl_wizard']),
            new TwigFunction('tpl_subcontent', [$this, 'tpl_subcontent']),
            new TwigFunction('tpl_subHeader', [$this, 'tpl_subHeader']),
        );
    }

    //===================== MACROS =================================
    public function macro_boutton($path, $icon, $color, $texte, $alert = null)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'icon' => $icon, 'color' => $color, 'texte' => $texte, 'alert' => $alert, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_bouttonBlank($path, $icon, $color, $texte)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'icon' => $icon, 'color' => $color, 'texte' => $texte, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_bouttonModal($id, $path, $icon, $color, $texte)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['id' => $id, 'path' => $path, 'icon' => $icon, 'color' => $color, 'texte' => $texte, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_bouttonModalTow($id, $path, $icon, $color, $texte, $action)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['id' => $id, 'path' => $path, 'icon' => $icon, 'color' => $color, 'texte' => $texte, 'action' => $action, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_tablePassword($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_tableReload($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_saisonAmicaleGenerer($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_tableValider($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_tableStopSales($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_tableDelete($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_tableClone($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_generate($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_generateTarif($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_returnPiece($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_tableEdit($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_addLigneToEncaissement($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_tablePdf($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_tablePhoto($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_albumPhoto($image)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['image' => $image, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_bouttonValider($path, $label = 'réservation')
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'label' => $label, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_bouttonFacturer($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_bouttonImpayer($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_bouttonSupprimer($path, $label = 'réservation')
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'label' => $label, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_bouttonAnnuler($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_bouttonPaiement($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_bouttonModifier($path, $label)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'label' => $label, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_bouttonRecu($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_bouttonPriseCharge($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function macro_PayerPiece($path)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['path' => $path, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    //=============== FORM ============================================
    public function form_text($class, $label, $name, $value = null, $required = true)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['class' => $class, 'label' => $label, 'name' => $name, 'value' => $value, 'required' => $required, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function form_file($class, $label, $from, $required=false)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['class' => $class, 'label' => $label, 'from' => $from, 'required' => $required, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function form_read_only($class, $label, $from, $required=false)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['class' => $class, 'label' => $label, 'from' => $from, 'required' => $required, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function form_input($class, $label, $from, $smallText = null, $value = null,$changeId=false, $required=false)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['class' => $class, 'label' => $label, 'from' => $from,'changeId' => $changeId, 'required' => $required, 'smallText' => $smallText, 'value' => $value, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function form_select2($class, $label, $from, $smallText = null, $selectAll = false, $required=false)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['class' => $class, 'label' => $label, 'from' => $from, 'required' => $required, 'smallText' => $smallText, 'selectAll' => $selectAll, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function form_submit($label, $load_ajax = false)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['label' => $label, 'load_ajax' => $load_ajax, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function form_submit2($name, $value, $class)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['name' => $name, 'value' => $value, 'class' => $class, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function form_formPhoto($form, $images)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['form' => $form, 'images' => $images, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function form_htmlInput($class, $label, $name, $placeholder = null, $required = false)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['class' => $class, 'label' => $label, 'name' => $name, 'required' => $required, 'placeholder' => $placeholder, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function form_htmlDate($class, $label, $name, $placeholder = null, $required = false)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['class' => $class, 'label' => $label, 'name' => $name, 'required' => $required, 'placeholder' => $placeholder, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function form_checkbox($class, $label, $from, $required=false)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['class' => $class, 'label' => $label, 'from' => $from, 'required' => $required, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function form_uploadImage($class, $label, $form, $plusieur = false, $type_image = null, $ordre = true, $smallText = null, $required = false)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['class' => $class, 'label' => $label, 'form' => $form, 'plusieur' => $plusieur, 'type_image' => $type_image, 'ordre' => $ordre, 'required' => $required, 'smallText' => $smallText, 'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    //========================== Template =======================================

    public function tpl_wizard($titre,$etape1,$etatEtape1,$etape2,$etatEtape2,$etape3,$etatEtape3,$etape4,$etatEtape4=null)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",[ 'titre'=>$titre,'etape1'=>$etape1,'etatEtape1'=>$etatEtape1,'etape2'=>$etape2,'etatEtape2'=>$etatEtape2,'etape3'=>$etape3,'etatEtape3'=>$etatEtape3,'etape4'=>$etape4,'etatEtape4'=>$etatEtape4,'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function tpl_subcontent($titre,$sousTitre,$textScore1,$valueScore1,$textScore2,$valueScore2)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",[ 'titre'=>$titre,'sousTitre'=>$sousTitre,'textScore1'=>$textScore1,'valueScore1'=>$valueScore1,'textScore2'=>$textScore2,'valueScore2'=>$valueScore2,'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

    public function tpl_subHeader($titre,$wiz,$subc,$image)
    {
        $rawString = $this->twig->render("Generale/themes_template.html.twig",['titre'=>$titre,'wiz'=>$wiz,'subc'=>$subc,'image'=>$image,'typeMacro' => __FUNCTION__]);
        return new \Twig\Markup($rawString, 'UTF-8');
    }

}