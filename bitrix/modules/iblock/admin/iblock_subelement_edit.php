<?
use Bitrix\Main\Loader;
use Bitrix\Iblock;

/** @global CUser $USER */
/** @global CMain $APPLICATION */

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
Loader::includeModule('iblock');
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/prolog.php");

/*Change any language identifiers carefully*/
/*because of user customized forms!*/
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/iblock/admin/iblock_element_edit_compat.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/iblock/admin/iblock_element_edit.php");
IncludeModuleLangFile(__FILE__);

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/iblock/classes/general/subelement.php');

if (!function_exists('tabFilter'))
{
	function tabFilter($tab)
	{
		return ($tab['DIV'] != 'product_group');
	};
}

$IBLOCK_ID = 0;
if (isset($_REQUEST['IBLOCK_ID']))
	$IBLOCK_ID = (int)$_REQUEST['IBLOCK_ID'];
define("MODULE_ID", "iblock");
define("ENTITY", "CIBlockDocument");
define("DOCUMENT_TYPE", "iblock_".$IBLOCK_ID);
define("BX_SUB_SETTINGS",(isset($_REQUEST['bxsku']) && $_REQUEST['bxsku'] == 'Y'));

$ajaxReload = (isset($_REQUEST['ajaxReload']) && $_REQUEST['ajaxReload'] == 'Y');

$strWarning = '';
$bVarsFromForm = false;

$strProductName = '';

if (isset($_REQUEST['PRODUCT_NAME']) && '' != trim($_REQUEST['PRODUCT_NAME']))
{
	CUtil::decodeURIComponent($_REQUEST['PRODUCT_NAME']);
	CUtil::decodeURIComponent($_POST['PRODUCT_NAME']);
	$strProductName = trim($_REQUEST['PRODUCT_NAME']);
}

$arShowTabs = array(
	'edit_rights' => false,
	'workflow' => false,
	'bizproc' => false,
	'sections' => false,
	'catalog' => true,
	'product_set' => false,
	'product_group' => false
);

$bCatalog = false;
$arSubCatalog = false;
$bCatalog = Loader::includeModule('catalog');
if ($bCatalog)
{
	$arSubCatalog = CCatalogSKU::GetInfoByOfferIBlock($IBLOCK_ID);
	if (empty($arSubCatalog) || !is_array($arSubCatalog))
	{
		$bCatalog = false;
	}
}

if (!$bCatalog)
{
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
	ShowError(GetMessage('IB_SE_GENERAL_SKU_ERROR'));
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
	die();
}

$intProductID = 0;
if (isset($_REQUEST['PRODUCT_ID']))
	$intProductID = (int)$_REQUEST['PRODUCT_ID'];
$strSubTMP_ID = '';
if (isset($_REQUEST['TMP_ID']))
	$strSubTMP_ID = trim($_REQUEST['TMP_ID']);

$ID = intval($ID);	//ID of the persistent record
$copyID = 0;
$bSubCopy = false;
if (isset($_REQUEST['action']) && 'copy' == $_REQUEST['action'])
	$bSubCopy = true;
$copyID = (int)(isset($_REQUEST['copyID']) ? $_REQUEST['copyID'] : 0);

$PREV_ID = intval($PREV_ID);

$WF_ID = $ID; 		//This is ID of the current copy

$arIBlock = CIBlock::GetArrayByID($IBLOCK_ID);

$bWorkflow = ('N' != $arIBlock['WORKFLOW']) && Loader::includeModule('workflow');
$bBizproc = ('N' != $arIBlock['BIZPROC']) && Loader::includeModule('bizproc');

/*if(($ID <= 0 || $bSubCopy) && $bWorkflow)
	$WF = "Y";
elseif(!$bWorkflow)
	$WF = "N"; */
if($ID <= 0 && $bWorkflow)
	$WF = "Y";
elseif(!$bWorkflow)
	$WF = "N";

$historyId = intval($history_id);
if ($historyId > 0 && $bBizproc)
	$view = "Y";
else
	$historyId = 0;

$error = false;

$WF = ($WF=="Y") ? "Y" : "N";	//workflow mode
$view = ($view=="Y") ? "Y" : "N"; //view mode

$return_url = '';

CCatalogAdminTools::setSkuFormParams();
$arSubCatalogEx = array();
$arSubCatalogTabs = CCatalogAdminTools::getShowTabs($IBLOCK_ID, ($copyID > 0 && ID == 0 ? $copyID : $ID), $arSubCatalogEx);
if (!empty($arSubCatalogTabs))
{
	$arShowTabs['product_group'] = $arSubCatalogTabs[CCatalogAdminTools::TAB_GROUP];
}

