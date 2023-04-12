<?php

namespace Commander;

class UploadValidator {

    public function getCurrentMode(array $post): ?string {
        if (isset($post["mode"])) {
            return $post["mode"];
        }
        return null;
    }

    function checkDocValidFilesTypes(array $files): bool {

        if (isset($files['file-ms_word']['name'][0])) {
            foreach ($files['file-ms_word']['name'] as $type) {
                $fileNameParts = explode('.', $type);
                $fileExt = end($fileNameParts);
                if ($fileExt !== ALLOWED_TYPES_DOC_UPLOADS["MS_WORD_DOCX"] && $fileExt !== ALLOWED_TYPES_DOC_UPLOADS["MS_WORD_DOC"]) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    function checkTextValidFilesTypes(array $files): bool {

        if (isset($files['file-txt']['name'][0])) {
            foreach ($files['file-txt']['name'] as $type) {
                $fileNameParts = explode('.', $type);
                $fileExt = end($fileNameParts);
                if ($fileExt !== ALLOWED_TYPES_TEXT_UPLOADS["TXT"]) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}