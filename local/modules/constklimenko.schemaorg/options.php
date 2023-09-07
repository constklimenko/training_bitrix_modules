<?php

use Bitrix\Main\Loader;

global $USER;

if (!$USER->IsAdmin()) {
    return;
}

if (file_exists(__DIR__ . "/install/module.cfg.php")) {
    include(__DIR__ . "/install/module.cfg.php");
};

if (!Loader::includeModule($arModuleCfg['MODULE_ID'])) {
    return;
}

$currentUrl = $APPLICATION->GetCurPage() . '?mid=' . urlencode($mid) . '&amp;lang=' . LANGUAGE_ID;
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$doc_root = \Bitrix\Main\Application::getDocumentRoot();
$url_module = str_replace($doc_root, '', __DIR__);
?>

<form method="POST" action="<?= $currentUrl; ?>"  id="schemaoptions_form"  >
    <?= bitrix_sessid_post(); ?>
    Список опций<br>

    <label for="one">Некая опция </label><input id="one" name="one[e]" type="text" value=""><br>
    <label for="one">Некая опция 2 </label><input id="two" name="one[r]" type="text" value=""><br>

	<button type="submit" class="adm-btn"  > Сохранить  </button>
</form>
