Spam detection
==============

Using Akismet
-------------

Install AkismetBundle (https://github.com/ornicar/OrnicarAkismetBundle).

Then, set the spam detector service accordingly::

```yaml
# app/config/config.yml

fos_message:
    spam_detector: fos_message.akismet_spam_detector
```

Other strategies
----------------

You can use any spam detector service, including one of your own, provided the
class implements ``FOS\MessageBundle\SpamDetection\SpamDetectorInterface``.

Realization:
<?php
declare(strict_types=1);

namespace FOS\MessageBundle\SpamDetection;

use FOS\MessageBundle\FormModel\NewThreadMessage;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use Ornicar\AkismetBundle\Akismet\AkismetInterface;

class AkismetSpamDetector implements SpamDetectorInterface
{
    /**
     * @var AkismetInterface
     */
    protected $akismet;

    /**
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    /**
     * AkismetSpamDetector constructor.
     * @param AkismetInterface $akismet
     * @param ParticipantProviderInterface $participantProvider
     */
    public function __construct(AkismetInterface $akismet, ParticipantProviderInterface $participantProvider)
    {
        $this->akismet = $akismet;
        $this->participantProvider = $participantProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function isSpam(NewThreadMessage $message): bool
    {
        return $this->akismet->isSpam([
            'comment_author' => (string) $this->participantProvider->getAuthenticatedParticipant(),
            'comment_content' => $message->getBody(),
        ]);
    }
}


[Return to the documentation index](00-index.md)
