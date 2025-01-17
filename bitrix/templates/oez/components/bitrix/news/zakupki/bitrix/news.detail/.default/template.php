<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
	<div class="main container">


		<section class="block_top open_inform row">
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
			<?if($arResult['DETAIL_TEXT'] == ""){?>
				<h1><?=$arResult['NAME'];?></h1>
			<?}else{?>
				<h1><?=$arResult['DETAIL_TEXT'];?></h1>
			<?}?>
			<div class="row content">
				<div class="col-md-6">
					<div class="table">
						<div class="table-row row">
							<div class="table-cell cell_name">Место поставки товара, выполнения работ, оказания услуг</div>
							<div class="table-cell cell_descr"><?=$arResult["DISPLAY_PROPERTIES"]["ADDR_ZAKUPKA"]["DISPLAY_VALUE"]?></div>
						</div>
						<div class="table-row row">
							<div class="table-cell cell_name">Форма торгов</div>
							<div class="table-cell cell_descr"><?=$arResult["DISPLAY_PROPERTIES"]["FORMA"]["DISPLAY_VALUE"]?></div>
						</div>
						<div class="table-row row">
							<div class="table-cell cell_name">Статус</div>
							<div class="table-cell cell_descr"><?=$arResult["DISPLAY_PROPERTIES"]["STATUS"]["DISPLAY_VALUE"]?></div>
						</div>
						<div class="table-row row">
							<div class="table-cell cell_name">Номер</div>
							<div class="table-cell cell_descr"><?=$arResult["DISPLAY_PROPERTIES"]["NUM"]["DISPLAY_VALUE"]?></div>
						</div>
						<div class="table-row row">
							<div class="table-cell cell_name">Название документа</div>
							<div class="table-cell cell_descr"><?=$arResult["DISPLAY_PROPERTIES"]["NAME_DOCUMENT"]["DISPLAY_VALUE"]?></div>
						</div>
						<div class="table-row row">
							<div class="table-cell cell_name">Дата начала подачи заявки</div>
							<div class="table-cell cell_descr"><?=$arResult["DISPLAY_PROPERTIES"]["DATE_START"]["DISPLAY_VALUE"]?></div>
						</div>
						<div class="table-row row">
							<div class="table-cell cell_name">Дата окончания подачи заявки</div>
							<div class="table-cell cell_descr"><?=$arResult["DISPLAY_PROPERTIES"]["DATE_STOP"]["DISPLAY_VALUE"]?></div>
						</div>
						<div class="table-row row">
							<div class="table-cell cell_name">Организатор</div>
							<div class="table-cell cell_descr"><?=$arResult["DISPLAY_PROPERTIES"]["ORGANISATOR"]["DISPLAY_VALUE"]?></div>
						</div>
						<div class="table-row row">
							<div class="table-cell cell_name">Заказчик</div>
							<div class="table-cell cell_descr"><?=$arResult["DISPLAY_PROPERTIES"]["ZAKAZCHIK"]["DISPLAY_VALUE"]?></div>
						</div>
						<div class="table-row row">
							<div class="table-cell cell_name">Адрес заказчика</div>
							<div class="table-cell cell_descr"><?=$arResult["DISPLAY_PROPERTIES"]["ADDR_ZAKAZCHIK"]["DISPLAY_VALUE"]?></div>
						</div>
					</div>
				</div>
				<div class="col-md-6 dop_info_container">
					<div class="dop_info_header">Дополнительная информация</div>
					<!--<div class="dop_info_body">
						<div class="please">Для просмотра/скачиваня файлов, пожалуйста,</div>
						<div class="link_login"><a>авторизуйтесь / зарегистрируйтесь.</a></div>
					</div>-->

					<div class="download">
					<?foreach ($arResult["DISPLAY_PROPERTIES"]["DOC"]["FILE_VALUE"] as $key => $value):
						switch($value["CONTENT_TYPE"]){
					case 'image/tiff':
					case 'tif':
						$ico = SITE_TEMPLATE_PATH.'/img/tif.png';
						$type = 'TIF';
						break;
					case 'application/msword':
					case 'text/rtf':
					case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
						$ico = SITE_TEMPLATE_PATH.'/img/doc2.png';
						$type = 'DOC';
						break;
					case 'ppt':
					case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
						$ico = SITE_TEMPLATE_PATH.'/img/ppt.png';
						$type = 'PPT';
						break;
					case 'application/vnd.ms-excel':
					case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
						$ico = SITE_TEMPLATE_PATH.'/img/xls.png';
						$type = 'XLS';
						break;
					default:
						$ico = SITE_TEMPLATE_PATH.'/img/pdf.png';
						$type = 'pdf';
				}
						$size = $value["FILE_SIZE"]/1024;
						if($size<1024){
							$size = round($size,2).'КБ';
						}else{
							$size/=1024;
							$size =round($size,2).'МБ';
						}?>

						<div class="file_it">
							<div><a href="<?=$value["SRC"]?>"><img src="<?=$ico?>"> <?=$value["ORIGINAL_NAME"]?>, <?=$size?></a></div>
							<div><?=$value["DESCRIPTION"]?>
							</div>
						</div>
						</br>
					<?endforeach;?>
					</div>
				</div>
			</div>
			<div class="row content">
				<a class="back" href="javascript:history.go(-1)">
					<img src="<?=SITE_TEMPLATE_PATH?>/img/arrow2.png">
					<span>Назад</span>
				</a>
			</div>
		</section>
	</div>
