<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Traits;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Trait TranslatorAwareTrait
 * @package FOS\MessageBundle\Traits
 */
trait TranslatorAwareTrait
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @required
     *
     * @param TranslatorInterface $translator
     *
     * @return $this
     */
    public function setTranslator(TranslatorInterface $translator): self
    {
        $this->translator = $translator;
        
        return $this;
    }

    /**
     * @param string $id
     * @param array $params
     * @param string|null $locale
     *
     * @return string
     */
    protected function translate(string $id, array $params = [], ?string $locale = null): string
    {
        return $this->translator->trans($id, $params, 'FOSMessageBundle', $locale);
    }
}