do{ //one iteration loop

	if ($historyId > 0)
	{
		$arErrorsTmp = array();
		$arResult = CBPDocument::GetDocumentFromHistory($historyId, $arErrorsTmp);

		if (!empty($arErrorsTmp))
		{
			foreach ($arErrorsTmp as $e)
			{
				$error = new _CIBlockError(1, $e["code"], $e["message"]);
				break;
			}
		}

		$canWrite = CBPDocument::CanUserOperateDocument(
			CBPCanUserOperateOperation::WriteDocument,
			$USER->GetID(),
			$arResult["DOCUMENT_ID"],
			array("UserGroups" => $USER->GetUserGroupArray())
		);
		if (!$canWrite)
		{
			$error = new _CIBlockError(1, "ACCESS_DENIED", GetMessage("IBLOCK_ACCESS_DENIED_STATUS"));
			break;
		}

		$type = $arResult["DOCUMENT"]["FIELDS"]["IBLOCK_TYPE_ID"];
		$IBLOCK_ID = $arResult["DOCUMENT"]["FIELDS"]["IBLOCK_ID"];
	}

	$arIBTYPE = CIBlockType::GetByIDLang($type, LANGUAGE_ID);
	if($arIBTYPE===false)
	{
		$error = new _CIBlockError(1, "BAD_IBLOCK_TYPE", GetMessage("IBLOCK_BAD_BLOCK_TYPE_ID"));
		break;
	}

	$bBadBlock = true;

	if($arIBlock)
	{
		if (($ID > 0 && !$bCopy) && !CIBlockElementRights::UserHasRightTo($IBLOCK_ID, $ID, "iblock_admin_display"))
			$bBadBlock = true;
		elseif (($ID <= 0 || $bCopy) && !CIBlockSectionRights::UserHasRightTo($IBLOCK_ID, 0, "iblock_admin_display"))
			$bBadBlock = true;
		elseif (CIBlockElementRights::UserHasRightTo($IBLOCK_ID, $ID, "element_edit"))
			$bBadBlock = false;
		elseif ($bWorkflow && ($WF=="Y" || $view=="Y"))
			$bBadBlock = false;
		elseif ($bBizproc)
			$bBadBlock = false;
		elseif(
			(($ID <= 0) || $bCopy)
			&& CIBlockSectionRights::UserHasRightTo($IBLOCK_ID, 0, "section_element_bind")
		)
			$bBadBlock = false;
	}

	if($bBadBlock)
	{
		$error = new _CIBlockError(1, "BAD_IBLOCK", GetMessage("IBLOCK_BAD_IBLOCK"));
		$APPLICATION->SetTitle($arIBTYPE["ELEMENT_NAME"].": ".GetMessage("IBLOCK_EDIT_TITLE"));
		break;
	}

	$arShowTabs['workflow'] = $bWorkflow;
	$arShowTabs['bizproc'] = $bBizproc && ($historyId <= 0);
	$bEditRights = $arIBlock["RIGHTS_MODE"] === "E" && (
		CIBlockElementRights::UserHasRightTo($IBLOCK_ID, $ID, "element_rights_edit")
		|| (($ID <= 0 || $bSubCopy) && CIBlockRights::UserHasRightTo($IBLOCK_ID, $IBLOCK_ID, "element_rights_edit"))
	);
	$arShowTabs['edit_rights'] = $bEditRights;
	$arShowTabs['catalog'] = $view!="Y" && $bCatalog && ($USER->CanDoOperation('catalog_read') || $USER->CanDoOperation('catalog_price'));

	$aTabs = array();
	$aTabs[] = array(
		"DIV" => "sub_edit1",
		"TAB" => $arIBlock["ELEMENT_NAME"],
		"ICON"=>"iblock_element",
		"TITLE"=>htmlspecialcharsex($arIBlock["ELEMENT_NAME"])
	);
	$aTabs[] = array(
		"DIV" => "sub_edit5",
		"TAB" => GetMessage("IBEL_E_TAB_PREV"),
		"ICON"=>"iblock_element",
		"TITLE"=>GetMessage("IBEL_E_TAB_PREV_TITLE")
	);
	$aTabs[] = array(
		"DIV" => "sub_edit6",
		"TAB" => GetMessage("IBEL_E_TAB_DET"),
		"ICON"=>"iblock_element",
		"TITLE"=>GetMessage("IBEL_E_TAB_DET_TITLE")
	);
	$aTabs[] = array(
		"DIV" => "sub_edit14",
		"TAB" => GetMessage("IBEL_E_TAB14"),
		"ICON" => "iblock_iprops",
		"TITLE" => GetMessage("IBEL_E_TAB14_TITLE"),
	);
	if ($arShowTabs['catalog'])
		$aTabs[] = array(
			"DIV" => "sub_edit10",
			"TAB" => GetMessage("IBLOCK_TCATALOG"),
			"ICON"=>"iblock_element",
			"TITLE"=>GetMessage("IBLOCK_TCATALOG"),
			"required" => true,
	);
	if ($arShowTabs['product_group'])
	{
		$aTabs[] = array(
			"DIV" => "product_group",
			"TAB" => GetMessage("IBLOCK_EL_TAB_PRODUCT_GROUP"),
			"ICON" => "iblock_element",
			"TITLE" => GetMessage("IBLOCK_EL_TAB_PRODUCT_GROUP_TITLE"),
			"required" => true,
		);
	}
	if($arShowTabs['workflow'])
		$aTabs[] = array(
			"DIV" => "sub_edit4",
			"TAB" => GetMessage("IBLOCK_EL_TAB_WF"),
			"ICON"=>"iblock_element_wf",
			"TITLE"=>GetMessage("IBLOCK_EL_TAB_WF_TITLE")
		);
	if ($arShowTabs['bizproc'])
		$aTabs[] = array(
			"DIV" => "sub_edit7",
			"TAB" => GetMessage("IBEL_E_TAB_BIZPROC"),
			"ICON"=>"iblock_element_wf",
			"TITLE"=>GetMessage("IBEL_E_TAB_BIZPROC")
		);
	if($arShowTabs['edit_rights'])
		$aTabs[] = array(
			"DIV" => "sub_edit8",
			"TAB" => GetMessage("IBEL_E_TAB_RIGHTS"),
			"ICON" => "iblock_element_rights",
			"TITLE" => GetMessage("IBEL_E_TAB_RIGHTS_TITLE"),
		);

	$bCustomForm = 	(strlen($arIBlock["EDIT_FILE_AFTER"])>0 && is_file($_SERVER["DOCUMENT_ROOT"].$arIBlock["EDIT_FILE_AFTER"]))
		|| (strlen($arIBTYPE["EDIT_FILE_AFTER"])>0 && is_file($_SERVER["DOCUMENT_ROOT"].$arIBTYPE["EDIT_FILE_AFTER"]));

	$arPostParams = array(
		'bxpublic' => 'Y'
	);
	if (defined('BX_SUB_SETTINGS') && BX_SUB_SETTINGS == true)
	{
		$arPostParams['bxsku'] = 'Y';
	}
	if ('' != $strProductName)
	{
		$arPostParams['PRODUCT_NAME'] = $strProductName;
		$arPostParams['sessid'] = bitrix_sessid();
	}

	$arListUrl = array(
		'LINK' => $APPLICATION->GetCurPageParam(),
		'POST_PARAMS' => $arPostParams,
	);

	if($ID>0)
	{
		$rsElement = CIBlockElement::GetList(
			array(),
			array("ID" => $ID, "IBLOCK_ID" => $IBLOCK_ID, "SHOW_HISTORY"=>"Y"),
			false,
			false,
			array("ID", "CREATED_BY")
		);
		if(!($arElement = $rsElement->Fetch()))
		{
			$error = new _CIBlockError(1, "BAD_ELEMENT", GetMessage("IBLOCK_BAD_ELEMENT"));
			$APPLICATION->SetTitle($arIBTYPE["ELEMENT_NAME"].": ".GetMessage("IBLOCK_EDIT_TITLE"));
			break;
		}
	}

	$customTabber = new CAdminTabEngine("OnAdminIBlockElementEdit", array("ID" => $ID, "IBLOCK"=>$arIBlock, "IBLOCK_TYPE"=>$arIBTYPE));

	// workflow mode
	if ($ID > 0 && $WF == "Y")
	{
		// get ID of the last record in workflow
		$WF_ID = CIBlockElement::WF_GetLast($ID);

		// check for edit permissions
		$STATUS_ID = CIBlockElement::WF_GetCurrentStatus($WF_ID, $STATUS_TITLE);
		$STATUS_PERMISSION = CIBlockElement::WF_GetStatusPermission($STATUS_ID);

		if($STATUS_ID>1 && $STATUS_PERMISSION<2)
		{
			$error = new _CIBlockError(1, "ACCESS_DENIED", GetMessage("IBLOCK_ACCESS_DENIED_STATUS"));
			break;
		}
		elseif($STATUS_ID==1)
		{
			$WF_ID = $ID;
			$STATUS_ID = CIBlockElement::WF_GetCurrentStatus($WF_ID, $STATUS_TITLE);
			$STATUS_PERMISSION = CIBlockElement::WF_GetStatusPermission($STATUS_ID);
		}

		// check if document is locked
		if(CIBlockElement::WF_IsLocked($ID, $locked_by, $date_lock))
		{
			if($locked_by > 0)
			{
				$by="ID";
				$order="ASC";
				$rsUser = CUser::GetList($by, $order, array("ID_EQUAL_EXACT" => $locked_by));
				if($arUser = $rsUser->GetNext())
					$locked_by = rtrim("[".$arUser["ID"]."] (".$arUser["LOGIN"].") ".$arUser["NAME"]." ".$arUser["LAST_NAME"]);
			}
			$error = new _CIBlockError(2, "BLOCKED", GetMessage("IBLOCK_DOCUMENT_LOCKED", array("#ID#"=>$locked_by, "#DATE#"=>$date_lock)));
			break;
		}
	}
	elseif ($bBizproc)
	{

		$arDocumentStates = CBPDocument::GetDocumentStates(
			array(MODULE_ID, ENTITY, DOCUMENT_TYPE),
			($ID > 0) ? array(MODULE_ID, ENTITY, $ID) : null,
			"Y"
		);

		$arCurrentUserGroups = $USER->GetUserGroupArray();
		if ($ID > 0 && is_array($arElement))
		{
			if ($USER->GetID() == $arElement["CREATED_BY"])
				$arCurrentUserGroups[] = "Author";
		}
		else
		{
			$arCurrentUserGroups[] = "Author";
		}

		if ($ID > 0)
		{
			$canWrite = CBPDocument::CanUserOperateDocument(
				CBPCanUserOperateOperation::WriteDocument,
				$USER->GetID(),
				array(MODULE_ID, ENTITY, $ID),
				array("AllUserGroups" => $arCurrentUserGroups, "DocumentStates" => $arDocumentStates)
			);
			$canRead = CBPDocument::CanUserOperateDocument(
				CBPCanUserOperateOperation::ReadDocument,
				$USER->GetID(),
				array(MODULE_ID, ENTITY, $ID),
				array("AllUserGroups" => $arCurrentUserGroups, "DocumentStates" => $arDocumentStates)
			);
		}
		else
		{
			$canWrite = CBPDocument::CanUserOperateDocumentType(
				CBPCanUserOperateOperation::WriteDocument,
				$USER->GetID(),
				array(MODULE_ID, ENTITY, DOCUMENT_TYPE),
				array("AllUserGroups" => $arCurrentUserGroups, "DocumentStates" => $arDocumentStates)
			);
			$canRead = false;
		}

		if (!$canWrite && !$canRead)
		{
			$error = new _CIBlockError(1, "ACCESS_DENIED", GetMessage("IBLOCK_ACCESS_DENIED_STATUS"));
			break;
		}
	}

	//Find out files properties
	$arFileProps = array();
	$propertyIterator = Iblock\PropertyTable::getList(array(
		'select' => array('ID'),
		'filter' => array('=IBLOCK_ID' => $IBLOCK_ID, '=PROPERTY_TYPE' => Iblock\PropertyTable::TYPE_FILE, '=ACTIVE' => 'Y')
	));
	while ($property = $propertyIterator->fetch())
		$arFileProps[] = $property['ID'];
	unset($property, $propertyIterator);

	//Assembly properties values from $_POST and $_FILES

	$PROP = array();
	if (isset($_POST['PROP']))
		$PROP = $_POST['PROP'];

	//Recover some user defined properties
	if(is_array($PROP))
	{
		foreach($PROP as $k1 => $val1)
		{
			if(is_array($val1))
			{
				foreach($val1 as $k2 => $val2)
				{
					$text_name = preg_replace("/([^a-z0-9])/is", "_", "PROP[".$k1."][".$k2."][VALUE][TEXT]");
					if(array_key_exists($text_name, $_POST))
					{
						$type_name = preg_replace("/([^a-z0-9])/is", "_", "PROP[".$k1."][".$k2."][VALUE][TYPE]");
						$PROP[$k1][$k2]["VALUE"] = array(
							"TEXT" => $_POST[$text_name],
							"TYPE" => $_POST[$type_name],
						);
					}
				}
			}
		}

		foreach($PROP as $k1 => $val1)
		{
			if(is_array($val1))
			{
				foreach($val1 as $k2 => $val2)
				{
					if(!is_array($val2))
						$PROP[$k1][$k2] = array("VALUE" => $val2);
				}
			}
		}
	}

	//transpose files array
	// [property id] [value id] = file array (name, type, tmp_name, error, size)
	$files = $_FILES["PROP"];
	if(is_array($files))
	{
		if(!is_array($PROP))
			$PROP = array();
		CAllFile::ConvertFilesToPost($_FILES["PROP"], $PROP);
	}

	foreach($arFileProps as $k1)
	{
		if (isset($PROP_del[$k1]) && is_array($PROP_del[$k1]))
		{
			foreach ($PROP_del[$k1] as $prop_value_id => $tmp)
			{
				if (!array_key_exists($prop_value_id, $PROP[$k1]))
					$PROP[$k1][$prop_value_id] = null;
			}
		}

		if (isset($PROP[$k1]) && is_array($PROP[$k1]))
		{
			foreach ($PROP[$k1] as $prop_value_id => $prop_value)
			{
				$PROP[$k1][$prop_value_id] = CIBlock::makeFilePropArray(
					$PROP[$k1][$prop_value_id],
					$PROP_del[$k1][$prop_value_id] === "Y",
					isset($_POST["DESCRIPTION_PROP"][$k1][$prop_value_id])? $_POST["DESCRIPTION_PROP"][$k1][$prop_value_id]: $_POST["PROP_descr"][$k1][$prop_value_id]
				);
			}
		}
	}

	$DESCRIPTION_PROP = $_POST["DESCRIPTION_PROP"];
	if(is_array($DESCRIPTION_PROP))
	{
		foreach($DESCRIPTION_PROP as $k1=>$val1)
		{
			foreach($val1 as $k2=>$val2)
			{
				if(is_set($PROP[$k1], $k2) && is_array($PROP[$k1][$k2]) && is_set($PROP[$k1][$k2], "DESCRIPTION"))
					$PROP[$k1][$k2]["DESCRIPTION"] = $val2;
				else
					$PROP[$k1][$k2] = array("VALUE"=>$PROP[$k1][$k2], "DESCRIPTION"=>$val2);
			}
		}
	}

	function _prop_value_id_cmp($a, $b)
	{
		if(substr($a, 0, 1)==="n")
		{
			$a = intval(substr($a, 1));
			if(substr($b, 0, 1)==="n")
			{
				$b = intval(substr($b, 1));
				if($a < $b)
					return -1;
				elseif($a > $b)
					return 1;
				else
					return 0;
			}
			else
			{
				return 1;
			}
		}
		else
		{
			if(substr($b, 0, 1)==="n")
			{
				return -1;
			}
			else
			{
				if(preg_match("/^(\d+):(\d+)$/", $a, $a_match))
					$a = intval($a_match[2]);
				else
					$a = intval($a);

				if(preg_match("/^(\d+):(\d+)$/", $b, $b_match))
					$b = intval($b_match[2]);
				else
					$b = intval($b);

				if($a < $b)
					return -1;
				elseif($a > $b)
					return 1;
				else
					return 0;
			}
		}
	}

	//Now reorder property values
	if(
		is_array($PROP)
		&& !empty($arFileProps)
		&& !class_exists('\Bitrix\Main\UI\FileInput', true)
	)
	{
		foreach($arFileProps as $id)
		{
			if(is_array($PROP[$id]))
				uksort($PROP[$id], "_prop_value_id_cmp");
		}
	}

	if(strlen($arIBlock["EDIT_FILE_BEFORE"])>0 && is_file($_SERVER["DOCUMENT_ROOT"].$arIBlock["EDIT_FILE_BEFORE"]))
	{
		include($_SERVER["DOCUMENT_ROOT"].$arIBlock["EDIT_FILE_BEFORE"]);
	}
	elseif(strlen($arIBTYPE["EDIT_FILE_BEFORE"])>0 && is_file($_SERVER["DOCUMENT_ROOT"].$arIBTYPE["EDIT_FILE_BEFORE"]))
	{
		include($_SERVER["DOCUMENT_ROOT"].$arIBTYPE["EDIT_FILE_BEFORE"]);
	}

	if (
		$bBizproc
		&& $canWrite
		&& $historyId <= 0
		&& $ID > 0
		&& $_SERVER['REQUEST_METHOD']=="GET"
		&& isset($_REQUEST["stop_bizproc"]) && strlen($_REQUEST["stop_bizproc"]) > 0
		&& check_bitrix_sessid()
	)
	{
		CBPDocument::TerminateWorkflow(
			$_REQUEST["stop_bizproc"],
			array(MODULE_ID, ENTITY, $ID),
			$ar
		);

		if (!empty($ar))
		{
			$str = "";
			foreach ($ar as $a)
				$str .= $a["message"];
			$error = new _CIBlockError(2, "STOP_BP_ERROR", $str);
		}
		else
		{
			LocalRedirect($APPLICATION->GetCurPageParam("", array("stop_bizproc", "sessid")));
		}
	}

	$changeTabs = false;
	if(
		$historyId <= 0
		&& $_SERVER['REQUEST_METHOD'] == 'GET'
		&& 0 < $ID
		&& check_bitrix_sessid()
	)
	{
		if (CCatalogAdminTools::changeTabs($IBLOCK_ID, $ID, $arMainCatalog))
		{
			$changeTabs = true;
			$arShowTabs['product_group'] = false;
			$aTabs = array_filter($aTabs, 'tabFilter');
			unset($_REQUEST['SUBPRODUCT_TYPE']);
		}
	}
	// save and apply
	// change $dontsave on cancel
	if (
		!$changeTabs
		&& $historyId <= 0
		&& $_SERVER['REQUEST_METHOD']=="POST"
		&& strlen($Update) > 0
		&& $view != "Y"
		&& (!$error)
		&& empty($dontsave)
	)
	{
		$DB->StartTransaction();

		if (!(check_bitrix_sessid() || $_SESSION['IBLOCK_CUSTOM_FORM']===true))
		{
			$strWarning .= GetMessage("IBLOCK_WRONG_SESSION")."<br>";
			$error = new _CIBlockError(2, "BAD_SAVE", $strWarning);
			$bVarsFromForm = true;
		}
		elseif ($WF=="Y" && $bWorkflow && intval($_POST["WF_STATUS_ID"])<=0)
			$strWarning .= GetMessage("IBLOCK_WRONG_WF_STATUS")."<br>";
		elseif ($WF=="Y" && $bWorkflow && CIBlockElement::WF_GetStatusPermission($_POST["WF_STATUS_ID"])<1)
			$strWarning .= GetMessage("IBLOCK_ACCESS_DENIED_STATUS")." [".$_POST["WF_STATUS_ID"]."]."."<br>";
		elseif (!$customTabber->Check())
		{
			if($ex = $APPLICATION->GetException())
				$strWarning .= $ex->GetString();
			else
				$strWarning .= "Error. ";
		}//if(!$customTabber->Check())
		else
		{
			$arCatalogItem = array(
				'IBLOCK_ID' => $IBLOCK_ID,
				'SECTION_ID' => 0,
				'ID' => $ID,
				'PRODUCT_ID' => (0 < $ID ? CIBlockElement::GetRealElement($ID) : 0)
			);
			if (
				$arShowTabs['catalog']
				&& file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/catalog/admin/templates/subproduct_edit_validator.php")
			)
			{
				// errors'll be appended to $strWarning;
				include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/catalog/admin/templates/subproduct_edit_validator.php");
			}
			if ($arShowTabs['product_group'])
			{
				CCatalogAdminProductSetEdit::setSkuFormParams(array('TYPE' => CCatalogProductSet::TYPE_GROUP));
				if (!CCatalogAdminProductSetEdit::checkFormValues($arCatalogItem))
				{
					$strWarning .= implode('<br>', CCatalogAdminProductSetEdit::getErrors());
				}
			}

			if ($bBizproc)
			{
				if($canWrite)
				{
					$arBizProcParametersValues = array();
					foreach ($arDocumentStates as $arDocumentState)
					{
						if (strlen($arDocumentState["ID"]) <= 0)
						{
							$arErrorsTmp = array();

							$arBizProcParametersValues[$arDocumentState["TEMPLATE_ID"]] = CBPDocument::StartWorkflowParametersValidate(
								$arDocumentState["TEMPLATE_ID"],
								$arDocumentState["TEMPLATE_PARAMETERS"],
								array(MODULE_ID, ENTITY, DOCUMENT_TYPE),
								$arErrorsTmp
							);

							if (!empty($arErrorsTmp))
							{
								foreach ($arErrorsTmp as $e)
									$strWarning .= $e["message"]."<br />";
							}
						}
					}
				}
				else
				{
					$strWarning .= GetMessage("IBLOCK_ACCESS_DENIED_STATUS")."<br />";
				}
			}

			if ($strWarning == '')
			{
				$bs = new CIBlockElement();

				$arPREVIEW_PICTURE = CIBlock::makeFileArray(
					array_key_exists("SUB_PREVIEW_PICTURE", $_FILES)? $_FILES["SUB_PREVIEW_PICTURE"]: $_REQUEST["SUB_PREVIEW_PICTURE"],
					${"SUB_PREVIEW_PICTURE_del"} === "Y",
					${"SUB_PREVIEW_PICTURE_descr"}
				);
				if ($arPREVIEW_PICTURE["error"] == 0)
					$arPREVIEW_PICTURE["COPY_FILE"] = "Y";

				$arDETAIL_PICTURE = CIBlock::makeFileArray(
					array_key_exists("SUB_DETAIL_PICTURE", $_FILES)? $_FILES["SUB_DETAIL_PICTURE"]: $_REQUEST["SUB_DETAIL_PICTURE"],
					${"SUB_DETAIL_PICTURE_del"} === "Y",
					${"SUB_DETAIL_PICTURE_descr"}
				);
				if ($arDETAIL_PICTURE["error"] == 0)
					$arDETAIL_PICTURE["COPY_FILE"] = "Y";

				$arFields = array(
					"ACTIVE" => $_POST["SUB_ACTIVE"],
					"MODIFIED_BY" => $USER->GetID(),
					"IBLOCK_ID" => $IBLOCK_ID,
					"ACTIVE_FROM" => $_POST["SUB_ACTIVE_FROM"],
					"ACTIVE_TO" => $_POST["SUB_ACTIVE_TO"],
					"SORT" => $_POST["SUB_SORT"],
					"NAME" => $_POST["SUB_NAME"],
					"CODE" => trim($_POST["SUB_CODE"], " \t\n\r"),
					"TAGS" => $_POST["SUB_TAGS"],
					"PREVIEW_PICTURE" => $arPREVIEW_PICTURE,
					"PREVIEW_TEXT" => $_POST["SUB_PREVIEW_TEXT"],
					"PREVIEW_TEXT_TYPE" => $_POST["SUB_PREVIEW_TEXT_TYPE"],
					"DETAIL_PICTURE" => $arDETAIL_PICTURE,
					"DETAIL_TEXT" => $_POST["SUB_DETAIL_TEXT"],
					"DETAIL_TEXT_TYPE" => $_POST["SUB_DETAIL_TEXT_TYPE"],
					"TMP_ID" => $strSubTMP_ID,
					"PROPERTY_VALUES" => $PROP,
				);
				if (COption::GetOptionString("iblock", "show_xml_id", "N")=="Y" && is_set($_POST, "SUB_XML_ID"))
					$arFields["XML_ID"] = trim($_POST["SUB_XML_ID"], " \t\n\r");

				if ($arIBlock["RIGHTS_MODE"] === "E" && CIBlockElementRights::UserHasRightTo($IBLOCK_ID, $ID, "element_rights_edit"))
				{
					if (is_array($_POST["SUB_RIGHTS"]))
						$arFields["RIGHTS"] = CIBlockRights::Post2Array($_POST["SUB_RIGHTS"]);
					else
						$arFields["RIGHTS"] = array();
				}

				if ($bWorkflow)
				{
					$arFields["WF_COMMENTS"] = $_POST["WF_COMMENTS"];
					if(intval($_POST["WF_STATUS_ID"])>0)
					{
						$arFields["WF_STATUS_ID"] = $_POST["WF_STATUS_ID"];
					}
				}

				if ($bBizproc)
				{
					$BP_HISTORY_NAME = $arFields["NAME"];
					if ($ID <= 0)
						$arFields["BP_PUBLISHED"] = "N";
				}

				if ($ID > 0)
				{
					$bCreateRecord = false;
					$res = $bs->Update($ID, $arFields, $WF=="Y", true, true);
				}
				else
				{
					$bCreateRecord = true;
					$ID = $bs->Add($arFields, $bWorkflow, true, true);
					$res = ($ID>0);
					$PARENT_ID = $ID;
				}

				if (!$res)
					$strWarning .= $bs->LAST_ERROR."<br>";

				if ($strWarning == '')
				{
					$arCatalogItem = array(
						'IBLOCK_ID' => $IBLOCK_ID,
						'SECTION_ID' => 0,
						'ID' => $ID,
						'PRODUCT_ID' => CIBlockElement::GetRealElement($ID)
					);
					if ($arShowTabs['catalog'])
					{
						include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/catalog/admin/templates/subproduct_edit_action.php");
					}
					if ($arShowTabs['product_group'])
					{
						CCatalogAdminProductSetEdit::setSkuFormParams(array('TYPE' => CCatalogProductSet::TYPE_GROUP));
						CCatalogAdminProductSetEdit::saveFormValues($arCatalogItem);
					}
				}
			} // if ($strWarning)

			if ($bBizproc)
			{
				if ($strWarning == '')
				{
					$arBizProcWorkflowId = array();
					foreach ($arDocumentStates as $arDocumentState)
					{
						if (strlen($arDocumentState["ID"]) <= 0)
						{
							$arErrorsTmp = array();

							$arBizProcWorkflowId[$arDocumentState["TEMPLATE_ID"]] = CBPDocument::StartWorkflow(
								$arDocumentState["TEMPLATE_ID"],
								array(MODULE_ID, ENTITY, $ID),
								$arBizProcParametersValues[$arDocumentState["TEMPLATE_ID"]],
								$arErrorsTmp
							);

							if (!empty($arErrorsTmp))
							{
								foreach ($arErrorsTmp as $e)
									$strWarning .= $e["message"]."<br />";
							}
						}
					}
				}

				if ($strWarning == '')
				{
					$bizprocIndex = intval($_REQUEST["bizproc_index"]);
					if ($bizprocIndex > 0)
					{
						for ($i = 1; $i <= $bizprocIndex; $i++)
						{
							$bpId = trim($_REQUEST["bizproc_id_".$i]);
							$bpTemplateId = intval($_REQUEST["bizproc_template_id_".$i]);
							$bpEvent = trim($_REQUEST["bizproc_event_".$i]);

							if (strlen($bpEvent) > 0)
							{
								if (strlen($bpId) > 0)
								{
									if (!array_key_exists($bpId, $arDocumentStates))
										continue;
								}
								else
								{
									if (!array_key_exists($bpTemplateId, $arDocumentStates))
										continue;
									$bpId = $arBizProcWorkflowId[$bpTemplateId];
								}

								$arErrorTmp = array();
								CBPDocument::SendExternalEvent(
									$bpId,
									$bpEvent,
									array("Groups" => $arCurrentUserGroups, "User" => $USER->GetID()),
									$arErrorTmp
								);

								if (!empty($arErrorsTmp))
								{
									foreach ($arErrorsTmp as $e)
										$strWarning .= $e["message"]."<br />";
								}
							}
						}
					}

					$arDocumentStates = null;
					CBPDocument::AddDocumentToHistory(array(MODULE_ID, ENTITY, $ID), $BP_HISTORY_NAME, $USER->GetID());
				}
			}
		}

		if($strWarning == '')
		{
			if(!$customTabber->Action())
			{
				if ($ex = $APPLICATION->GetException())
					$strWarning .= $ex->GetString();
				else
					$strWarning .= "Error. ";
			}
		}

		if($strWarning != '')
		{
			$error = new _CIBlockError(2, "BAD_SAVE", $strWarning);
			$bVarsFromForm = true;
			$DB->Rollback();
		}
		else
		{
			if($bWorkflow)
				CIBlockElement::WF_UnLock($ID);

			$arFields['ID'] = $ID;
			if(function_exists('BXIBlockAfterSave'))
				BXIBlockAfterSave($arFields);

			$DB->Commit();

			// i have only savebtn and cancel
			if ((true == isset($_POST['Update'])) && (0 < strlen($_POST['Update'])))
			{
				?><script type="text/javascript">
				top.BX.closeWait(); top.BX.WindowManager.Get().AllowClose(); top.BX.WindowManager.Get().Close();
				if (!!top.ReloadSubList)
					top.ReloadSubList();
				</script><?
				die();
			}
		}
	}

	// cancel vmesto dontsave
	if (!$changeTabs && !empty($dontsave) && check_bitrix_sessid())
	{
		if($bWorkflow)
			CIBlockElement::WF_UnLock($ID);

		?><script type="text/javascript">
		top.BX.closeWait(); top.BX.WindowManager.Get().AllowClose(); top.BX.WindowManager.Get().Close();
		</script><?
		die();
	}

}while(false);

