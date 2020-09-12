<?php
declare(strict_types = 1);

namespace FOS\MessageBundle\Utils;

/**
 * Class UploadedFileManager
 *
 * @package App\Utils
 */
interface UploadedFileManagerInterface
{
    /**
     * @param array $uploadedFiles
     * @param string $resultFilePrefix
     * @param string $dirToMoveTo
     * @param string $id
     *
     * @return array
     */
    public function moveToDirAndReturnFileNames(
        array $uploadedFiles,
        string $resultFilePrefix,
        string $dirToMoveTo,
        string $id = ''
    ): array;
}