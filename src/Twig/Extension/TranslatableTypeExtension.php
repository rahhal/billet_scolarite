<?php

namespace App\Twig\Extension;

//require_once __DIR__ . '/../../../vendor/autoload.php';

use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

use Twig\TwigFunction;
use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class TranslatableTypeExtension extends AbstractExtension
{
    /**
     * @var EntityManagerInterface
     */
    private $om;

    /**
     * @var TranslatableListener
     */
    private $listener;

    /**
     * @param EntityManagerInterface $om
     */
    public function __construct(EntityManagerInterface $om, TranslatableListener $listener)
    {
        $this->om = $om;
        $this->listener = $listener;
    }

    public function getFunctions()
    {
        return array(
            //===================== MACROS =================================
            new TwigFunction('isTranslatableField', [$this, 'isTranslatableField'])
        );
    }

    public function getFilters()
    {
        return [
            new TwigFilter('lng', [$this, 'langueTaduction']),
        ];
    }

    public function isTranslatableField($object, $name)
    {
        try {
            $config = $this->listener->getConfiguration($this->om, get_class($object));

            if (isset($config['fields']) && in_array($name, $config['fields']))
                return true;
            return false;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($form->getParent() == null)
            return;

        if (is_object($form->getParent()->getData())) {
            if ($this->isTranslatableField($form->getParent()->getData(), $form->getName()))
                $view->vars['field_translatable'] = true;
        }
    }

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return 'field';
    }
}