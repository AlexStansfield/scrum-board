<?php
namespace Itsallagile\CoreBundle\Form\Type\Team;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;

class Add extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
    }

    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'Itsallagile\CoreBundle\Document\Team');
    }

    public function getName()
    {
        return 'team';
    }
}
