<?php
//script defender
if (!defined('JOIN_CORE')) define('JOIN_CORE', true);

//allowed upload elements
const ALLOWED_TYPES_DOC_UPLOADS = [
    "MS_WORD_DOCX" => "docx",
    "MS_WORD_DOC" => "doc"
];
const ALLOWED_TYPES_TEXT_UPLOADS = [
    "TXT" => "txt"
];
const UPLOADED_DOC_DIR_PATH = "app/temp/upload_doc";
const UPLOADED_TXT_DIR_PATH = "app/temp/upload_txt";
const MARKED_FILES_DIR_PATH = "app/temp/marked_files";

const CURRENT_TEMPLATE = "app/views/templates/.default/load_form.php";