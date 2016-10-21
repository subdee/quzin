<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Item;
use AppBundle\Helpers\ItemTypeTranslator;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Translation\DataCollectorTranslator;

class ItemAdmin extends AbstractAdmin
{
    protected $translator;
    protected $translationDomain = 'item';

    public function __construct($code, $class, $baseControllerName, DataCollectorTranslator $translator)
    {
        $this->translator = $translator;
        parent::__construct($code, $class, $baseControllerName);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', 'text')
            ->add('type', 'choice', [
                'choices' => $this->getTypesList()
            ])
            ->add('image', 'file', ['required' => false]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name')
            ->add('type', null, [], 'choice', [
                'choices' => $this->getTypesList()
            ]);
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

    private function getTypesList()
    {
        return [
            $this->translator->trans(ItemTypeTranslator::translate(Item::TYPE_VEGETABLE)) => Item::TYPE_VEGETABLE,
            $this->translator->trans(ItemTypeTranslator::translate(Item::TYPE_FRUIT)) => Item::TYPE_FRUIT,
            $this->translator->trans(ItemTypeTranslator::translate(Item::TYPE_HERB)) => Item::TYPE_HERB,
            $this->translator->trans(ItemTypeTranslator::translate(Item::TYPE_NUT)) => Item::TYPE_NUT,
        ];
    }
}