if($error && $error->err_level==1)
{
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
	CAdminMessage::ShowOldStyleError($error->GetErrorText());
}
else
{
	if(!$arIBlock["ELEMENT_NAME"])
		$arIBlock["ELEMENT_NAME"] = $arIBTYPE["ELEMENT_NAME"]? $arIBTYPE["ELEMENT_NAME"]: GetMessage("IBEL_E_IBLOCK_ELEMENT");
	if(!$arIBlock["SECTIONS_NAME"])
		$arIBlock["SECTIONS_NAME"] = $arIBTYPE["SECTION_NAME"]? $arIBTYPE["SECTION_NAME"]: GetMessage("IBEL_E_IBLOCK_SECTIONS");

	ClearVars("str_");
	ClearVars("str_prev_");
	ClearVars("prn_");
	$str_ACTIVE="Y";
	$str_SORT="500";
	$str_DETAIL_TEXT_TYPE="html";
	$str_PREVIEW_TEXT_TYPE="html";

	if(!$error && $bWorkflow && $view!="Y")
	{
		if(!$bSubCopy)
			CIBlockElement::WF_Lock($ID);
		else
			CIBlockElement::WF_UnLock($ID);
	}

	if($historyId <= 0 && $view=="Y")
	{
		$WF_ID = $ID;
		$ID = CIBlockElement::GetRealElement($ID);

		if($PREV_ID)
		{
			$prev_result = CIBlockElement::GetByID($PREV_ID);
			$prev_arElement = $prev_result->ExtractFields("str_prev_");
			if(!$prev_arElement)
				$PREV_ID = 0;
		}
	}

	$str_ACTIVE = $arIBlock["FIELDS"]["ACTIVE"]["DEFAULT_VALUE"] === "N"? "N": "Y";
	$str_NAME = htmlspecialcharsbx($arIBlock["FIELDS"]["NAME"]["DEFAULT_VALUE"]);
	if ('' != $strProductName)
		$str_NAME = htmlspecialcharsbx($strProductName);

	$str_PREVIEW_TEXT_TYPE = $arIBlock["FIELDS"]["PREVIEW_TEXT_TYPE"]["DEFAULT_VALUE"] !== "html"? "text": "html";
	$str_PREVIEW_TEXT = htmlspecialcharsbx($arIBlock["FIELDS"]["PREVIEW_TEXT"]["DEFAULT_VALUE"]);
	$str_DETAIL_TEXT_TYPE = $arIBlock["FIELDS"]["DETAIL_TEXT_TYPE"]["DEFAULT_VALUE"] !== "html"? "text": "html";
	$str_DETAIL_TEXT = htmlspecialcharsbx($arIBlock["FIELDS"]["DETAIL_TEXT"]["DEFAULT_VALUE"]);

	if ($historyId > 0)
	{
		$view = "Y";
		foreach ($arResult["DOCUMENT"]["FIELDS"] as $k => $v)
			${"str_".$k} = $v;

	}
	else
	{
		$result = CIBlockElement::GetByID($WF_ID);

		if ($arElement = $result->ExtractFields("str_"))
		{
		}
		else
		{
			$WF_ID=0;
			$ID=0;
		}
	}

	if ($bSubCopy)
		$str_XML_ID = "";

	if($ID > 0 && !$bSubCopy)
	{
		if($view=="Y")
			$APPLICATION->SetTitle($arIBlock["NAME"].": ".$arIBlock["ELEMENT_NAME"].": ".$arElement["NAME"]." - ".GetMessage("IBLOCK_ELEMENT_EDIT_VIEW"));
		else
			$APPLICATION->SetTitle($arIBlock["NAME"].": ".$arIBlock["ELEMENT_NAME"].": ".$arElement["NAME"]." - ".GetMessage("IBLOCK_EDIT_TITLE"));
	}
	else
	{
		$APPLICATION->SetTitle($arIBlock["NAME"].": ".$arIBlock["ELEMENT_NAME"].": ".GetMessage("IBLOCK_NEW_TITLE"));
	}

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

	$tabControl = new CAdminSubForm($bCustomForm? "tabControl_sub": "form_subelement_".$IBLOCK_ID, $aTabs, true, false, $arListUrl, BX_SUB_SETTINGS);

	if($bVarsFromForm)
	{
		if(!isset($ACTIVE)) $ACTIVE = "N"; //It is checkbox. So it is not set in POST.
		$DB->InitTableVarsForEdit("b_iblock_element", "", "str_");
	}

	$arPROP_tmp = array();
	$properties = CIBlockProperty::GetList(
		array("sort"=>"asc", "name"=>"asc"),
		array("IBLOCK_ID"=>$IBLOCK_ID, "ACTIVE"=>"Y", "CHECK_PERMISSIONS"=>"N")
	);
	while($prop_fields = $properties->Fetch())
	{
		$prop_values = array();
		$prop_values_with_descr = array();
		if($bVarsFromForm)
		{
			if($prop_fields["PROPERTY_TYPE"]=="F")
			{
				$db_prop_values = CIBlockElement::GetProperty($IBLOCK_ID, $WF_ID, "id", "asc", array("ID"=>$prop_fields["ID"], "EMPTY"=>"N"));
				while($res = $db_prop_values->Fetch())
				{
					$prop_values[$res["PROPERTY_VALUE_ID"]] = $res["VALUE"];
					$prop_values_with_descr[$res["PROPERTY_VALUE_ID"]] = array("VALUE"=>$res["VALUE"],"DESCRIPTION"=>$res["DESCRIPTION"]);
				}
			}
			elseif(is_array($PROP))
			{
				if(array_key_exists($prop_fields["ID"], $PROP))
					$prop_values = $PROP[$prop_fields["ID"]];
				else
					$prop_values = $PROP[$prop_fields["CODE"]];
				$prop_values_with_descr = $prop_values;
			}
			else
			{
				$prop_values = "";
				$prop_values_with_descr = $prop_values;
			}
		}
		else
		{
			if ($historyId > 0)
			{
				$vx = $arResult["DOCUMENT"]["PROPERTIES"][(strlen(trim($prop_fields["CODE"])) > 0) ? $prop_fields["CODE"] : $prop_fields["ID"]];

				$prop_values = array();
				if (is_array($vx["VALUE"]) && is_array($vx["DESCRIPTION"]))
				{
					for ($i = 0, $cnt = count($vx["VALUE"]); $i < $cnt; $i++)
						$prop_values[] = array("VALUE" => $vx["VALUE"][$i], "DESCRIPTION" => $vx["DESCRIPTION"][$i]);
				}
				else
				{
					$prop_values[] = array("VALUE" => $vx["VALUE"], "DESCRIPTION" => $vx["DESCRIPTION"]);
				}

				$prop_values_with_descr = $prop_values;
			}
			elseif($ID>0)
			{
				$db_prop_values = CIBlockElement::GetProperty($IBLOCK_ID, $WF_ID, "id", "asc", array("ID"=>$prop_fields["ID"], "EMPTY"=>"N"));
				while($res = $db_prop_values->Fetch())
				{
					if($res["WITH_DESCRIPTION"]=="Y")
						$prop_values[$res["PROPERTY_VALUE_ID"]] = array("VALUE"=>$res["VALUE"], "DESCRIPTION"=>$res["DESCRIPTION"]);
					else
						$prop_values[$res["PROPERTY_VALUE_ID"]] = $res["VALUE"];
					$prop_values_with_descr[$res["PROPERTY_VALUE_ID"]] = array("VALUE"=>$res["VALUE"], "DESCRIPTION"=>$res["DESCRIPTION"]);
				}
			}
		}

		$prop_fields["VALUE"] = $prop_values;
		$prop_fields["~VALUE"] = $prop_values_with_descr;
		if(strlen(trim($prop_fields["CODE"]))>0)
			$arPROP_tmp[$prop_fields["CODE"]] = $prop_fields;
		else
			$arPROP_tmp[$prop_fields["ID"]] = $prop_fields;
	}
	$PROP = $arPROP_tmp;

	if (0 == $ID)
	{
		foreach ($PROP as $strCode => $arProperty)
		{
			if ($arSubCatalog['SKU_PROPERTY_ID'] == $arProperty['ID'])
			{
				$arProperty['VALUE'] = array(
					'n0' => array(
						'VALUE' => $intProductID,
					),
				);
				$arProperty['~VALUE'] = $arProperty['VALUE'];
				$PROP[$strCode] = $arProperty;
				break;
			}
		}
	}

	if($error)
		CAdminMessage::ShowOldStyleError($error->GetErrorText());

	$bFileman = CModule::IncludeModule("fileman");
	$arTranslit = $arIBlock["FIELDS"]["CODE"]["DEFAULT_VALUE"];
	$bLinked = (!strlen($str_TIMESTAMP_X) || $bSubCopy) && $_POST["linked_state"]!=='N';

	//////////////////////////
	//START of the custom form
	//////////////////////////

	//We have to explicitly call calendar and editor functions because
	//first output may be discarded by form settings

	$tabControl->BeginPrologContent();
	CJSCore::Init(array('date'));

	if(COption::GetOptionString("iblock", "use_htmledit", "Y")=="Y" && $bFileman)
	{
		//TODO:This dirty hack will be replaced by special method like calendar do
		echo '<div style="display:none">';
		CFileMan::AddHTMLEditorFrame(
			"SOME_TEXT",
			"",
			"SOME_TEXT_TYPE",
			"text",
			array(
				'height' => 450,
				'width' => '100%'
			),
			"N",
			0,
			"",
			"",
			$arIBlock["LID"]
		);
		echo '</div>';
	}

	if($arTranslit["TRANSLITERATION"] == "Y")
	{
		CJSCore::Init(array('window','translit'));
		?>
		<script type="text/javascript">
		var linked=<?if ($bLinked) echo 'true'; else echo 'false';?>;
		function set_linked()
		{
			linked=!linked;

			var name_link = document.getElementById('sub_name_link');
			if(name_link)
			{
				if(linked)
					name_link.src='/bitrix/themes/.default/icons/iblock/link.gif';
				else
					name_link.src='/bitrix/themes/.default/icons/iblock/unlink.gif';
			}
			var code_link = document.getElementById('sub_code_link');
			if(code_link)
			{
				if(linked)
					code_link.src='/bitrix/themes/.default/icons/iblock/link.gif';
				else
					code_link.src='/bitrix/themes/.default/icons/iblock/unlink.gif';
			}
			var linked_state = document.getElementById('linked_state');
			if(linked_state)
			{
				if(linked)
					linked_state.value='Y';
				else
					linked_state.value='N';
			}
		}
		var oldValue = '';
		function transliterate()
		{
			if(linked)
			{
				var from = document.getElementById('SUB_NAME');
				var to = document.getElementById('SUB_CODE');
				if(from && to && oldValue != from.value)
				{
					BX.translit(from.value, {
						'max_len' : <?echo intval($arTranslit['TRANS_LEN'])?>,
						'change_case' : '<?echo $arTranslit['TRANS_CASE']?>',
						'replace_space' : '<?echo $arTranslit['TRANS_SPACE']?>',
						'replace_other' : '<?echo $arTranslit['TRANS_OTHER']?>',
						'delete_repeat_replace' : <?echo $arTranslit['TRANS_EAT'] == 'Y'? 'true': 'false'?>,
						'use_google' : <?echo $arTranslit['USE_GOOGLE'] == 'Y'? 'true': 'false'?>,
						'callback' : function(result){to.value = result; setTimeout('transliterate()', 250);}
					});
					oldValue = from.value;
				}
				else
				{
					setTimeout('transliterate()', 250);
				}
			}
			else
			{
				setTimeout('transliterate()', 250);
			}
		}
		transliterate();
		</script>
		<?
	}
	else
	{
		CJSCore::Init(array('window'));
	}

	$tabControl->EndPrologContent();

	$tabControl->BeginEpilogContent();
echo bitrix_sessid_post();
echo GetFilterHiddens("find_");?>
<input type="hidden" name="linked_state" id="linked_state" value="<?if($bLinked) echo 'Y'; else echo 'N';?>">
<input type="hidden" name="Update" value="Y">
<input type="hidden" name="from" value="<?echo htmlspecialcharsbx($from)?>">
<input type="hidden" name="WF" value="<?echo htmlspecialcharsbx($WF)?>">
<input type="hidden" name="return_url" value="<?echo htmlspecialcharsbx($return_url)?>">
<?if ($ID>0 && !$bSubCopy)
{
	?><input type="hidden" name="ID" value="<?echo $ID?>"><?
}
if ($bSubCopy)
{
	?><input type="hidden" name="copyID" value="<? echo $ID; ?>"><?
}?>
<input type="hidden" name="IBLOCK_SECTION_ID" value="<?echo intval($IBLOCK_SECTION_ID)?>">
<input type="hidden" name="PRODUCT_ID" value="<? echo $intProductID; ?>">
<input type="hidden" name="TMP_ID" value="<?echo htmlspecialcharsbx($strSubTMP_ID)?>">
<?
CCatalogAdminTools::showFormParams();
$tabControl->EndEpilogContent();

$customTabber->SetErrorState($bVarsFromForm);
$tabControl->AddTabs($customTabber);
$strFormAction = CIBlock::GetAdminSubElementEditLink(
	$IBLOCK_ID,
	$intProductID,
	($ID>0 && !$bSubCopy ? $ID : 0),
	array('WF' => $WF, 'find_section_section' => (int)$find_section_section),
	'',
	!BX_SUB_SETTINGS
);

$tabControl->Begin(array(
	"FORM_ACTION" => $strFormAction,
));

$tabControl->BeginNextFormTab();
	if($ID > 0 && !$bSubCopy):
		$p = CIblockElement::GetByID($ID);
		$pr = $p->ExtractFields("prn_");
	endif;
$tabControl->AddCheckBoxField("SUB_ACTIVE", GetMessage("IBLOCK_FIELD_ACTIVE").":", false, "Y", $str_ACTIVE=="Y");
$tabControl->BeginCustomField("SUB_ACTIVE_FROM", GetMessage("IBLOCK_FIELD_ACTIVE_PERIOD_FROM"), $arIBlock["FIELDS"]["ACTIVE_FROM"]["IS_REQUIRED"] === "Y");
?>
<tr id="tr_SUB_ACTIVE_FROM">
	<td><?echo $tabControl->GetCustomLabelHTML()?>:</td>
	<td><?echo CAdminCalendar::CalendarDate("SUB_ACTIVE_FROM", $str_ACTIVE_FROM, 19, true)?></td>
</tr>
<?
$tabControl->EndCustomField("SUB_ACTIVE_FROM", '<input type="hidden" id="SUB_ACTIVE_FROM" name="SUB_ACTIVE_FROM" value="'.$str_ACTIVE_FROM.'">');
$tabControl->BeginCustomField("SUB_ACTIVE_TO", GetMessage("IBLOCK_FIELD_ACTIVE_PERIOD_TO"), $arIBlock["FIELDS"]["ACTIVE_TO"]["IS_REQUIRED"] === "Y");
?>
<tr id="tr_SUB_ACTIVE_TO">
	<td><?echo $tabControl->GetCustomLabelHTML()?>:</td>
	<td><?echo CAdminCalendar::CalendarDate("SUB_ACTIVE_TO", $str_ACTIVE_TO, 19, true)?></td>
</tr>

<?
$tabControl->EndCustomField("SUB_ACTIVE_TO", '<input type="hidden" id="SUB_ACTIVE_TO" name="SUB_ACTIVE_TO" value="'.$str_ACTIVE_TO.'">');

if ($arTranslit["TRANSLITERATION"] == "Y")
{
	$tabControl->BeginCustomField("SUB_NAME", GetMessage("IBLOCK_FIELD_NAME").":", true);
	?><tr id="tr_SUB_NAME">
	<td><?echo $tabControl->GetCustomLabelHTML()?></td>
	<td style="white-space: nowrap;">
		<input type="text" size="50" name="SUB_NAME" id="SUB_NAME" maxlength="255" value="<?echo $str_NAME?>"><image id="sub_name_link" title="<?echo GetMessage("IBEL_E_LINK_TIP")?>" class="linked" src="/bitrix/themes/.default/icons/iblock/<?if($bLinked) echo 'link.gif'; else echo 'unlink.gif';?>" onclick="set_linked()" />
	</td>
</tr><?
	$tabControl->EndCustomField("SUB_NAME", '<input type="hidden" name="SUB_NAME" id="SUB_NAME" value="'.$str_NAME.'">');

	$tabControl->BeginCustomField("SUB_CODE", GetMessage("IBLOCK_FIELD_CODE").":", $arIBlock["FIELDS"]["CODE"]["IS_REQUIRED"] === "Y");
	?><tr id="tr_SUB_CODE">
	<td><?echo $tabControl->GetCustomLabelHTML()?></td>
	<td style="white-space: nowrap;">
		<input type="text" size="50" name="SUB_CODE" id="SUB_CODE" maxlength="255" value="<?echo $str_CODE?>"><image id="sub_code_link" title="<?echo GetMessage("IBEL_E_LINK_TIP")?>" class="linked" src="/bitrix/themes/.default/icons/iblock/<?if($bLinked) echo 'link.gif'; else echo 'unlink.gif';?>" onclick="set_linked()" />
	</td>
</tr><?
	$tabControl->EndCustomField("SUB_CODE", '<input type="hidden" name="SUB_CODE" id="SUB_CODE" value="'.$str_CODE.'">');
}
else
{
	$tabControl->AddEditField("SUB_NAME", GetMessage("IBLOCK_FIELD_NAME").":", true, array("size" => 50, "maxlength" => 255), $str_NAME);
	$tabControl->AddEditField("SUB_CODE", GetMessage("IBLOCK_FIELD_CODE").":", $arIBlock["FIELDS"]["CODE"]["IS_REQUIRED"] === "Y", array("size" => 20, "maxlength" => 255), $str_CODE);
}

if (COption::GetOptionString("iblock", "show_xml_id", "N")=="Y")
	$tabControl->AddEditField("SUB_XML_ID", GetMessage("IBLOCK_FIELD_XML_ID").":", $arIBlock["FIELDS"]["XML_ID"]["IS_REQUIRED"] === "Y", array("size" => 20, "maxlength" => 255, "id" => "SUB_XML_ID"), $str_XML_ID);

$tabControl->AddEditField("SUB_SORT", GetMessage("IBLOCK_FIELD_SORT").":", $arIBlock["FIELDS"]["SORT"]["IS_REQUIRED"] === "Y", array("size" => 7, "maxlength" => 10), $str_SORT);

if(!empty($PROP)):
	$tabControl->AddSection("IBLOCK_ELEMENT_PROP_VALUE", GetMessage("IBLOCK_ELEMENT_PROP_VALUE"));
	foreach($PROP as $prop_code=>$prop_fields):
			$prop_values = $prop_fields["VALUE"];
			$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
		if ($arSubCatalog['SKU_PROPERTY_ID'] != $prop_fields['ID'])
		{

			?>
			<tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>">
				<td><?echo $tabControl->GetCustomLabelHTML();?>:</td>
				<td><?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0)), $bVarsFromForm, 50000, $tabControl->GetFormName());?></td>
			</tr>
			<?
			$hidden = "";
			if(!is_array($prop_fields["~VALUE"]))
				$values = array();
			else
				$values = $prop_fields["~VALUE"];
			$start = 1;
			foreach($values as $key=>$val)
			{
				if($bSubCopy)
				{
					$key = "n".$start;
					$start++;
				}

				if(is_array($val) && array_key_exists("VALUE",$val))
				{
					$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
					$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
				}
				else
				{
					$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
					$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
				}
			}
		}
		else
		{
			$hidden = "";
			if(!is_array($prop_fields["~VALUE"]))
				$values = array();
			else
				$values = $prop_fields["~VALUE"];
			$start = 1;
			foreach($values as $key=>$val)
			{
				if($bSubCopy)
				{
					$key = "n".$start;
					$start++;
				}

				if(is_array($val) && array_key_exists("VALUE",$val))
				{
					$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
					$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
				}
				else
				{
					$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
					$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
				}
			}
				echo $hidden;
		}
			$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);

	endforeach;
