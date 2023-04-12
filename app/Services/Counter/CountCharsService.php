<?php

namespace Services\Counter;

use PhpOffice\PhpWord\IOFactory;


class CountCharsService {

    private string $_file;

    public function __construct(string $_file) {
        $this->_file = $_file;
    }


    public function getCountNumberCharacters($ignoreSpaces = true): int {
        $document = IOFactory::load($this->_file);
        $cntChar = 0;
        $sections = $document->getSections();

        foreach ($sections as $section) {
            if (method_exists($section, "getElements")) {
                $arrays = $section->getElements();
                foreach ($arrays as $el) {
                    if (method_exists($el, "getElements")) {
                        $classWithElem = $el->getElements();
                        foreach ($classWithElem as $item) {
                            if (method_exists($item, "getText")) {
                                $cntChar += mb_strlen(trim($item->getText()));
                            }
                        }
                    }
                }
            }
        }
        //table counter
        foreach ($sections[0]->getElements() as $el) {
            if ($el instanceof \PhpOffice\PhpWord\Element\Table) {
                foreach ($el->getRows() as $row) {
                    foreach ($row->getCells() as $cell) {
                        foreach ($cell->getElements() as $cEl) {
                            if ($cEl instanceof \PhpOffice\PhpWord\Element\Text) {
                                $cntChar += mb_strlen($cEl->getText());
                            } elseif ($cEl instanceof \PhpOffice\PhpWord\Element\TextRun) {
                                if (count($cEl->getElements()) > 0 and $cEl->getElements()[0] instanceof \PhpOffice\PhpWord\Element\Text) {
                                    $cntChar += mb_strlen(trim($cEl->getElements()[0]->getText()));
                                }
                            }
                        }
                    }
                }
            }
        }
        return $cntChar;
    }
}