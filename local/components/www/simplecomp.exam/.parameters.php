<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */



$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
//		"AJAX_MODE" => array(),
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("T_IBLOCK_DESC_LIST_ID"),
            "TYPE" => "STRING",
            "DEFAULT" => '={$_REQUEST["ID"]}',
            "ADDITIONAL_VALUES" => "Y",
            "REFRESH" => "Y",
        ),
        "IBLOCK_NEW" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("T_IBLOCK_DESC_LIST_ID_NEWS"),
            "TYPE" => "STRING",
            "DEFAULT" => '1',
            "ADDITIONAL_VALUES" => "Y",
            "REFRESH" => "Y",
        ),
        "CODE_USER_PROP" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("T_IBLOCK_CODE_USER_PROP"),
			"TYPE" => "STRING",
			"VALUES" => "",
		),
        "CACHE_TIME"  =>  array("DEFAULT"=>36000000),
	),
);