endif;

$tabControl->BeginNextFormTab();
$tabControl->BeginCustomField("SUB_PREVIEW_PICTURE", GetMessage("IBLOCK_FIELD_PREVIEW_PICTURE"), $arIBlock["FIELDS"]["PREVIEW_PICTURE"]["IS_REQUIRED"] === "Y");
if($bVarsFromForm && !array_key_exists("SUB_PREVIEW_PICTURE", $_REQUEST) && $arElement)
	$str_PREVIEW_PICTURE = intval($arElement["PREVIEW_PICTURE"]);
?>
	<tr id="tr_SUB_PREVIEW_PICTURE" class="adm-detail-file-row">
		<td width="40%"><?echo $tabControl->GetCustomLabelHTML()?>:</td>
		<td width="60%">
			<?if($historyId > 0):?>
				<?echo CFileInput::Show("SUB_PREVIEW_PICTURE", $str_PREVIEW_PICTURE, array(
					"IMAGE" => "Y",
					"PATH" => "Y",
					"FILE_SIZE" => "Y",
					"DIMENSIONS" => "Y",
					"IMAGE_POPUP" => "Y",
					"MAX_SIZE" => array(
						"W" => COption::GetOptionString("iblock", "detail_image_size"),
						"H" => COption::GetOptionString("iblock", "detail_image_size"),
					),
				));
				?>
			<?else:?>
				<?
				if (class_exists('\Bitrix\Main\UI\FileInput', true))
				{
					echo \Bitrix\Main\UI\FileInput::createInstance(array(
						"name" => "SUB_PREVIEW_PICTURE",
						"description" => true,
						"upload" => true,
						"allowUpload" => "I",
						"medialib" => true,
						"fileDialog" => true,
						"cloud" => true,
						"delete" => true,
						"maxCount" => 1
					))->show($str_PREVIEW_PICTURE);
				}
				else
				{
					?>
					<?echo CFileInput::Show("SUB_PREVIEW_PICTURE", ($ID > 0 && !$bSubCopy ? $str_PREVIEW_PICTURE : 0),
					array(
						"IMAGE" => "Y",
						"PATH" => "Y",
						"FILE_SIZE" => "Y",
						"DIMENSIONS" => "Y",
						"IMAGE_POPUP" => "Y",
						"MAX_SIZE" => array(
							"W" => COption::GetOptionString("iblock", "detail_image_size"),
							"H" => COption::GetOptionString("iblock", "detail_image_size"),
						),
					), array(
						'upload' => true,
						'medialib' => true,
						'file_dialog' => true,
						'cloud' => true,
						'del' => true,
						'description' => true,
					)
				);
				}
			endif?>
		</td>
	</tr>
