<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */
global $INTRANET_TOOLBAR;

use Bitrix\Main\Context,
	Bitrix\Main\Type\DateTime,
	Bitrix\Main\Loader,
	Bitrix\Iblock;

CPageOption::SetOptionString("main", "nav_page_in_session", "N");
$this->addEditButton(
  array(
      "TITLE" => "TEST",
      "URL" => "index.php"
  )
);

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
if(strlen($arParams["IBLOCK_TYPE"])<=0)
	$arParams["IBLOCK_TYPE"] = "products";


$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);
$arParams["IBLOCK_ID_CLASSIF"] = trim($arParams["IBLOCK_ID_CLASSIF"]);

if(empty($arParams["CODE_CLASSIF_PRODUCT"]))
    $arParams["CODE_CLASSIF_PRODUCT"] = "FIRM";

$arParams["DETAIL_URL"]=trim($arParams["DETAIL_URL"]);

$arParams["SET_TITLE"] = $arParams["SET_TITLE"]!="N";

$arParams["USE_PERMISSIONS"] = $arParams["USE_PERMISSIONS"]=="Y";
if(!is_array($arParams["GROUP_PERMISSIONS"]))
	$arParams["GROUP_PERMISSIONS"] = array(1);

$bUSER_HAVE_ACCESS = !$arParams["USE_PERMISSIONS"];
if($arParams["USE_PERMISSIONS"] && isset($GLOBALS["USER"]) && is_object($GLOBALS["USER"]))
{
	$arUserGroupArray = $USER->GetUserGroupArray();
	foreach($arParams["GROUP_PERMISSIONS"] as $PERM)
	{
		if(in_array($PERM, $arUserGroupArray))
		{
			$bUSER_HAVE_ACCESS = true;
			break;
		}
	}
}

if($_GET["F"] == "Y") 
{
	$arFilter[] = array(
	            "LOGIC" => "OR",
	            array("<PROPERTY_PRICE" => 1500,  "=PROPERTY_MATERIAL" => "Дерево, ткань"),
	            array("<=PROPERTY_PRICE" => 1700, "=PROPERTY_MATERIAL" => "Металл, пластик")
        );
}

$filter = array (
        "IBLOCK_LID" => SITE_ID,
        "ACTIVE" => "Y",
        "!PROPERTY_" . $arParams["CODE_CLASSIF_PRODUCT"] => false,
        "CHECK_PERMISSIONS" => $arParams['CHECK_PERMISSIONS'] ? "Y" : "N",
    );



if(isset($arFilter))
{
	$arFilter = array_merge($arFilter, $filter);
} 
else 
{
	$arFilter = $filter;
}


if(
	$this->startResultCache(
		false, 
		array(
			(
				$arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()
			), 
			$bUSER_HAVE_ACCESS, $arNavigation, $arrFilter, $pagerParameters
		)
	)
)
{
	if(!Loader::includeModule("iblock"))
	{
		$this->abortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}

	if(is_numeric($arParams["IBLOCK_ID"]))
	{
		$rsIBlock = CIBlock::GetList(array(), array(
			"ACTIVE" => "Y",
			"ID" => $arParams["IBLOCK_ID"],
		));
	}
	else
	{
		$rsIBlock = CIBlock::GetList(array(), array(
			"ACTIVE" => "Y",
			"CODE" => $arParams["IBLOCK_ID"],
			"SITE_ID" => SITE_ID,
		));
	}

	$arResult = $rsIBlock->GetNext();
	$arFilter["IBLOCK_ID"] = $arResult["ID"];
	if (!$arResult)
	{
		$this->abortResultCache();
		Iblock\Component\Tools::process404(
			trim($arParams["MESSAGE_404"]) ?: GetMessage("T_NEWS_NEWS_NA")
			,true
			,$arParams["SET_STATUS_404"] === "Y"
			,$arParams["SHOW_404"] === "Y"
			,$arParams["FILE_404"]
		);
		return;
	}

	$arResult["USER_HAVE_ACCESS"] = $bUSER_HAVE_ACCESS;
	//SELECT


	$bGetProperty = count($arParams["PROPERTY_CODE"])>0;

	if($bGetProperty)
		$arSelect[]="PROPERTY_*";
	//WHERE
    $sort = array(
        $arParams["SORT_BY1"] => $arParams["SORT_ORDER1"],
        $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"]
    );

    $rsElementClassif = CIBlockElement::GetList(
        array(),
        array(
            "IBLOCK_ID" => $arParams["IBLOCK_ID_CLASSIF"],
        ),
        false,
        array(),
        array("ID", "NAME", "IBLOCK_ID"));
    $rsElementClassif->SetUrlTemplates($arParams["DETAIL_URL"], "", $arParams["IBLOCK_URL"]);
    $arResult["ITEMS"] = array();

    while($obClassif = $rsElementClassif->GetNextElement())
    {
        $arClassif = $obClassif->GetFields();

        // Edit area
        $arButtons = CIBlock::GetPanelButtons(
            $arClassif["IBLOCK_ID"],
            $arClassif["ID"],
            0,
            array("SECTION_BUTTONS"=>false, "SESSID"=>false)
        );

        $arClassif["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
        $arClassif["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
        // end edit area

        $arResult["ITEMS"][$arClassif["ID"]] = $arClassif;
    }

	if($arParams["CHECK_DATES"])
		$arFilter["ACTIVE_DATE"] = "Y";

    $arResult["SECTION"]= false;

    $arSelect = array(
        "NAME",
        "ID",
        "PROPERTY_FIRM",
        "PROPERTY_PRICE",
        "PROPERTY_MATERIAL",
        "PROPERTY_ARTNUMBER"
    );

	$rsElement = CIBlockElement::GetList($sort, $arFilter, false, array(),array());
	$rsElement->SetUrlTemplates($arParams["DETAIL_URL"], "", $arParams["IBLOCK_URL"]);

	while($obElement = $rsElement->GetNextElement())
	{
		$arItem = $obElement->GetFields();
        $arProp = $obElement->GetProperties();

        // Edit area
        $arButtons = CIBlock::GetPanelButtons(
            $arItem["IBLOCK_ID"],
            $arItem["ID"],
            0,
            array("SECTION_BUTTONS"=>false, "SESSID"=>false)
        );

        $arItem["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
        $arItem["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
        // end edit area


        foreach ($arProp["FIRM"]["VALUE"] as $val) {
            if(!empty($arResult["ITEMS"]["$val"])) {

                $arResult["ITEMS"]["$val"]["ELEM"][] = array(
                    "NAME" => $arItem["NAME"],
                    "PRICE" => $arProp["PRICE"]["VALUE"],
                    "MATERIAL" => $arProp["MATERIAL"]["VALUE"],
                    "ARTNUMBER" => $arProp["ARTNUMBER"]["VALUE"],
                    "DETAIL" => $arItem["DETAIL_PAGE_URL"],
                    "EDIT_LINK" => $arItem["EDIT_LINK"],
                    "DELETE_LINK" => $arItem["DELETE_LINK"]
                );
            }
        }
	}

    $GLOBALS["APPLICATION"]->SetTitle("Разделов: " . count($arResult["ITEMS"]));
	$this->setResultCacheKeys(array("ITEMS"));
	$this->includeComponentTemplate();
}
