<?php
declare(strict_types=1);

namespace FOS\MessageBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Message form type for starting a new conversation.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
abstract class AbstractAttachmentAwareFormType extends AbstractType
{
    public const FORM_CHILD_RECIPIENT   = 'recipient';
    public const FORM_CHILD_SUBJECT     = 'subject';
    public const FORM_CHILD_BODY        = 'body';
    public const FORM_CHILD_ATTACHMENTS = 'attachments';
    public const FORM_CHILD_SAVE        = 'save';

    abstract protected function innerBuild(FormBuilderInterface $builder, array $options): void;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->innerBuild($builder, $options);

        $builder
            ->add(
                self::FORM_CHILD_ATTACHMENTS,
                FileType::class,
                [
                    'label' => 'attachment',
                    'multiple' => true,
                    'attr' => [
                        'accept' => 'image/*',
                        'multiple' => 'multiple'
                    ],
                    'required' => false,
                    'translation_domain' => 'FOSMessageBundle',
                ]
            )
            ->add(
                self::FORM_CHILD_SAVE,
                SubmitType::class,
                [
                    'label' => 'Save',
                    'translation_domain' => 'FOSMessageBundle',
                ]
            );
    }
}
