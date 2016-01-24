<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\Model\StartConversationModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class StartConversationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recipients', RecipientsType::class, [
                'label' => 'Recipients (separated by commas, for instance: "alice, jenn")'
            ])
            ->add('subject', TextType::class, [
                'label' => 'Conversation subject',
                'required' => false,
            ])
            ->add('body', TextareaType::class, [
                'label' => 'First message content',
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Form\Model\StartConversationModel'
        ));
    }
}