<?
$tabControl->EndCustomField("SUB_PREVIEW_PICTURE", "");
$tabControl->BeginCustomField("SUB_PREVIEW_TEXT", GetMessage("IBLOCK_FIELD_PREVIEW_TEXT"), $arIBlock["FIELDS"]["PREVIEW_TEXT"]["IS_REQUIRED"] === "Y");
?>
	<tr class="heading" id="tr_SUB_PREVIEW_TEXT_LABEL">
		<td colspan="2"><?echo $tabControl->GetCustomLabelHTML()?></td>
	</tr>
	<?if($ID && $PREV_ID && $bWorkflow):?>
	<tr id="tr_SUB_PREVIEW_TEXT_DIFF">
		<td colspan="2">
			<div style="width:95%;background-color:white;border:1px solid black;padding:5px">
				<?echo getDiff($prev_arElement["PREVIEW_TEXT"], $arElement["PREVIEW_TEXT"])?>
			</div>
		</td>
	</tr>
	<?elseif(COption::GetOptionString("iblock", "use_htmledit", "Y")=="Y" && $bFileman):?>
	<tr id="tr_SUB_PREVIEW_TEXT_EDITOR">
		<td colspan="2" align="center">
			<?CFileMan::AddHTMLEditorFrame(
				"SUB_PREVIEW_TEXT",
				$str_PREVIEW_TEXT,
				"SUB_PREVIEW_TEXT_TYPE",
				$str_PREVIEW_TEXT_TYPE,
				//300,
				array(
					'height' => 450,
					'width' => '100%',
				),
				"N",
				0,
				"",
				"",
				$arIBlock["LID"],
				true,
				false,
				array(
					'toolbarConfig' => CFileman::GetEditorToolbarConfig("iblock_".(defined('BX_SUB_SETTINGS') && BX_SUB_SETTINGS == true ? 'admin' : 'public')),
					'saveEditorKey' => $IBLOCK_ID,
					'hideTypeSelector' => $arIBlock["FIELDS"]["PREVIEW_TEXT_TYPE_ALLOW_CHANGE"]["DEFAULT_VALUE"] === "N",
				)
			);?>
		</td>
	</tr>
	<?else:?>
	<tr id="tr_SUB_PREVIEW_TEXT_TYPE">
		<td><?echo GetMessage("IBLOCK_DESC_TYPE")?></td>
		<td><input type="radio" name="SUB_PREVIEW_TEXT_TYPE" id="SUB_PREVIEW_TEXT_TYPE_text" value="text"<?if($str_PREVIEW_TEXT_TYPE!="html")echo " checked"?>> <label for="SUB_PREVIEW_TEXT_TYPE_text"><?echo GetMessage("IBLOCK_DESC_TYPE_TEXT")?></label> / <input type="radio" name="SUB_PREVIEW_TEXT_TYPE" id="SUB_PREVIEW_TEXT_TYPE_html" value="html"<?if($str_PREVIEW_TEXT_TYPE=="html")echo " checked"?>> <label for="SUB_PREVIEW_TEXT_TYPE_html"><?echo GetMessage("IBLOCK_DESC_TYPE_HTML")?></label></td>
	</tr>
	<tr id="tr_SUB_PREVIEW_TEXT">
		<td colspan="2" align="center">
			<textarea cols="60" rows="10" name="SUB_PREVIEW_TEXT" style="width:100%"><?echo $str_PREVIEW_TEXT?></textarea>
		</td>
	</tr>
	<?endif;
