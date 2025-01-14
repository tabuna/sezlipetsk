<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle(" Social infrastructure of the region");
?>
	<div class="main">

		<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"sots_infr", 
	array(
		"COMPONENT_TEMPLATE" => "sots_infr",
		"IBLOCK_TYPE" => "information",
		"IBLOCK_ID" => "43",
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"SECTION_CODE" => $_REQUEST["SECTION_CODE"],
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arrFilter",
		"INCLUDE_SUBSECTIONS" => "N",
		"SHOW_ALL_WO_SECTION" => "N",
		"PAGE_ELEMENT_COUNT" => "30",
		"LINE_ELEMENT_COUNT" => "3",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_LIMIT" => "5",
		"TEMPLATE_THEME" => "blue",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SEF_MODE" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "Y",
		"SET_BROWSER_TITLE" => "Y",
		"BROWSER_TITLE" => "-",
		"SET_META_KEYWORDS" => "Y",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "Y",
		"META_DESCRIPTION" => "-",
		"SET_LAST_MODIFIED" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRICE_CODE" => array(
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"BASKET_URL" => "/personal/basket.php",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"ADD_PICT_PROP" => "-",
		"LABEL_PROP" => "-"
	),
	false
);?>

		<section class="container">
			<div class="row">
				<div class="col-md-6 ">
					<div class="col-lg-6 col-xs-12 col-sm-12 col-md-6 block_item">
						<div class="item_block">
							<a class="item_block_a" href="/en/investor/sotsialnaya-infrastruktura-oez/obrazovanie/"><div class="hover_container">
									<div class="hover"></div>
								</div>
								<div class="item_bod">
									<div class="item_name">Образование</div>
								</div>
								<div class="block_icon">
							<span
								class="item_block_img">
								<img src="<?=SITE_TEMPLATE_PATH?>/img/icons/T_15(1)white.png"/>
								<img src="<?=SITE_TEMPLATE_PATH?>/img/icons/T_15(1).png"/>
							</span>
								</div></a>
						</div>
					</div>
					<div class="col-lg-6 col-xs-12 col-sm-12 col-md-6 block_item">
						<div class="item_block">
							<a class="item_block_a" href="/en/investor/sotsialnaya-infrastruktura-oez/meditsina/"><div class="hover_container">
									<div class="hover"></div>
								</div>
								<div class="item_bod">
									<div class="item_name">Медицина</div>
								</div>
								<div class="block_icon">
							<span
								class="item_block_img">
								<img src="<?=SITE_TEMPLATE_PATH?>/img/icons/T_15(4)white.png"/>
								<img src="<?=SITE_TEMPLATE_PATH?>/img/icons/T_15(4).png"/>
							</span>
								</div></a>
						</div>
					</div>
					<div class="col-md-12 col-lg-6 col-xs-12 block_item">
						<div class="item_block">
							<a href="/en/investor/sotsialnaya-infrastruktura-oez/poleznye-biznes-ssylki/" class="item_block_a" href="/en/investor/sotsialnaya-infrastruktura-oez/"><div class="hover_container">
									<div class="hover"></div>
								</div>
								<div class="item_bod">
									<div class="item_name">Полезные ссылки на бизнес-сервисы</div>
								</div>
								<div class="block_icon">
							<span
								class="item_block_img">
								<img src="<?=SITE_TEMPLATE_PATH?>/img/icons/T_15(5)white.png"/>
								<img src="<?=SITE_TEMPLATE_PATH?>/img/icons/T_15(5).png"/>
							</span>
								</div></a>
						</div>
					</div>
					<div class="col-md-12 col-lg-6 col-xs-12 block_item">
						<div class="item_block">
							<a class="item_block_a" href="/en/investor/sotsialnaya-infrastruktura-oez/zhilishchnye-usloviya/"><div class="hover_container">
									<div class="hover"></div>
								</div>
								<div class="item_bod">
									<div class="item_name">Жилищные условия</div>
								</div>
								<div class="block_icon">
							<span
								class="item_block_img">
								<img src="<?=SITE_TEMPLATE_PATH?>/img/icons/T_15(7)white.png"/>
								<img src="<?=SITE_TEMPLATE_PATH?>/img/icons/T_15(7).png"/>
							</span>
								</div></a>
						</div>
					</div>
				</div>
				<div class="col-md-6 ">
					<div class="col-sm-6 col-xs-6 block_item">
						<div class="item_block hight_item_block">
							<a class="item_block_a" href="/en/investor/sotsialnaya-infrastruktura-oez/kultura/"><div class="hover_container">
									<div class="hover"></div>
								</div>
								<div class="item_bod">
									<div class="item_name">Культурная среда</div>
									<div class="item_dop">Множество музеев, театров, кинотеатров и культурных выставок, музыкальные и творческие коллективы для культурного отдыха и детей и взрослых.</div>
								</div>
								<div class="block_icon">
							<span
								class="item_block_img">
								<img src="<?=SITE_TEMPLATE_PATH?>/img/icons/T_15(3)white.png"/>
								<img src="<?=SITE_TEMPLATE_PATH?>/img/icons/T_15(3).png"/>
							</span>
								</div></a>
						</div>
					</div>
					<div class="col-sm-6 col-xs-6 block_item">
						<div class="item_block hight_item_block">
							<a class="item_block_a" href="/en/investor/sotsialnaya-infrastruktura-oez/otdykh-i-sport/"><div class="hover_container">
									<div class="hover"></div>
								</div>
								<div class="item_bod">
									<div class="item_name">Отдых и спорт</div>
									<div class="item_dop">Кинотеатры, дворцы культуры и спорта, спортивные секции и множество кружков художественной самодеятельности для детей и взрослых.</div>
								</div>
								<div class="block_icon">
							<span class="item_block_img">
								<img src="<?=SITE_TEMPLATE_PATH?>/img/icons/T_15(2)white.png"/>
								<img src="<?=SITE_TEMPLATE_PATH?>/img/icons/T_15(2).png"/>
							</span>
								</div></a>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
