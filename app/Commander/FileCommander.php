<?php

namespace Commander;

class FileCommander {

    function showViewFile(array $res): void {
        $arResult = $res;
        require CURRENT_TEMPLATE;
    }

    function uploadDocFiles(array $files): array {
        $uploadDocFilesArr = [];
        if ($files["file-ms_word"]) {
            for ($i = 0; $i < count($files['file-ms_word']['name']); $i++) {
                $uploadDocFilesArr[$i] = UPLOADED_DOC_DIR_PATH . "/" . $files['file-ms_word']['name'][$i];
                move_uploaded_file($files['file-ms_word']['tmp_name'][$i], $uploadDocFilesArr[$i]);;
            }
        }
        return $uploadDocFilesArr;
    }

    function uploadTextFiles(array $files): array {
        $uploadTextFilesArr = [];
        if ($files["file-txt"]) {
            for ($i = 0; $i < count($files['file-txt']['name']); $i++) {
                $uploadTextFilesArr[$i] = UPLOADED_TXT_DIR_PATH . "/" . $files['file-txt']['name'][$i];
                move_uploaded_file($files['file-txt']['tmp_name'][$i], $uploadTextFilesArr[$i]);
            }
        }
        return $uploadTextFilesArr;
    }

    function getAllUploadedDocPath(): array {
        $allDocPath = [];
        $handle = opendir(UPLOADED_DOC_DIR_PATH);
        if ($handle) {
            while (($entry = readdir($handle)) !== false) {
                if ($this->fixPath($entry)) {
                    $allDocPath[] = UPLOADED_DOC_DIR_PATH . '/' . $entry;
                }
            }
        }
        closedir($handle);
        return $allDocPath;
    }

    function getWordsForMark(): array {
        $allWordsArr = [];
        $handle = opendir(UPLOADED_TXT_DIR_PATH);
        if ($handle) {
            while (($entry = readdir($handle)) !== false) {
                if ($this->fixPath($entry)) {
                    $allWordsArr = array_merge($allWordsArr, array_map("trim", file(UPLOADED_TXT_DIR_PATH . '/' . $entry)));
                }
            }
        }
        $allWordsArr = array_unique($allWordsArr);
        return array_filter($allWordsArr, fn($value) => !is_null($value) && $value !== '');
    }

    function deleteTempFiles(): void {
        $handleDOC = opendir(UPLOADED_DOC_DIR_PATH);
        $handleTXT = opendir(UPLOADED_TXT_DIR_PATH);
        $handleMarkedFiles = opendir(MARKED_FILES_DIR_PATH);
        if ($handleDOC) {
            while (($entry = readdir($handleDOC)) !== false) {
                if ($this->fixPath($entry)) {
                    unlink(UPLOADED_DOC_DIR_PATH . '/' . $entry);
                }
            }
        }
        if ($handleTXT) {
            while (($entry = readdir($handleTXT)) !== false) {
                if ($this->fixPath($entry)) {
                    unlink(UPLOADED_TXT_DIR_PATH . '/' . $entry);
                }
            }
        }
        if ($handleMarkedFiles) {
            while (($entry = readdir($handleMarkedFiles)) !== false) {
                if ($this->fixPath($entry)) {
                    unlink(MARKED_FILES_DIR_PATH . '/' . $entry);
                }
            }
        }
        closedir($handleDOC);
        closedir($handleTXT);
        closedir($handleMarkedFiles);
    }

    private function fixPath(string $path): bool {
        return ($path !== '.' && $path !== '..');
    }

    function uploadFilesToClient(string $pathToFile, string $saveName, string $saveType): void {
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header("Content-Disposition: attachment; filename = {$saveName}_result{$saveType}");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Content-Length: ' . filesize($pathToFile));

        while (ob_get_level()) {
            ob_end_clean();
        }

        readfile($pathToFile);
    }
}

