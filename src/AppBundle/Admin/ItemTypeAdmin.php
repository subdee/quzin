<?php

namespace AppBundle\Admin;

use AppBundle\Entity\ItemType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Translation\TranslatorInterface;

class ItemTypeAdmin extends AbstractAdmin
{
    protected $translator;
    protected $translationDomain = 'item';

    public function __construct($code, $class, $baseControllerName, TranslatorInterface $translator)
    {
        $this->translator = $translator;
        parent::__construct($code, $class, $baseControllerName);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', 'text');
        $formMapper->add('pluralName', 'text');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name');
    }

    /**
     * @param ItemType $object
     * @return string
     */
    public function toString($object)
    {
        return $object->getName();
    }
}