$tabControl->EndCustomField("SUB_PREVIEW_TEXT",
	'<input type="hidden" name="SUB_PREVIEW_TEXT" value="'.$str_PREVIEW_TEXT.'">'.
	'<input type="hidden" name="SUB_PREVIEW_TEXT_TYPE" value="'.$str_PREVIEW_TEXT_TYPE.'">'
);
$tabControl->BeginNextFormTab();
$tabControl->BeginCustomField("SUB_DETAIL_PICTURE", GetMessage("IBLOCK_FIELD_DETAIL_PICTURE"), $arIBlock["FIELDS"]["DETAIL_PICTURE"]["IS_REQUIRED"] === "Y");
if($bVarsFromForm && !array_key_exists("SUB_DETAIL_PICTURE", $_REQUEST) && $arElement)
	$str_DETAIL_PICTURE = intval($arElement["DETAIL_PICTURE"]);
?>
	<tr id="tr_SUB_DETAIL_PICTURE" class="adm-detail-file-row">
		<td width="40%"><?echo $tabControl->GetCustomLabelHTML()?>:</td>
		<td width="60%">
			<?if($historyId > 0):?>
				<?echo CFileInput::Show("SUB_DETAIL_PICTURE", $str_DETAIL_PICTURE, array(
					"IMAGE" => "Y",
					"PATH" => "Y",
					"FILE_SIZE" => "Y",
					"DIMENSIONS" => "Y",
					"IMAGE_POPUP" => "Y",
					"MAX_SIZE" => array(
						"W" => COption::GetOptionString("iblock", "detail_image_size"),
						"H" => COption::GetOptionString("iblock", "detail_image_size"),
					),
				));
				?>
			<?else:?>
				<?
				if (class_exists('\Bitrix\Main\UI\FileInput', true))
				{
					echo \Bitrix\Main\UI\FileInput::createInstance(array(
						"name" => "SUB_DETAIL_PICTURE",
						"description" => true,
						"upload" => true,
						"allowUpload" => "I",
						"medialib" => true,
						"fileDialog" => true,
						"cloud" => true,
						"delete" => true,
						"maxCount" => 1
					))->show($str_DETAIL_PICTURE);
				}
				else
				{
					?>
					<?echo CFileInput::Show("SUB_DETAIL_PICTURE", ($ID > 0 && !$bSubCopy ? $str_DETAIL_PICTURE : 0),
					array(
						"IMAGE" => "Y",
						"PATH" => "Y",
						"FILE_SIZE" => "Y",
						"DIMENSIONS" => "Y",
						"IMAGE_POPUP" => "Y",
						"MAX_SIZE" => array(
							"W" => COption::GetOptionString("iblock", "detail_image_size"),
							"H" => COption::GetOptionString("iblock", "detail_image_size"),
						),
					), array(
						'upload' => true,
						'medialib' => true,
						'file_dialog' => true,
						'cloud' => true,
						'del' => true,
						'description' => true,
					)
				);
				}
				?>
			<?endif?>
		</td>
	</tr>
<?
$tabControl->EndCustomField("SUB_DETAIL_PICTURE", "");
$tabControl->BeginCustomField("SUB_DETAIL_TEXT", GetMessage("IBLOCK_FIELD_DETAIL_TEXT"), $arIBlock["FIELDS"]["DETAIL_TEXT"]["IS_REQUIRED"] === "Y");
?>
	<tr class="heading" id="tr_SUB_DETAIL_TEXT_LABEL">
		<td colspan="2"><?echo $tabControl->GetCustomLabelHTML()?></td>
	</tr>
	<?if($ID && $PREV_ID && $bWorkflow):?>
	<tr id="tr_SUB_DETAIL_TEXT_DIFF">
		<td colspan="2">
			<div style="width:95%;background-color:white;border:1px solid black;padding:5px">
				<?echo getDiff($prev_arElement["DETAIL_TEXT"], $arElement["DETAIL_TEXT"])?>
			</div>
		</td>
	</tr>
	<?elseif(COption::GetOptionString("iblock", "use_htmledit", "Y")=="Y" && $bFileman):?>
	<tr id="tr_SUB_DETAIL_TEXT_EDITOR">
		<td colspan="2" align="center">
			<?CFileMan::AddHTMLEditorFrame(
				"SUB_DETAIL_TEXT",
				$str_DETAIL_TEXT,
				"SUB_DETAIL_TEXT_TYPE",
				$str_DETAIL_TEXT_TYPE,
				array(
						'height' => 450,
						'width' => '100%',
				),
				"N",
				0,
				"",
				"",
				$arIBlock["LID"],
				true,
				false,
				array(
					'toolbarConfig' => CFileman::GetEditorToolbarConfig("iblock_".(defined('BX_SUB_SETTINGS') && BX_SUB_SETTINGS == true ? 'admin' : 'public')),
					'saveEditorKey' => $IBLOCK_ID,
					'hideTypeSelector' => $arIBlock["FIELDS"]["DETAIL_TEXT_TYPE_ALLOW_CHANGE"]["DEFAULT_VALUE"] === "N",
				)
			);
		?></td>
	</tr>
	<?else:?>
	<tr id="tr_SUB_DETAIL_TEXT_TYPE">
		<td><?echo GetMessage("IBLOCK_DESC_TYPE")?></td>
		<td><input type="radio" name="SUB_DETAIL_TEXT_TYPE" id="SUB_DETAIL_TEXT_TYPE_text" value="text"<?if($str_DETAIL_TEXT_TYPE!="html")echo " checked"?>> <label for="SUB_DETAIL_TEXT_TYPE_text"><?echo GetMessage("IBLOCK_DESC_TYPE_TEXT")?></label> / <input type="radio" name="SUB_DETAIL_TEXT_TYPE" id="SUB_DETAIL_TEXT_TYPE_html" value="html"<?if($str_DETAIL_TEXT_TYPE=="html")echo " checked"?>> <label for="SUB_DETAIL_TEXT_TYPE_html"><?echo GetMessage("IBLOCK_DESC_TYPE_HTML")?></label></td>
	</tr>
	<tr id="tr_SUB_DETAIL_TEXT">
		<td colspan="2" align="center">
			<textarea cols="60" rows="20" name="SUB_DETAIL_TEXT" style="width:100%"><?echo $str_DETAIL_TEXT?></textarea>
		</td>
	</tr>
	<?endif?>
