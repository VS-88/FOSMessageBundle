<?php
declare(strict_types=1);

namespace FOS\MessageBundle\FormType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Message form type for starting a new conversation.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class NewThreadMessageFormType extends AbstractAttachmentAwareFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    protected function innerBuild(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(self::FORM_CHILD_RECIPIENT, RecipientType::class, [
                'label' => 'recipient',
                'translation_domain' => 'FOSMessageBundle',
            ])
            ->add(self::FORM_CHILD_SUBJECT, TextType::class, [
                'label' => 'subject',
                'translation_domain' => 'FOSMessageBundle',
            ])
            ->add(self::FORM_CHILD_BODY, TextareaType::class, [
                'label' => 'body',
                'translation_domain' => 'FOSMessageBundle',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'intention' => 'message',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): ?string
    {
        return 'fos_message_new_thread';
    }
}
