<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}

if(intval($arParams["PRODUCTS_IBLOCK_ID"]) > 0)
{
    global $USER;
    $cur_user_id = $USER->GetID();
    $rsUser = CUser::GetByID($cur_user_id);
    $arUser = $rsUser->Fetch();

    $order = array('sort' => 'asc');
    $tmp = 'sort'; // параметр проигнорируется методом, но обязан быть
    $filter = array("UF_AUTHOR_TYPE" => intval($arUser["UF_AUTHOR_TYPE"]));
    $arSelectProp["SELECT"] = array("UF_AUTHOR_TYPE");
    $rsUsers = CUser::GetList($order, $tmp, $filter, $arSelectProp);

    $arUserNews = array();
    while($res = $rsUsers->GetNext())
    {
        $arUserNews[$res["ID"]] = array("USER_LOGIN" => $res["LOGIN"], "USER_ID" => $res["ID"], "PROP_ID" => $res["UF_AUTHOR_TYPE"]);
        if($res["ID"] == $cur_user_id) {
            unset($arUserNews[$res["ID"]]);
        }
    }

	//iblock elements
	$arSelectElems = array (
	    "NAME",
        "ID",
        "PROPERTY_AUTHOR"
	);


	$arFilterElems = array (
		"IBLOCK_ID" => intval($arParams["PRODUCTS_IBLOCK_ID"]),
		"ACTIVE" => "Y",
        "=PROPERTY_AUTHOR" => array_keys($arUserNews)
	);

	$arResult["ELEMENTS"] = array();
	$rsElements = CIBlockElement::GetList(array(), $arFilterElems, false, false, array());
    $checkOnFalse = array();
    $checkOnTrue = array();
    $titleCount = 0;

	while($arElement = $rsElements->GetNextElement())
	{
	    $resData = $arElement->GetFields();
	    $resProp = $arElement->GetProperties();

        if(!in_array($cur_user_id, $resProp["AUTHOR"]["VALUE"])) {
            foreach ($resProp["AUTHOR"]["VALUE"] as $val){
                if(!in_array(array("NAME" => $resData["NAME"],"AUTHOR" => $resProp["AUTHOR"]["VALUE"]), $checkOnTrue)) {
                    $titleCount++;
                    $checkOnTrue[] = array("NAME" => $resData["NAME"],"AUTHOR" => $resProp["AUTHOR"]["VALUE"]);
                }
            }
        }
	}

	foreach ($checkOnTrue as $key => $item) {
	    foreach ($item["AUTHOR"] as $elem) {
            if(isset($arUserNews[$elem])) {
                $arUserNews[$elem]["ITEMS"][$key] = $item["NAME"];
            }
        }
    }

    $arResult["ITEMS"] = $arUserNews;
	$APPLICATION->SetTitle("Новости: " . $titleCount);
}

$this->includeComponentTemplate();	
?>