<?
$tabControl->EndCustomField("SUB_DETAIL_TEXT",
	'<input type="hidden" name="SUB_DETAIL_TEXT" value="'.$str_DETAIL_TEXT.'">'.
	'<input type="hidden" name="SUB_DETAIL_TEXT_TYPE" value="'.$str_DETAIL_TEXT_TYPE.'">'
);

$tabControl->BeginNextFormTab();

$tabControl->AddSection("SUB_SEO_ADDITIONAL", GetMessage("IBLOCK_EL_TAB_MO"));

$tabControl->BeginCustomField("SUB_TAGS", GetMessage("IBLOCK_FIELD_TAGS").":", $arIBlock["FIELDS"]["TAGS"]["IS_REQUIRED"] === "Y");
?>
	<tr id="tr_SUB_TAGS">
		<td><?echo $tabControl->GetCustomLabelHTML()?><br><?echo GetMessage("IBLOCK_ELEMENT_EDIT_TAGS_TIP")?></td>
		<td>
			<?if(CModule::IncludeModule('search')):
				$arLID = array();
				$rsSites = CIBlock::GetSite($IBLOCK_ID);
				while($arSite = $rsSites->Fetch())
					$arLID[] = $arSite["LID"];
				echo InputTags("SUB_TAGS", htmlspecialcharsback($str_TAGS), $arLID, 'size="55"');
			else:?>
				<input type="text" size="20" name="SUB_TAGS" maxlength="255" value="<?echo $str_TAGS?>">
			<?endif?>
		</td>
	</tr>
<?
$tabControl->EndCustomField("SUB_TAGS",
	'<input type="hidden" name="SUB_TAGS" value="'.$str_TAGS.'">'
);

if ($arShowTabs['catalog'])
{
	$tabControl->BeginNextFormTab();
	$tabControl->BeginCustomField("CATALOG", GetMessage("IBLOCK_TCATALOG"), true);
	include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/catalog/admin/templates/subproduct_edit.php");
	$tabControl->EndCustomField("CATALOG", "");
}

if ($arShowTabs['product_group'])
{
	$tabControl->BeginNextFormTab();
	$tabControl->BeginCustomField('SUBPRODUCT_GROUP', GetMessage('IBLOCK_EL_PRODUCT_GROUP').':', false);
	?><tr id="tr_SUBPRODUCT_GROUP"><td colspan="2"><?

		$intProductID = (0 < $ID ? CIBlockElement::GetRealElement($ID) : 0);

		$arSets = false;
		CCatalogAdminProductSetEdit::setSkuFormParams(array('TYPE' => CCatalogProductSet::TYPE_GROUP));
		if (0 < $intProductID)
		{
			$arSets = CCatalogProductSet::getAllSetsByProduct($intProductID, CCatalogProductSet::TYPE_GROUP);
			if ($bSubCopy)
				CCatalogAdminProductSetEdit::clearOwnerSet($arSets);
		}
		if (empty($arSets))
			$arSets = CCatalogAdminProductSetEdit::getEmptySet($intProductID);
		if ($bVarsFromForm)
			CCatalogAdminProductSetEdit::getFormValues($arSets);
		CCatalogAdminProductSetEdit::addEmptyValues($arSets);

		CCatalogAdminProductSetEdit::showEditForm($arSets);

		?></td></tr><?
	$tabControl->EndCustomField('SUBPRODUCT_SET', '');
}

if($arShowTabs['workflow']):?>
<?
	$tabControl->BeginNextFormTab();
	$tabControl->BeginCustomField("WORKFLOW_PARAMS", GetMessage("IBLOCK_EL_TAB_WF_TITLE"));
	if(strlen($pr["DATE_CREATE"])>0):
	?>
		<tr id="tr_WF_CREATED">
			<td width="40%"><?echo GetMessage("IBLOCK_CREATED")?></td>
			<td width="60%"><?echo $pr["DATE_CREATE"]?><?
			if (intval($pr["CREATED_BY"])>0):
			?>&nbsp;&nbsp;&nbsp;[<a href="user_edit.php?lang=<?=LANG?>&amp;ID=<?=$pr["CREATED_BY"]?>"><?echo $pr["CREATED_BY"]?></a>]&nbsp;<?=htmlspecialcharsex($pr["CREATED_USER_NAME"])?><?
			endif;
			?></td>
		</tr>
	<?endif;?>
	<?if(strlen($str_TIMESTAMP_X) > 0 && !$bSubCopy):?>
	<tr id="tr_WF_MODIFIED">
		<td><?echo GetMessage("IBLOCK_LAST_UPDATE")?></td>
		<td><?echo $str_TIMESTAMP_X?><?
		if (intval($str_MODIFIED_BY)>0):
		?>&nbsp;&nbsp;&nbsp;[<a href="user_edit.php?lang=<?=LANG?>&amp;ID=<?=$str_MODIFIED_BY?>"><?echo $str_MODIFIED_BY?></a>]&nbsp;<?=$str_USER_NAME?><?
		endif;
		?></td>
	</tr>
	<?endif?>
	<?if($WF=="Y" && strlen($prn_WF_DATE_LOCK)>0):?>
	<tr id="tr_WF_LOCKED">
		<td><?echo GetMessage("IBLOCK_DATE_LOCK")?></td>
		<td><?echo $prn_WF_DATE_LOCK?><?
		if (intval($prn_WF_LOCKED_BY)>0):
		?>&nbsp;&nbsp;&nbsp;[<a href="user_edit.php?lang=<?=LANG?>&amp;ID=<?=$prn_WF_LOCKED_BY?>"><?echo $prn_WF_LOCKED_BY?></a>]&nbsp;<?=$prn_LOCKED_USER_NAME?><?
		endif;
		?></td>
	</tr>
	<?endif;
	$tabControl->EndCustomField("WORKFLOW_PARAMS", "");
	if ($WF=="Y" || $view=="Y"):
	$tabControl->BeginCustomField("WF_STATUS_ID", GetMessage("IBLOCK_FIELD_STATUS").":");
	?>
	<tr id="tr_WF_STATUS_ID">
		<td><?echo $tabControl->GetCustomLabelHTML()?></td>
		<td>
			<?if($ID > 0 && !$bSubCopy):?>
				<?echo SelectBox("WF_STATUS_ID", CWorkflowStatus::GetDropDownList("N", "desc"), "", $str_WF_STATUS_ID);?>
			<?else:?>
				<?echo SelectBox("WF_STATUS_ID", CWorkflowStatus::GetDropDownList("N", "desc"), "", "");?>
			<?endif?>
		</td>
	</tr>
	<?
	if($ID > 0 && !$bSubCopy)
		$hidden = '<input type="hidden" name="WF_STATUS_ID" value="'.$str_WF_STATUS_ID.'">';
	else
	{
		$rsStatus = CWorkflowStatus::GetDropDownList("N", "desc");
		$arDefaultStatus = $rsStatus->Fetch();
		if($arDefaultStatus)
			$def_WF_STATUS_ID = intval($arDefaultStatus["REFERENCE_ID"]);
		else
			$def_WF_STATUS_ID = "";
		$hidden = '<input type="hidden" name="WF_STATUS_ID" value="'.$def_WF_STATUS_ID.'">';
	}
	$tabControl->EndCustomField("WF_STATUS_ID", $hidden);
	endif;
	$tabControl->BeginCustomField("WF_COMMENTS", GetMessage("IBLOCK_COMMENTS"));
	?>
	<tr class="heading" id="tr_WF_COMMENTS_LABEL">
		<td colspan="2"><b><?echo $tabControl->GetCustomLabelHTML()?></b></td>
	</tr>
	<tr id="tr_WF_COMMENTS">
		<td colspan="2">
			<?if($ID > 0 && !$bSubCopy):?>
				<textarea name="WF_COMMENTS" style="width:100%" rows="10"><?echo $str_WF_COMMENTS?></textarea>
			<?else:?>
				<textarea name="WF_COMMENTS" style="width:100%" rows="10"><?echo ""?></textarea>
			<?endif?>
		</td>
	</tr>
	<?
	$tabControl->EndCustomField("WF_COMMENTS", '<input type="hidden" name="WF_COMMENTS" value="'.$str_WF_COMMENTS.'">');
endif;

