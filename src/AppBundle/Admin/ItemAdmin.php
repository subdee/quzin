<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Item;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Translation\TranslatorInterface;

class ItemAdmin extends AbstractAdmin
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
        $formMapper->add('name', 'text')
            ->add('type', 'sonata_type_model')
            ->add('imageFile', 'file', ['required' => false]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name')
            ->add('type', 'string', ['template' => '@App/admin/item_type.html.twig']);
    }

    /**
     * @param Item $object
     * @return string
     */
    public function toString($object)
    {
        return $object->getName();
    }

    /**
     * @param Item $object
     */
    public function prePersist($object)
    {
        $this->manageFileUpload($object);
    }

    /**
     * @param Item $object
     */
    public function preUpdate($object)
    {
        $this->manageFileUpload($object);
    }

    /**
     * @param Item $object
     */
    private function manageFileUpload($object)
    {
        if ($object->getImageFile()) {
            $object->refreshUpdated();
        }
    }
}