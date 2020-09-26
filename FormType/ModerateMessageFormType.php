<?php
declare(strict_types=1);

namespace FOS\MessageBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ModerateMessageFormType
 */
class ModerateMessageFormType extends AbstractType
{
    public const FORM_CHILD_IS_APPROVED = 'isApproved';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                self::FORM_CHILD_IS_APPROVED,
                CheckboxType::class,
                ['label' => 'Approve (Yes/No)', 'required' => false]
            )
            ->add('Save', SubmitType::class, ['label' => 'Сохранить',]);
    }
}