if ($arShowTabs['bizproc']):

	$tabControl->BeginNextFormTab();

	$tabControl->BeginCustomField("BIZPROC_WF_STATUS", GetMessage("IBEL_E_PUBLISHED"));
	?>
	<tr id="tr_BIZPROC_WF_STATUS">
		<td style="width:40%;"><?=GetMessage("IBEL_E_PUBLISHED")?>:</td>
		<td style="width:60%;"><?=($str_BP_PUBLISHED=="Y"?GetMessage("MAIN_YES"):GetMessage("MAIN_NO"))?></td>
	</tr>
	<?
	$tabControl->EndCustomField("BIZPROC_WF_STATUS", '');

	$tabControl->BeginCustomField("BIZPROC", GetMessage("IBEL_E_TAB_BIZPROC"));

	CBPDocument::AddShowParameterInit(MODULE_ID, "only_users", DOCUMENT_TYPE);

	$bizProcIndex = 0;
	if (!isset($arDocumentStates))
	{
		$arDocumentStates = CBPDocument::GetDocumentStates(
			array(MODULE_ID, ENTITY, DOCUMENT_TYPE),
			($ID > 0) ? array(MODULE_ID, ENTITY, $ID) : null,
			"Y"
		);
	}
	foreach ($arDocumentStates as $arDocumentState)
	{
		$bizProcIndex++;

		$canViewWorkflow = CBPDocument::CanUserOperateDocument(
			CBPCanUserOperateOperation::ViewWorkflow,
			$USER->GetID(),
			array(MODULE_ID, ENTITY, $ID),
			array("IBlockPermission" => $BlockPerm, "AllUserGroups" => $arCurrentUserGroups, "DocumentStates" => $arDocumentStates, "WorkflowId" => $arDocumentState["ID"] > 0 ? $arDocumentState["ID"] : $arDocumentState["TEMPLATE_ID"])
		);

		if (!$canViewWorkflow)
			continue;
		?>
		<tr class="heading">
			<td colspan="2">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td width="99%" align="center">
							<?= htmlspecialcharsbx($arDocumentState["TEMPLATE_NAME"]) ?>
						</td>
						<td width="1%" align="right">
							<?if (strlen($arDocumentState["ID"]) > 0 && strlen($arDocumentState["WORKFLOW_STATUS"]) > 0):?>
							<a href="iblock_element_edit.php?WF=<?= $WF ?>&ID=<?= $ID ?>&type=<?= $type ?>&lang=<?= $lang ?>&IBLOCK_ID=<?= $IBLOCK_ID ?>&find_section_section=<?= $find_section_section ?>&stop_bizproc=<?= $arDocumentState["ID"] ?>&<?= bitrix_sessid_get() ?>"><?echo GetMessage("IBEL_BIZPROC_STOP")?></a>
							<?endif;?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="40%"><?echo GetMessage("IBEL_BIZPROC_NAME")?></td>
			<td width="60%"><?= htmlspecialcharsbx($arDocumentState["TEMPLATE_NAME"]) ?></td>
		</tr>
		<?if($arDocumentState["TEMPLATE_DESCRIPTION"]!=''):?>
		<tr>
			<td width="40%"><?echo GetMessage("IBEL_BIZPROC_DESC")?></td>
			<td width="60%"><?= htmlspecialcharsbx($arDocumentState["TEMPLATE_DESCRIPTION"]) ?></td>
		</tr>
		<?endif?>
		<?if (strlen($arDocumentState["STATE_MODIFIED"]) > 0):?>
		<tr>
			<td width="40%"><?echo GetMessage("IBEL_BIZPROC_DATE")?></td>
			<td width="60%"><?= $arDocumentState["STATE_MODIFIED"] ?></td>
		</tr>
		<?endif;?>
		<?if (strlen($arDocumentState["STATE_NAME"]) > 0):?>
		<tr>
			<td width="40%"><?echo GetMessage("IBEL_BIZPROC_STATE")?></td>
			<td width="60%"><?if (strlen($arDocumentState["ID"]) > 0):?><a href="/bitrix/admin/bizproc_log.php?ID=<?= $arDocumentState["ID"] ?>"><?endif;?><?= strlen($arDocumentState["STATE_TITLE"]) > 0 ? $arDocumentState["STATE_TITLE"] : $arDocumentState["STATE_NAME"] ?><?if (strlen($arDocumentState["ID"]) > 0):?></a><?endif;?></td>
		</tr>
		<?endif;?>
		<?
		if (strlen($arDocumentState["ID"]) <= 0)
		{
			CBPDocument::StartWorkflowParametersShow(
				$arDocumentState["TEMPLATE_ID"],
				$arDocumentState["TEMPLATE_PARAMETERS"],
				($bCustomForm ? "tabControl" : "form_element_".$IBLOCK_ID)."_form",
				$bVarsFromForm
			);
		}
		?>
		<?
		$arEvents = CBPDocument::GetAllowableEvents($USER->GetID(), $arCurrentUserGroups, $arDocumentState, $arIBlock["RIGHTS_MODE"] === "E");
		if (!empty($arEvents))
		{
			?>
			<tr>
				<td width="40%"><?echo GetMessage("IBEL_BIZPROC_RUN_CMD")?></td>
				<td width="60%">
					<input type="hidden" name="bizproc_id_<?= $bizProcIndex ?>" value="<?= $arDocumentState["ID"] ?>">
					<input type="hidden" name="bizproc_template_id_<?= $bizProcIndex ?>" value="<?= $arDocumentState["TEMPLATE_ID"] ?>">
					<select name="bizproc_event_<?= $bizProcIndex ?>">
						<option value=""><?echo GetMessage("IBEL_BIZPROC_RUN_CMD_NO")?></option>
						<?
						foreach ($arEvents as $e)
						{
							?><option value="<?= htmlspecialcharsbx($e["NAME"]) ?>"<?= ($_REQUEST["bizproc_event_".$bizProcIndex] == $e["NAME"]) ? " selected" : ""?>><?= htmlspecialcharsbx($e["TITLE"]) ?></option><?
						}
						?>
					</select>
				</td>
			</tr>
			<?
		}

		if (strlen($arDocumentState["ID"]) > 0)
		{
			$arTasks = CBPDocument::GetUserTasksForWorkflow($USER->GetID(), $arDocumentState["ID"]);
			if (!empty($arTasks))
			{
				?>
				<tr>
					<td width="40%"><?echo GetMessage("IBEL_BIZPROC_TASKS")?></td>
					<td width="60%">
						<?
						foreach ($arTasks as $arTask)
						{
							?><a href="bizproc_task.php?id=<?= $arTask["ID"] ?>&back_url=<?= urlencode($APPLICATION->GetCurPageParam("", array())) ?>" title="<?= htmlspecialcharsbx($arTask["DESCRIPTION"]) ?>"><?= $arTask["NAME"] ?></a><br /><?
						}
						?>
					</td>
				</tr>
				<?
			}
		}
	}
	if ($bizProcIndex <= 0)
	{
		?>
		<tr>
			<td><br /></td>
			<td><?=GetMessage("IBEL_BIZPROC_NA")?></td>
		</tr>
		<?
	}
	?>
	<input type="hidden" name="bizproc_index" value="<?= $bizProcIndex ?>">
	<?
	if ($ID > 0):
		$bStartWorkflowPermission = CBPDocument::CanUserOperateDocument(
			CBPCanUserOperateOperation::StartWorkflow,
			$USER->GetID(),
			array(MODULE_ID, ENTITY, $ID),
			array("IBlockPermission" => $BlockPerm, "AllUserGroups" => $arCurrentUserGroups, "DocumentStates" => $arDocumentStates, "WorkflowId" => $arDocumentState["TEMPLATE_ID"])
		);
		if ($bStartWorkflowPermission):
			?>
			<tr class="heading">
				<td colspan="2"><?echo GetMessage("IBEL_BIZPROC_NEW")?></td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<a href="/bitrix/admin/<?=MODULE_ID?>_start_bizproc.php?document_id=<?= $ID ?>&document_type=<?= DOCUMENT_TYPE ?>&back_url=<?= urlencode($APPLICATION->GetCurPageParam("", array())) ?>"><?echo GetMessage("IBEL_BIZPROC_START")?></a>
				</td>
			</tr>
			<?
		endif;
	endif;

	$tabControl->EndCustomField("BIZPROC", "");
endif;

if($arShowTabs['edit_rights']):
	$tabControl->BeginNextFormTab();
	if($ID > 0)
	{
		$obRights = new CIBlockElementRights($IBLOCK_ID, $ID);
		$htmlHidden = '';
		foreach($obRights->GetRights() as $RIGHT_ID => $arRight)
			$htmlHidden .= '
				<input type="hidden" name="SUB_RIGHTS[][RIGHT_ID]" value="'.htmlspecialcharsbx($RIGHT_ID).'">
				<input type="hidden" name="SUB_RIGHTS[][GROUP_CODE]" value="'.htmlspecialcharsbx($arRight["GROUP_CODE"]).'">
				<input type="hidden" name="SUB_RIGHTS[][TASK_ID]" value="'.htmlspecialcharsbx($arRight["TASK_ID"]).'">
			';
	}
	else
	{
		$obRights = new CIBlockSectionRights($IBLOCK_ID, 0);
		$htmlHidden = '';
	}

	$tabControl->BeginCustomField("RIGHTS", GetMessage("IBEL_E_RIGHTS_FIELD"));
		IBlockShowRights(
			'element',
			$IBLOCK_ID,
			$ID,
			GetMessage("IBEL_E_RIGHTS_SECTION_TITLE"),
			"SUB_RIGHTS",
			$obRights->GetRightsList(),
			$obRights->GetRights(array("count_overwrited" => true, "parents" => array())),
			false, /*$bForceInherited=*/($ID <= 0) || $bCopy
		);
	$tabControl->EndCustomField("RIGHTS", $htmlHidden);
endif;

$bDisabled =
	($view=="Y")
	|| ($bWorkflow && $prn_LOCK_STATUS=="red")
	|| (
		(($ID <= 0) || $bCopy)
		&& !CIBlockSectionRights::UserHasRightTo($IBLOCK_ID, 0, "section_element_bind")
	)
	|| (
		(($ID > 0) && !$bCopy)
		&& !CIBlockElementRights::UserHasRightTo($IBLOCK_ID, $ID, "element_edit")
	)
	|| (
		$bBizproc
		&& !$canWrite
	)
;

if ($ID > 0 && !$bSubCopy && BX_SUB_SETTINGS)
{
	if (!$ajaxReload)
	{
		$reloadParams = array(
			'bxpublic' => 'Y',
			'bxsku' => 'Y',
			'WF' => 'Y',
			'TMP_ID' => $strSubTMP_ID,
			'ajaxReload' => 'Y'
		);

		if ($arShowTabs['product_group'])
		{
			$reloadParams['groupdel'] = 'Y';
			$reloadParams['sessid'] = bitrix_sessid();
			$setBtn = "{
				title: '".CUtil::JSEscape(GetMessage('IB_SE_SET_PRODUCT_TYPE_GROUP_DELETE'))."',
				name: 'groupdel',
				id: 'groupdel',
				className: 'adm-btn-add',
				action: function () {
					if (confirm('".CUtil::JSEscape(GetMessage('IB_SE_SET_PRODUCT_TYPE_GROUP_DELETE_CONFIRM'))."'))
					{
						top.BX.showWait();
						top.BX.ajax.get(
							'".CIBlock::GetAdminSubElementEditLink($IBLOCK_ID, $intProductID, $ID, $reloadParams, '', true)."',
							function (result) {
								top.BX.closeWait();
								top.BX.WindowManager.Get().SetContent(result);
								if (BX('groupdel'))
								{
									BX.style(BX('groupdel'), 'display', 'none');
								}
							}
						);
					}
				}
			}";
		}
		else
		{
			$reloadParams['SUBPRODUCT_TYPE'] = CCatalogAdminTools::TAB_GROUP;
			$setBtn = "{
				title: '".CUtil::JSEscape(GetMessage('IB_SE_SET_PRODUCT_TYPE_GROUP_ADD'))."',
				name: 'groupset',
				id: 'groupset',
				className: 'adm-btn-add',
				action: function () {
					top.BX.showWait();
					top.BX.ajax.get('".CIBlock::GetAdminSubElementEditLink($IBLOCK_ID, $intProductID, $ID, $reloadParams, '', true)."',
					function (result) {
						top.BX.closeWait();
						top.BX.WindowManager.Get().SetContent(result);
						if (BX('groupset'))
						{
							BX.style(BX('groupset'), 'display', 'none');
						}
					});
				}
			}";
		}
		$tabControl->ButtonsPublic(array($setBtn));
		unset($setBtn, $reloadParams);
	}
}
else
{
	$tabControl->Buttons(false, '');
}

$tabControl->Show();

	//////////////////////////
	//END of the custom form
	//////////////////////////
//endif;
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");