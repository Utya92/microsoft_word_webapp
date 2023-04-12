<?php

namespace Services\Highlighter ;

use PhpOffice;
use PhpOffice\PhpWord\Element\TextRun;
use Services\Customs\CustomTemplateProcessor;

class MarkWordsService {

    private string $_file;

    public function __construct(string $_file) {
        $this->_file = $_file;
    }

    private function createCustomTemplateProcessor(): ?CustomTemplateProcessor {
        try {
            $templateProcessor = new  CustomTemplateProcessor($this->_file);
            $templateProcessor->setMacroOpeningChars('');
            $templateProcessor->setMacroClosingChars('');
            return $templateProcessor;
        } catch (PhpOffice\PhpWord\Exception\CopyFileException|PhpOffice\PhpWord\Exception\CreateTemporaryFileException $e) {
            echo $e;
        }
        return null;
    }

    function markWords(array $highlightWords, string $saveAs): void {
        $templateProcessor = $this->createCustomTemplateProcessor();
        $templateProcessor->setWordsToHighlight($highlightWords);
        $foundWords = $templateProcessor->getFoundWords();

        $textRun = new TextRun();
        $templateProcessor->setMacroChars('${', '}');
        foreach ($foundWords as $item) {
            $repl = $textRun->addText($item, array('bold' => false, 'bgColor' => '#fdfd02'));
            $templateProcessor->setComplexValue($item, $repl);

        }
        $templateProcessor->saveAs($saveAs);
    }
}