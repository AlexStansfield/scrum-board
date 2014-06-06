<?php
namespace Itsallagile\CoreBundle\Form\Type\Team;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;

class AddUser extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Build the choices array from the users
        $choices = array();
        if (isset($options['data']['users'])) {
            foreach ($options['data']['users'] as $user) {
                $choices[$user->getId()] = $user->getFullName();
            }
        }

        // Create the form
        $builder->add('add-user', 'choice', array(
            'empty_value' => '--- Add a User ---',
            'choices' => $choices,
            'label' => false
        ))
            ->add('add', 'submit', array(
                'attr' => array('class' => 'btn')
            ));
    }

    public function getName()
    {
        return 'teamAddUser';
    }
}
