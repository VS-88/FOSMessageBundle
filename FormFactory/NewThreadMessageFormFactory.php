<?php
declare(strict_types=1);

namespace FOS\MessageBundle\FormFactory;

use FOS\MessageBundle\Model\ThreadInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Instanciates message forms.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class NewThreadMessageFormFactory extends AbstractMessageFormFactory
{
    /**
     * {@inheritDoc}
     */
    public function create(?ThreadInterface $thread = null): FormInterface
    {
        return $this->formFactory->createNamed($this->formName, $this->formType, $this->createModelInstance());
    }
}
