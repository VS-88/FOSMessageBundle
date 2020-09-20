<?php
declare(strict_types=1);

namespace FOS\MessageBundle\FormType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Message form type for starting a new conversation with multiple recipients.
 *
 * @author Łukasz Pospiech <zocimek@gmail.com>
 */
class NewThreadMultipleMessageFormType extends AbstractAttachmentAwareFormType
{
    public const FORM_CHILD_RECIPIENTS = 'recipients';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    protected function innerBuild(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(self::FORM_CHILD_RECIPIENTS, RecipientsType::class, [
                'label' => 'recipients',
                'translation_domain' => 'FOSMessageBundle',
            ])
            ->add(self::FORM_CHILD_SUBJECT, TextType::class, [
                'label' => 'subject',
                'translation_domain' => 'FOSMessageBundle',
            ])
            ->add(self::FORM_CHILD_BODY, TextareaType::class, [
                'label' => 'body',
                'translation_domain' => 'FOSMessageBundle',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fos_message_new_multiperson_thread';
    }
}
