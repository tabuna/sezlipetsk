<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Доска объявлений");
?><?$APPLICATION->IncludeComponent("bitrix:iblock.element.add", ".default", array(
	"NAV_ON_PAGE" => "10",
	"USE_CAPTCHA" => "Y",
	"USER_MESSAGE_ADD" => "Ваше объявление добавлено",
	"USER_MESSAGE_EDIT" => "Ваше объявление сохранено",
	"DEFAULT_INPUT_SIZE" => "30",
	"RESIZE_IMAGES" => "N",
	"IBLOCK_TYPE" => "services",
	"IBLOCK_ID" => "#BOARD_IBLOCK_ID#",
	"PROPERTY_CODES" => array(
		0 => "NAME",
		1 => "DATE_ACTIVE_TO",
		2 => "IBLOCK_SECTION",
		3 => "PREVIEW_TEXT",
		#IDS_CODE_PROPERTY#
	),
	"PROPERTY_CODES_REQUIRED" => array(
		0 => "NAME",
		1 => "DATE_ACTIVE_TO",
		2 => "IBLOCK_SECTION",
		3 => "PREVIEW_TEXT",
		
	),
	"GROUPS" => array(
		0 => "1",
		1 => "5",
	),
	"STATUS" => "ANY",	"STATUS_NEW" => "N",
	"ALLOW_EDIT" => "Y",
	"ALLOW_DELETE" => "Y",
	"ELEMENT_ASSOC" => "CREATED_BY",
	"MAX_USER_ENTRIES" => "20",
	"MAX_LEVELS" => "1",
	"LEVEL_LAST" => "Y",
	"MAX_FILE_SIZE" => "0",
	"PREVIEW_TEXT_USE_HTML_EDITOR" => "N",
	"DETAIL_TEXT_USE_HTML_EDITOR" => "N",
	"SEF_MODE" => "N",
	"SEF_FOLDER" => "/board/my/",
	"AJAX_MODE" => "N",
	"AJAX_OPTION_SHADOW" => "Y",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"CUSTOM_TITLE_NAME" => "Краткое описание объявления",
	"CUSTOM_TITLE_TAGS" => "",
	"CUSTOM_TITLE_DATE_ACTIVE_FROM" => "",
	"CUSTOM_TITLE_DATE_ACTIVE_TO" => "Срок публикации до",
	"CUSTOM_TITLE_IBLOCK_SECTION" => "Категория",
	"CUSTOM_TITLE_PREVIEW_TEXT" => "Текст объявления",
	"CUSTOM_TITLE_PREVIEW_PICTURE" => "",
	"CUSTOM_TITLE_DETAIL_TEXT" => "",
	"CUSTOM_TITLE_DETAIL_PICTURE" => "",
	"SEND_EMAIL" => "Y",
	"EMAIL_TO" => "mopckou_pak48rus@mail.ru",
	"SUBJECT" => "Добавлено новое объявление",
	"EVENT_MESSAGE_ID" => array(),
	"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
