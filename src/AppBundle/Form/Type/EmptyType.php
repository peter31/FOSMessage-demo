<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class EmptyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*
         * This empty form is useful to prevent CSRF attacks
         * (it creates a token system).
         */
    }
}
