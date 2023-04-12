<?  if (!defined('JOIN_CORE') || !JOIN_CORE) die(); ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="app/views/templates/.default/style.css">
    <meta charset="utf-8"/>
</head>
<body>
<div class="main">
    <form enctype="multipart/form-data" action="index.php" method="POST">
        <h2 class="description">Сервис работы с документами Microsoft Word</h2>

        <?php if ($arResult["MODE"] == "first_starter"): ?>
            <!--        mode buttons-->
            <span class="mode">Выберите режим:</span>
            <br>
            <br>
            <input type="radio" id="counter" name="mode" value="counter">
            <label for="counter">Подсчет всех символов</label>
            <br>
            <br>
            <input type="radio" id="highlighter" name="mode" value="highlighter">
            <label for="highlighter">Выделение слов</label>
        <?php endif; ?>
        <!--        mode buttons-->


        <!--first-run-->
        <?php if ($arResult["MODE"] == "counter" || $arResult["MODE"] == "highlighter"): ?>
            <h2>Загрузка файла (*.doc/docx)</h2>
            <!--            <input type="hidden" name="MAX_FILE_SIZE" value="500000">-->
            <input type='file' name='file-ms_word[]' class='file-drop'
                   id='file-drop' <?= $arResult["MODE"] == "counter" ? "multiple required" : "" ?>>
            <br>
        <?php endif; ?>
        <?php if ($arResult["MODE"] == "highlighter"): ?>
            <h2>Загрузка файла (*.txt)</h2>
            <input type='file' name='file-txt[]' class='file-drop' id='file-drop' multiple required>
        <?php endif; ?>
        <!--first-run-->

        <br>
        <br>
        <span class="submit"> <input type='submit' class="btn-submit" value='SUBMIT'> </span>
        <hr>
    </form>

    <? if ($arResult["MODE"] == "highlighter" && $arResult["ERROR_VALID_DOC"]): ?>
        <div class="not-valid">
            ERROR! isn't VALID. Allowed format: .DOC/.DOCX
        </div>
    <? endif; ?>
    <? if ($arResult["MODE"] == "highlighter" && $arResult["ERROR_VALID_TXT"]): ?>
        <div class="not-valid">
            ERROR! One or more txt-files isn't VALID. Allowed format: .TXT
        </div>
    <? endif; ?>

    <? if ($arResult["MODE"] == "counter" && $arResult["ERROR_VALID_DOC"]): ?>
        <div class="not-valid">
            ERROR! One or more word-files isn't VALID. Allowed format: .DOC/.DOCX
        </div>
    <? endif; ?>

    <? if ($arResult["MODE"] == "counter" && $arResult["DOCUMENT_NAME"]): ?>
        <div class="docs-count">
            <h3> Количество загруженных документов: <span><?= $arResult["COUNT"] ?>
        </span>
            </h3>
        </div>
        <? foreach ($arResult["DOCUMENT_NAME"] as $name => $value): ?>
            <div class="message">
                <hr>
                <span class="doc-field">Имя документа: <?= $name ?></span>
                <br>
                <span class="doc-field">Количество всех символов в документе : <?= $value ?></span>
                <hr>
            </div>
        <? endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>