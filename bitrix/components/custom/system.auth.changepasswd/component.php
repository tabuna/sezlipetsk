<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */

$arParamsToDelete = array(
	"login",
	"logout",
	"register",
	"forgot_password",
	"change_password",
	"confirm_registration",
	"confirm_code",
	"confirm_user_id",
);

if(defined("AUTH_404"))
{
	$arResult["AUTH_URL"] = POST_FORM_ACTION_URI;
}
else
{
	$arResult["AUTH_URL"] = $APPLICATION->GetCurPageParam("change_password=yes", $arParamsToDelete);
}

$arResult["BACKURL"] = $APPLICATION->GetCurPageParam("", $arParamsToDelete);
	//$arResult["BACKURL"] = "/potrebitelyam/internet-priemnaya/lichnyy-kabinet/";

//$arResult["AUTH_AUTH_URL"] = $APPLICATION->GetCurPageParam("login=yes",$arParamsToDelete);
	$arResult["AUTH_AUTH_URL"] ="/potrebitelyam/internet-priemnaya/lichnyy-kabinet/";

foreach ($arResult as $key => $value)
{
	if (!is_array($value)) $arResult[$key] = htmlspecialcharsbx($value);
}

$arRequestParams = array(
	"USER_CHECKWORD",
	"USER_PASSWORD",
	"USER_CONFIRM_PASSWORD",
);

foreach ($arRequestParams as $param)
{
	$arResult[$param] = strlen($_REQUEST[$param]) > 0 ? $_REQUEST[$param] : "";
	$arResult[$param] = htmlspecialcharsbx($arResult[$param]);
}

if(isset($_GET["USER_LOGIN"]))
	$arResult["~LAST_LOGIN"] = CUtil::ConvertToLangCharset($_GET["USER_LOGIN"]);
elseif(isset($_POST["USER_LOGIN"]))
	$arResult["~LAST_LOGIN"] = $_POST["USER_LOGIN"];
else
	$arResult["~LAST_LOGIN"] = $_COOKIE[COption::GetOptionString("main", "cookie_name", "BITRIX_SM")."_LOGIN"];

$arResult["LAST_LOGIN"] = htmlspecialcharsbx($arResult["~LAST_LOGIN"]);

$userId = 0;
if($arResult["~LAST_LOGIN"] <> '')
{
	$res = CUser::GetByLogin($arResult["~LAST_LOGIN"]);
	if($profile = $res->Fetch())
	{
		$userId = $profile["ID"];
	}
}
$arResult["GROUP_POLICY"] = CUser::GetGroupPolicy($userId);

$arResult["SECURE_AUTH"] = false;
if(!CMain::IsHTTPS() && COption::GetOptionString('main', 'use_encrypted_auth', 'N') == 'Y')
{
	$sec = new CRsaSecurity();
	if(($arKeys = $sec->LoadKeys()))
	{
		$sec->SetKeys($arKeys);
		$sec->AddToForm('bform', array('USER_PASSWORD', 'USER_CONFIRM_PASSWORD'));
		$arResult["SECURE_AUTH"] = true;
	}
}
//var_dump($arResult);
$this->IncludeComponentTemplate();
