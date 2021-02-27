<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use FOS\MessageBundle\Model\MessageAttachmentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

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
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * MessageAttachmentDownloadController constructor.
     * @param string $pathToDirWithAttachments
     * @param EntityManagerInterface $em
     * @param string $messageAttachmentEntityClass
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        string $pathToDirWithAttachments,
        EntityManagerInterface $em,
        string $messageAttachmentEntityClass,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->pathToDirWithAttachments = $pathToDirWithAttachments;
        $this->messageAttachmentEntityClass = $messageAttachmentEntityClass;
        $this->em = $em;
        $this->authorizationChecker = $authorizationChecker;
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

        if ($messageAttachment !== null) {
            if ($this->authorizationChecker->isGranted('VIEW', $messageAttachment) === false) {
                throw $this->createAccessDeniedException('Доступен запрещен!');
            }

            $filepath = realpath(
                $this->pathToDirWithAttachments . DIRECTORY_SEPARATOR . $messageAttachment->getFileName()
            );

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
