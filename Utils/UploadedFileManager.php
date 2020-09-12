<?php
declare(strict_types=1);

namespace FOS\MessageBundle\Utils;

use Ramsey\Uuid\UuidFactoryInterface;

/**
 * Class UploadedFileManager
 *
 * @package App\Utils
 */
class UploadedFileManager implements UploadedFileManagerInterface
{
    /**
     * @var UuidFactoryInterface
     */
    private $uuidFactory;

    /**
     * UploadedFileManager constructor.
     *
     * @param UuidFactoryInterface $uuidFactory
     */
    public function __construct(UuidFactoryInterface $uuidFactory)
    {
        $this->uuidFactory = $uuidFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function moveToDirAndReturnFileNames(
        array $uploadedFiles,
        string $resultFilePrefix,
        string $dirToMoveTo,
        string $id = ''
    ): array {
        $result = [];

        foreach ($uploadedFiles as $uploadedFile) {
            $ext = $uploadedFile->getClientOriginalExtension();

            $newFileName = $resultFilePrefix;

            if (empty($id) === false) {
                $newFileName .= '-' . $id;
            }

            $newFileName .= '-' . $this->uuidFactory->uuid4()->toString();

            $newFileName = strtolower($newFileName);
            $newFileName = preg_replace('/[^a-z-0-9-]/', '', $newFileName);

            if ($ext) {
                $newFileName .= ".{$ext}";
            }

            $uploadedFile->move($dirToMoveTo, $newFileName);

            $result[] = $newFileName;
        }

        return $result;
    }
}
