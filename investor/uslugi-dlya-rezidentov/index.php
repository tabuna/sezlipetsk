<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Услуги для резидентов");
?><div class="main ">
 <section class="top_block_main">
	<div class="container">
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
		<h3><?=$APPLICATION->GetTitle();?></h3>
		
		<p align="justify">Для резидентов и нерезидентов ОЭЗ ППТ «Липецк» предлагаются следующие виды услуг:</p>
<p align="justify"><b>1. Основные услуги:</p></b>
<p align="justify">1.1. Технологическое присоединение к системе ресурсоснабжения ОЭЗ – подключение инженерных сетей заявителя к сетям электро-, тепло-, водоснабжения и водоотведения. Стоимость услуг определяется индивидуально и в случаях, установленных законодательством РФ, утверждается уполномоченными органами исполнительной власти.</p>
<p align="justify">1.2. Ресурсоснабжение – теплоснабжение, водоснабжение, прием сточных вод, транспортировка электроэнергии и газа по сетям ОЭЗ. Стоимость услуг определяется в соответствии с тарифами, утвержденными уполномоченными органами исполнительной власти.</p>
<p align="justify"><b>2. Дополнительные услуги:</p></b>
<p align="justify">2.1. Техническое обслуживание инженерных сетей – выполнение комплекса мероприятий, определенного требованиями нормативных актов и направленного на обеспечение бесперебойной и безопасной работы энергоустановок потребителей. Стоимость услуг определяется индивидуально, в зависимости от состава обслуживаемого оборудования.</p>
<p align="justify">2.2. Аренда – аренда офисных помещений, конференц-залов, оборудованных необходимым презентационным оборудованием, переговорных комнат.</p>
<p align="justify">Стоимость аренды офисных помещений в месяц составляет 413/550 руб. за м2 с НДС (в стоимость входит ресурсоснабжение помещения, обслуживание мест общего пользования, охрана здания).</p>
<p align="justify">Стоимость предоставления конференц-залов, переговорных комнат в час составляет 10-15 руб./м2 с НДС (в стоимость входит предоставление мультимедийного оборудования и специалиста по его эксплуатации, ресурсоснабжение помещений, охрана здания, уборка помещений).</p>
<p align="justify">2.3. Испытание электрооборудования и средств защиты – проведение обязательных испытаний электрооборудования (в т.ч. – кабеля из сшитого полиэтилена) и средств защиты заказчика (периодические испытания средств защиты, высоковольтные испытания, испытания при проведении пуско-наладочных работ), определение мест повреждений в кабеле. Стоимость услуг определяется в соответствии с действующим прейскурантом и является одной из наиболее низких в регионе. </p>
<p align="justify">2.4. Предоставление автотранспорта и спецтехники.</p>
<p align="justify">2.5. Строительный контроль (технический надзор) – осуществление функций строительного контроля (технического надзора) заказчика на этапе проектирования и строительства объектов капитального строительства. Цель – обеспечение выполнения подрядчиком работ в соответствии с техническим заданием заказчика, проектной документацией, требованиями нормативных документов и строительных регламентов, условиями договора подряда. Стоимость услуг определяется индивидуально.</p>
		</div>
 </section>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>