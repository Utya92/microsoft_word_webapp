<?php

namespace Services\Customs;

use PhpOffice\PhpWord\TemplateProcessor;

class CustomTemplateProcessor extends TemplateProcessor {
    private array $foundWords=[];

    public function getFoundWords(): array {
        return $this->foundWords;
    }

    public function setWordsToHighlight(array $stopWords): void {
        foreach ($stopWords as $word) {
            $search = static::ensureMacroCompleted($word);
            $this->tempDocumentMainPart = $this->setMacroForPart($search, $this->tempDocumentMainPart);
        }
    }

    protected function setMacroForPart(string $search, $documentPartXML) {
        return preg_replace_callback(
            "/(?![^{]*})\w*$search\w*/miu",
            function ($match) {
                $this->foundWords[] = $match[0];
                return '${' . $match[0] . '}';
            },
            $documentPartXML);
    }
}