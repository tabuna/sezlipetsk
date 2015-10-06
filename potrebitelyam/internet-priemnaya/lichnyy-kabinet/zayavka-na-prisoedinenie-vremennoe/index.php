<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("Заявка на временное присоединение");
?><div class="main container">
	<div class="col-xs-12 col-md-6">
		 <!--Хеебный крош--> <?$APPLICATION->IncludeComponent(
	"custom:breadcrumb",
	"",
	Array(
		"COMPONENT_TEMPLATE" => "oez",
		"START_FROM" => "0",
		"PATH" => "",
		"SITE_ID" => "s1"
	)
);?> <!--Хлебные крошки-->
		<h1>Подать заявку на тех. присоединение к сетям электроснабжения.</h1>
 <span style="text-align: justify;">«Примечания:». Текст в примечаниях должен совпадать с текстом примечаний в направленных ранее формах заявок. Кроме того, в Примечаниях, помимо примечаний, необходимо указать Перечень необходимых документов, который одинаков и для заявки до 150 кВт и для заявки на временное присоединение:</span>
		<div class="scrollbar-outer">
			<p align="justify">
				 «К заявке необходимо приложить следующие документы:
			</p>
			<p align="justify">
				 1) план расположения энергопринимающих устройств, которые необходимо присоединить к электрическим сетям сетевой организации;
			</p>
			<p align="justify">
				 2) копия документа, подтверждающего право собственности или иное предусмотренное законом основание на объект капитального строительства (нежилое помещение в таком объекте капитального строительства) и (или) земельный участок, на котором расположены (будут располагаться) объекты заявителя, либо право собственности или иное предусмотренное законом основание на энергопринимающие устройства (для заявителей, планирующих осуществить технологическое присоединение энергопринимающих устройств потребителей, расположенных в нежилых помещениях многоквартирных домов или иных объектах капитального строительства, - копия документа, подтверждающего право собственности или иное предусмотренное законом основание на нежилое помещение в таком многоквартирном доме или ином объекте капитального строительства);
			</p>
			<p>
			</p>
			<p>
				 3) доверенность или иные документы, подтверждающие полномочия представителя заявителя, подающего и получающего документы, в случае если заявка подается в сетевую организацию представителем заявителя.»
			</p>
			<div class="prime4ania">
				<p>
					<br>
				</p>
			</div>
			<div class="needle-documents">
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-md-6 login_part_right max_height_block">
		 <?$APPLICATION->IncludeComponent(
	"custom:support.form_toconnect",
	"temp_connection",
	Array(
	)
);?>
	</div>
</div>
 <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>