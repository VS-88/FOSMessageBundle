<?php
declare(strict_types=1);

namespace FOS\MessageBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ModerateMessageFormType
 */
class ModerateMessageFormType extends AbstractType
{
    public const FORM_CHILD_DECLINE     = 'Decline';
    public const FORM_CHILD_APPROVE     = 'Approve';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                self::FORM_CHILD_DECLINE,
                SubmitType::class,
                [
                    'label' => 'Decline',
                    'translation_domain' => 'FOSMessageBundle',
                ]
            )
            ->add(
                self::FORM_CHILD_APPROVE,
                SubmitType::class,
                [
                    'label' => 'Approve',
                    'translation_domain' => 'FOSMessageBundle',
                ]
            );
    }
}
