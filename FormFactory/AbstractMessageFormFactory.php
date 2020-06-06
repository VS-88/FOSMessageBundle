<?php
declare(strict_types=1);

namespace FOS\MessageBundle\FormFactory;

use FOS\MessageBundle\FormModel\AbstractMessage;
use FOS\MessageBundle\Model\ThreadInterface;
use InvalidArgumentException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Instanciates message forms.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
abstract class AbstractMessageFormFactory
{
    /**
     * The Symfony form factory.
     *
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * The message form type.
     *
     * @var AbstractType|string
     */
    protected $formType;

    /**
     * The name of the form.
     *
     * @var string
     */
    protected $formName;

    /**
     * The FQCN of the message model.
     *
     * @var string
     */
    protected $messageClass;

    /**
     * AbstractMessageFormFactory constructor.
     * @param FormFactoryInterface $formFactory
     * @param $formType
     * @param $formName
     * @param $messageClass
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        string $formType,
        string $formName,
        string $messageClass
    ) {
        if (!is_string($formType) && !$formType instanceof AbstractType) {
            throw new InvalidArgumentException(sprintf(
                'Form type provided is not valid (class name or instance of %s expected, %s given)',
                'Symfony\Component\Form\AbstractType',
                is_object($formType) ? get_class($formType) : gettype($formType)
            ));
        }

        $this->formFactory = $formFactory;
        $this->formType = $formType;
        $this->formName = $formName;
        $this->messageClass = $messageClass;
    }

    /**
     * @param ThreadInterface $thread
     *
     * @return FormInterface
     */
    abstract public function create(?ThreadInterface $thread = null): FormInterface;

    /**
     * Creates a new instance of the form model.
     *
     * @return AbstractMessage
     */
    protected function createModelInstance(): AbstractMessage
    {
        $class = $this->messageClass;

        return new $class();
    }
}
