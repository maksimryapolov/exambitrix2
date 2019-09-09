<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

use	Bitrix\Main\Loader;

CPageOption::SetOptionString("main", "nav_page_in_session", "N");

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
if(strlen($arParams["IBLOCK_TYPE"])<=0)
	$arParams["IBLOCK_TYPE"] = "news";
$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);
$arParams["IBLOCK_NEW"] = trim($arParams["IBLOCK_NEW"]);

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

if($this->startResultCache(false, array(($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()), $bUSER_HAVE_ACCESS, $arNavigation, $arrFilter, $pagerParameters)))
{
	if(!Loader::includeModule("iblock"))
	{
		$this->abortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}

	$arResult["USER_HAVE_ACCESS"] = $bUSER_HAVE_ACCESS;

	$arParams["PARENT_SECTION"] = $PARENT_SECTION;

    $Result = array();

    if(intval($arParams["IBLOCK_NEW"]) > 0) {
        $arFilterNew = array("IBLOCK_ID" => intval($arParams["IBLOCK_NEW"]));
        $arSelectNew = array("ID", "NAME", "DATE_ACTIVE_FROM");
        $res = CIBlockElement::GetList(array(), $arFilterNew, false, array(), $arSelectNew);

        while($ob = $res->GetNextElement())
        {
            $el = $ob->GetFields();
            $Result[$el["ID"]]["NAME_PROP"] = $el["NAME"];
            $Result[$el["ID"]]["DATE_ACTIVE_FROM"] = $el["DATE_ACTIVE_FROM"];
            $Result[$el["ID"]]["ID"] = $el["ID"];
        }
    }

    $rsSections = CIBlockSection::GetList (
        array(),
        array("IBLOCK_ID"=>intval($arParams["IBLOCK_ID"]), "!UF_NEWS_LINK" => false),
        false,
        array("ID", "NAME", "UF_NEWS_LINK")
    );

    $arSelectSect = array();

    while($arSection = $rsSections->GetNext())
    {

        foreach ($arSection["UF_NEWS_LINK"] as $item) {
            $Result[$item]["SECTION_ITEMS"][$arSection["ID"]]["NAME"] = $arSection["NAME"];
        }
        $arSelectSect[] = $arSection["ID"];
    }

    $rsElems = CIBlockElement::GetList(array(), array("IBLOCK_ID"=>intval($arParams["IBLOCK_ID"]), "SECTION_ID" => $arSelectSect), false, array(), array("ID", "NAME", "IBLOCK_SECTION_ID", "PROPERTY_PRICE", "PROPERTY_ARTNUMBER", "PROPERTY_MATERIAL"));

    $i = 0;

    while($res = $rsElems->GetNextElement())
    {
        $elem = $res->GetFields();
        foreach ($Result as $pid => $el) {
            foreach ($el["SECTION_ITEMS"] as $key => $item) {
                if($elem["IBLOCK_SECTION_ID"] == $key) {
                    $i++;
                    $Result[$pid]["ITEMS"][$elem["ID"]]["NAME"] = $elem["NAME"];
                    $Result[$pid]["ITEMS"][$elem["ID"]]["PRICE"] = $elem["PROPERTY_PRICE_VALUE"];
                    $Result[$pid]["ITEMS"][$elem["ID"]]["ARTNUMBER"] = $elem["PROPERTY_ARTNUMBER_VALUE"];
                    $Result[$pid]["ITEMS"][$elem["ID"]]["MATERIAL"] = $elem["PROPERTY_MATERIAL_VALUE"];
                }
            }
        }
    }

    $APPLICATION->SetTitle("В каталоге товаров представлено товаров: $i");

    foreach ($Result as $key => $res) {
        if(empty($res["SECTION_ITEMS"])) {
            unset($Result[$key ]);
        }
    }

    $arResult["NEW_ITEMS"] = $Result;
	$this->includeComponentTemplate();
}
