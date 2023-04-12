<?php
namespace Boot;

use Commander\FileCommander;
use Commander\UploadValidator;
use Services\Counter\CountCharsService;
use Services\Highlighter\MarkWordsService;

require_once 'vendor/autoload.php';

class Main {

    private array $arResult = [];

    function run(array $post, array $files) {
        $fileCommander = new FileCommander();
        $uploadValidator = new UploadValidator();

        //first_starter-mod
        if (!$uploadValidator->getCurrentMode($post)) {
            $this->arResult["MODE"] = "first_starter";
        }


        //counter-mode
        if ($uploadValidator->getCurrentMode($post) == "counter") {
            $this->arResult["MODE"] = "counter";
        }
        if ($files["file-ms_word"] && !$files["file-txt"]) {
            $this->arResult["MODE"] = "counter";
            if ($uploadValidator->checkDocValidFilesTypes($files)) {
                $fileCommander->uploadDocFiles($files);
                $allUploadedFiles = $fileCommander->getAllUploadedDocPath();
                foreach ($allUploadedFiles as $file) {
                    $this->arResult["COUNT"]++;
                    $this->arResult["DOCUMENT_NAME"][substr(strrchr($file, "/"), 1)] = (new CountCharsService($file))->getCountNumberCharacters();
                }
            } else {
                $this->arResult["ERROR_VALID_DOC"] = true;
            }
            $fileCommander->deleteTempFiles();
        }
        //counter-mode


        //highlighter-mode
        if ($uploadValidator->getCurrentMode($post) == "highlighter") {
            $this->arResult["MODE"] = "highlighter";
        }
        if ($files["file-ms_word"] && $files["file-txt"]) {
            $this->arResult["MODE"] = "highlighter";
            $isValidDoc = false;
            $isValidTxt = false;
            $uploadValidator->checkDocValidFilesTypes($files) ? $isValidDoc = true : $this->arResult["ERROR_VALID_DOC"] = true;
            $uploadValidator->checkTextValidFilesTypes($files) ? $isValidTxt = true : $this->arResult["ERROR_VALID_TXT"] = true;
            if ($isValidDoc && $isValidTxt) {
                $fileCommander->uploadDocFiles($files);
                $allUploadedFiles = $fileCommander->getAllUploadedDocPath();
                $fileCommander->uploadTextFiles($files);
                $findWords = $fileCommander->getWordsForMark();
                foreach ($allUploadedFiles as $file) {
                    $fullFilePath = MARKED_FILES_DIR_PATH . '/' . substr(strrchr($file, "/"), 1);
                    $fileType = explode('.', $file);
                    $fileType = "." . end($fileType);
                    $fileName = basename(basename($file), "$fileType");
                    (new MarkWordsService($file))->markWords($findWords, $fullFilePath);
                    $fileCommander->uploadFilesToClient($fullFilePath, $fileName, $fileType);
                }
            }
            $fileCommander->deleteTempFiles();
        }
        //highlighter-mode


        $fileCommander->showViewFile($this->arResult);
    }
}