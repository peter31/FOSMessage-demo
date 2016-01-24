<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\DataTransformer\RecipientsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class RecipientsType extends AbstractType
{
    /**
     * @var RecipientsTransformer
     */
    private $recipientsTransformer;

    /**
     * @param RecipientsTransformer $recipientsTransformer
     */
    public function __construct(RecipientsTransformer $recipientsTransformer)
    {
        $this->recipientsTransformer = $recipientsTransformer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->recipientsTransformer);
    }

    /**
     * @see Symfony\Component\Form\AbstractType::getParent()
     */
    public function getParent()
    {
        return TextType::class;
    }
}
