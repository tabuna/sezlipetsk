
<footer>
	<div class="container">
		<div class="part_1 row">
			<div class="col-md-7 no_padding">
				<!--Социальные сети-->
				<?$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"PATH" => SITE_TEMPLATE_PATH."/include/socials.php",
						"EDIT_TEMPLATE" => ""
					),
					false
				);?>
				<!--Социальные сети-->
				<div class="col-lg-7 col-md-12 col-sm-6 col-xs-12 foot_item">
					<div class="foot_name">Partners</div>
					<div class="partners_ul">
						<ul>
							<li><a href="http://admlip.ru/eng/" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/img/lipobl.png"></a></li>
							<li><a href="http://eng.russez.ru/management_company/" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/img/oez.png"></a></li>
							<li><a href="http://economy.gov.ru/en/home/" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/img/mineconom.png"></a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="clearfix hidden-md hidden-lg"></div>
			<div class="col-md-5 no_padding">
				<div class="col-md-12 col-sm-12 foot_item">

					<div class="row">
						<div class="foot_phones col-sm-6 col-md-6">
							<div class="foot_name">Contacts</div>
							<div class="f_t_img">
								<div>
									<img src="<?=SITE_TEMPLATE_PATH?>/img/tel.png">
								</div>
								<div>
									<div>Phone: +7 (4742) 51-51-80,</div><br>
									<div>Fax: +7 (4742) 51-51-95</div>
								</div>
							</div>

						</div>
						<div class="col-md-6 no_padding">
							<!--<div class="foot_name_dop">Power supply hotline: +7 (4742) 51-51-80</div>
							<div class="red_link"><a>Power outage</a></div>-->
						</div>
						<div class="foot_adres col-sm-12 col-md-12">
							<div>
								<img src="<?=SITE_TEMPLATE_PATH?>/img/mark.png">
							</div>
							<div>
								Administrative and Business Center, Lipetsk Special Economic Zone
								Gryazi District, Lipetsk Region 399071
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="part_2 row">
			<div class="col-md-4 col-sm-12 all_rights">All rights reserved. 2007–2015. Lipetsk Industrial Special Economic Zone
			</div>
			<div class="col-md-4 col-sm-6 col-xs-6 sitemap"><a href="/en/sitemap/"><img src="<?=SITE_TEMPLATE_PATH?>/img/sitemap.png">Site map</a></div>
			<div class="col-md-4 col-sm-6 col-xs-6 copirite">
				Powered by
				<a target="_blank" href="http://octavian48.ru">Octavian</a>
			</div>
		</div>
	</div>
	<?
		//$APPLICATION->AddChainItem($APPLICATION->GetTitle());
	?>
</footer>
