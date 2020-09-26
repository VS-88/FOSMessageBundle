<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use FOS\MessageBundle\Model\MessageAttachmentInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\HeaderUtils;

/**
 * Class MessageAttachmentDownloadController
 */
class MessageAttachmentDownloadController extends AbstractController
{
    /**
     * @var string
     */
    private $pathToDirWithAttachments;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     */
    private $messageAttachmentEntityClass;

    /**
     * MessageAttachmentDownloadController constructor.
     * @param string $pathToDirWithAttachments
     * @param EntityManagerInterface $em
     * @param string $messageAttachmentEntityClass
     */
    public function __construct(
        string $pathToDirWithAttachments,
        EntityManagerInterface $em,
        string $messageAttachmentEntityClass
    ) {
        $this->pathToDirWithAttachments = $pathToDirWithAttachments;
        $this->messageAttachmentEntityClass = $messageAttachmentEntityClass;
        $this->em = $em;
    }

    /**
     * @param int $messageAttachmentId
     *
     * @return Response
     */
    public function indexAction(int $messageAttachmentId): Response
    {
        /**
         * @var MessageAttachmentInterface $messageAttachment
         */
        $messageAttachment = $this->em->find($this->messageAttachmentEntityClass, $messageAttachmentId);

        /**
         * @var ParticipantInterface $user
         */
        $user = $this->getUser();

        $thread = $messageAttachment->getMessage()->getThread();

        if ($thread->isParticipant($user) === false) {
            throw $this->createAccessDeniedException();
        }

        if ($messageAttachment !== null) {
            $filepath = $this->pathToDirWithAttachments . DIRECTORY_SEPARATOR . $messageAttachment->getFileName();

            $response = new BinaryFileResponse($filepath);

            $disposition = HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_ATTACHMENT,
                $messageAttachment->getFileName()
            );

            $response->headers->set('Content-Disposition', $disposition);
        } else {
            throw $this->createNotFoundException();
        }

        return $response;
    }
}
