<?php

use Bitrix\Main\Loader;


global $USER, $APPLICATION, $mid;

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
$urlOption = Options::prepareUrl($request, $arModuleCfg['MODULE_ID']);

//setting organization name
$nameOption = Options::prepareOption($request, $arModuleCfg['MODULE_ID'], 'name');

//setting organization type
$organization_type = Options::prepareOption($request, $arModuleCfg['MODULE_ID'], 'organization_type');

//setting logo
$logoOption = Options::prepareOption($request, $arModuleCfg['MODULE_ID'], 'logo');

//setting addressCountry
$addressCountry = Options::prepareOption($request, $arModuleCfg['MODULE_ID'], 'addressCountry');

//setting addressRegion
$addressRegion = Options::prepareOption($request, $arModuleCfg['MODULE_ID'], 'addressRegion');

//setting addressaddressLocality
$addressLocality = Options::prepareOption($request, $arModuleCfg['MODULE_ID'], 'addressLocality');

//setting streetAddress
$streetAddress = Options::prepareOption($request, $arModuleCfg['MODULE_ID'], 'streetAddress');

//setting postalCode
$postalCode = Options::prepareOption($request, $arModuleCfg['MODULE_ID'], 'postalCode');

//creating open hours string
$openHoursString = Options::prepareOpenHours($request, $arModuleCfg);
$arOpenHours = Options::parseOpenHours($openHoursString, $arModuleCfg);

//setting description
$description = Options::prepareOption($request, $arModuleCfg['MODULE_ID'], 'description');
?>

<form method="POST" action="<?= $currentUrl; ?>" id="schemaoptions_form" name="schemaoptions_form">
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
						<option value="<?= $option; ?>" <?php if ($organization_type == $option) echo 'selected'; ?>><?= $option; ?></option>
                    <?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="name">Название организации </label>
			</td>
			<td>
				<input id="name" name="name" type="text" value="<?= $nameOption; ?>">
			</td>
		</tr>
		<tr>
			<td>
				<label for="description">Описание организации </label>
			</td>
			<td>
				<textarea id="description" name="description"><?= $description; ?></textarea>
			</td>
		</tr>

		<tr>
			<td>
				<label for="url">Адрес сайта</label>
			</td>
			<td>
				<input id="url" name="url" type="text" value="<?= $urlOption; ?>">
			</td>
		</tr>

		<tr>
			<td><label for="logo">Путь к файлу логотипа</label></td>
			<td>
                <?php CAdminFileDialog::ShowScript
                (
                    [
                        "event"            => "BtnClickExpPath",
                        "arResultDest"     => array("FORM_NAME" => "schemaoptions_form", "FORM_ELEMENT_NAME" => 'logo'),
                        "arPath"           => array("PATH" => GetDirPath('/')),
                        "select"           => 'F',// F - file only, D - folder only
                        "operation"        => 'O',// O - open, S - save
                        "showUploadTab"    => false,
                        "showAddToMenuTab" => false,
                        "fileFilter"       => 'image',
                        "SaveConfig"       => false,
                    ]
                );
                ?><input type="text" name="logo" id="logo" size="50" maxlength="255" value="<?= $logoOption; ?>">&nbsp;
				<input type="button" name="browseExpPath" value="..." onClick="BtnClickExpPath()">

			</td>
		</tr>
		<tr>
			<td span="2">
				<table>
					<tr>
						<th span="2">Адрес</th>
					</tr>
					<tr>
						<td>
							<label for="addressCountry">Страна</label>
						</td>
						<td>
							<input name="addressCountry" id="addressCountry" type="text" value="<?= (!empty($addressCountry)) ? $addressCountry : 'Россия'; ?>">
						</td>
					</tr>
					<tr>
						<td>
							<label for="addressRegion">Регион</label>
						</td>
						<td>
							<input name="addressRegion" id="addressRegion" type="text" value="<?= $addressRegion; ?>">
						</td>
					</tr>
					<tr>
						<td>
							<label for="addressLocality">Город</label>
						</td>
						<td>
							<input name="addressLocality" id="addressLocality" type="text" value="<?= $addressLocality; ?>">
						</td>
					</tr>
					<tr>
						<td>
							<label for="postalCode">Почтовый индекс</label>
						</td>
						<td>
							<input name="postalCode" id="postalCode" type="text" value="<?= $postalCode; ?>">
						</td>
					</tr>
					<tr>
						<td>
							<label for="streetAddress">Улица, дом</label>
						</td>
						<td>
							<input name="streetAddress" id="streetAddress" type="text" value="<?= $streetAddress; ?>">
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td span="2">
				<table>
					<tr>
						<th span="2">Время работы</th>
					</tr>
                    <?php foreach ($arModuleCfg['DAYS_OF_WEEK'] as $day => $dayName): ?>
						<tr>
							<td>
								<label for="hours_<?= $day; ?>"><?= $dayName; ?></label>
								<input type="checkbox" name="hours_<?= $day; ?>_checked" id="hours_<?= $day; ?>_checked" <?php if (!empty($arOpenHours[$day])) echo 'checked'; ?>>
							</td>
							<td>С
								<select name="hours_beginning_<?= $day; ?>" id="hours_beginning_<?= $day; ?>">
                                    <?php foreach ($arModuleCfg['HOURS_OF_DAY'] as  $hour): ?>
										<option value="<?= $hour; ?>" <?php if ($hour == $arOpenHours[$day]['open']) echo 'selected'; ?>><?= $hour; ?></option>
                                    <?php endforeach; ?>
								</select>
								до
								<select name="hours_ending_<?= $day; ?>" id="hours_ending_<?= $day; ?>">
									<?php foreach ($arModuleCfg['HOURS_OF_DAY'] as  $hour): ?>
										<option value="<?= $hour; ?>" <?php if ($hour == $arOpenHours[$day]['close']) echo 'selected'; ?>><?= $hour; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
                    <?php endforeach; ?>

				</table>
			</td>
		</tr>
		<tr>
			<td span="2">
				<button type="submit" class="adm-btn"> Сохранить</button>
			</td>
		</tr>
	</table>


</form>
