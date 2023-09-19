<?php

use Bitrix\Main\Loader;


global $USER, $APPLICATION;

if (!$USER->IsAdmin()) {
    return;
}

if (file_exists(__DIR__ . "/install/module.cfg.php")) {
    include(__DIR__ . "/install/module.cfg.php");
};

if (!Loader::includeModule($arModuleCfg['MODULE_ID'])) {
    return;
}

use Schemaorg\Options;

$currentUrl = $APPLICATION->GetCurPage() . '?mid=' . urlencode($mid) . '&amp;lang=' . LANGUAGE_ID;
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$doc_root = \Bitrix\Main\Application::getDocumentRoot();
$url_module = str_replace($doc_root, '', __DIR__);

//setting site URL
$urlOption = Options::prepareUrl($request,$arModuleCfg['MODULE_ID']);

//setting organization name
$nameOption = Options::prepareOption($request,$arModuleCfg['MODULE_ID'],'name');

//setting organization type
$organization_type = Options::prepareOption($request,$arModuleCfg['MODULE_ID'],'organization_type');
?>

<form method="POST" action="<?= $currentUrl; ?>"  id="schemaoptions_form"  >
    <?= bitrix_sessid_post(); ?>

<table>
	<tr>
		<th span="2">Список опций</th>
	</tr>
	<tr>
		<td>
			<label for="organization_type">Тип организации </label>
		</td>
		<td>
			<select name="organization_type" id="organization_type">
				<?php foreach ($arModuleCfg['ORGANIZATION_TYPE'] as $option): ?>
					<option value="<?=$option;?>" <?php if ($organization_type==$option) echo 'selected'; ?>><?=$option;?></option>
				<?php endforeach; ?>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<label for="name">Название организации </label>
		</td>
		<td>
			<input id="name" name="name" type="text" value="<?=$nameOption;?>">
		</td>
	</tr>

	<tr>
		<td>
			<label for="url">Адрес сайта</label>
		</td>
		<td>
			<input id="url" name="url" type="text" value="<?=$urlOption;?>">
		</td>
	</tr>
	<tr>
		<td span="2">
			<button type="submit" class="adm-btn"  > Сохранить  </button>
		</td>

	</tr>
</table>


</form>
