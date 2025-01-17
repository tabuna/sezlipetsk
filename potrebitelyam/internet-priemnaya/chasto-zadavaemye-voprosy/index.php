<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Часто задаваемые вопросы");
?>

<div class="main container">
    <div class="col-xs-12 col-md-6">
        <!--Хеебный крош-->
		<?$APPLICATION->IncludeComponent(
			"custom:breadcrumb",
			"",
			Array(
				"COMPONENT_TEMPLATE" => "oez",
				"START_FROM" => "0",
				"PATH" => "",
				"SITE_ID" => "s1"
			)
		);
		?>
		<!--Хлебные крошки-->
		<h1><?=$APPLICATION->GetTitle();?></h1>

        <div class="scroll-content">
            <div class="">
              ОАО «ОЭЗ ППТ «Липецк» осуществляет строительство и эксплуатацию распределительных сетей электроснабжения, теплоснабжения, газоснабжения, водоснабжения и водоотведения на территории ОЭЗ ППТ «Липецк».
В данном разделе Вы найдёте ответы на часто задаваемые вопросы по указанным видам деятельности, а также задать свой вопрос если ответа на него Вы не нашли.<br/><br/>

                В данном разделе вы найдёте информацию о том, как начать свой бизнес в российских особых экономических
                зонах.
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6 login_part_right max_height_block">
	    <?$APPLICATION->IncludeComponent(
		    "custom:support.form_toconnect",
		    "faq",
		    Array(
		    )
	    );
	    ?>
    </div>

</div>
<?$APPLICATION->IncludeComponent(
	"custom:news.list_faq", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_TYPE" => "information",
		"IBLOCK_ID" => "30",
		"NEWS_COUNT" => "",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_LAST_MODIFIED" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => ""
	),
	false
);?>



</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>