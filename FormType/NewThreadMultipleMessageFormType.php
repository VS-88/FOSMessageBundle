<?php
declare(strict_types=1);

namespace FOS\MessageBundle\FormType;

use FOS\MessageBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Message form type for starting a new conversation with multiple recipients.
 *
 * @author Łukasz Pospiech <zocimek@gmail.com>
 */
class NewThreadMultipleMessageFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recipients', LegacyFormHelper::getType(RecipientsType::class), [
                'label' => 'recipients',
                'translation_domain' => 'FOSMessageBundle',
            ])
            ->add('subject', LegacyFormHelper::getType(TextType::class), [
                'label' => 'subject',
                'translation_domain' => 'FOSMessageBundle',
            ])
            ->add('body', LegacyFormHelper::getType(TextareaType::class), [
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
