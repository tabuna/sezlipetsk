<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/investor/sotsialnaya-infrastruktura-oez/([a-zA-Z0-9_-]+)/\\?{0,1}(.*)\$#",
		"RULE" => "SECTION_CODE=\$1",
		"ID" => "",
		"PATH" => "/investor/sotsialnaya-infrastruktura-oez/index.php",
	),
	array(
		"CONDITION" => "#^/en/investor/sotsialnaya-infrastruktura-oez/([a-zA-Z0-9_-]+)/\\?{0,1}(.*)\$#",
		"RULE" => "SECTION_CODE=\$1",
		"ID" => "",
		"PATH" => "/en/investor/sotsialnaya-infrastruktura-oez/index.php",
	),
	array(
		"CONDITION" => "#^/information/links/([a-zA-Z0-9_]+)/\\?{0,1}(.*)\$#",
		"RULE" => "/information/links/index.php?SECTION_CODE=\\1&\\2",
		"ID" => "",
		"PATH" => "",
	),
	array(
		"CONDITION" => "#^/about/upravlyayushchaya-kompaniya/zakupki/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/about/upravlyayushchaya-kompaniya/zakupki/index.php",
	),
	array(
		"CONDITION" => "#^/board/([a-zA-Z0-9_]+)/\\?{0,1}(.*)\$#",
		"RULE" => "/board/index.php?SECTION_CODE=\\1&\\2",
		"ID" => "",
		"PATH" => "",
	),
	array(
		"CONDITION" => "#^/en/about/rezidenty/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/en/about/rezidenty/index.php",
	),
	array(
		"CONDITION" => "#^/en/press-releases/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/en/press-releases/index.php",
	),
	array(
		"CONDITION" => "#^/about/rezidenty/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/about/rezidenty/index.php",
	),
	array(
		"CONDITION" => "#^/about/rezidenty/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/map/index.php",
	),
	array(
		"CONDITION" => "#^/nationalnews/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/nationalnews/index.php",
	),
	array(
		"CONDITION" => "#^/press-relizy/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/press-relizy/index.php",
	),
	array(
		"CONDITION" => "#^/job/vacancy/#",
		"RULE" => "",
		"ID" => "bitrix:catalog",
		"PATH" => "/job/vacancy/index.php",
	),
	array(
		"CONDITION" => "#^/job/resume/#",
		"RULE" => "",
		"ID" => "bitrix:catalog",
		"PATH" => "/job/resume/index.php",
	),
	array(
		"CONDITION" => "#^/en/news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/en/news/index.php",
	),
	array(
		"CONDITION" => "#^/themes/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/themes/index.php",
	),
	array(
		"CONDITION" => "#^/forum/#",
		"RULE" => "",
		"ID" => "bitrix:forum",
		"PATH" => "/forum/index.php",
	),
	array(
		"CONDITION" => "#^/blogs/#",
		"RULE" => "",
		"ID" => "bitrix:blog",
		"PATH" => "/blogs/index.php",
	),
	array(
		"CONDITION" => "#^/photo/#",
		"RULE" => "",
		"ID" => "bitrix:photogallery_user",
		"PATH" => "/photo/index.php",
	),
	array(
		"CONDITION" => "#^/news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/news/index.php",
	),
);

?>
