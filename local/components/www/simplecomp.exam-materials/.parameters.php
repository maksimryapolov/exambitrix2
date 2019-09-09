<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"PRODUCTS_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_ID"),
			"TYPE" => "STRING",
		),
        "NEWS_PROP_NAME" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_PROP_NAME"),
			"TYPE" => "STRING",
		),
        "USER_PROP_NAME" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_USER_PROP_NAME"),
			"TYPE" => "STRING",
		),
        "CACHE_TIME"  =>  array("DEFAULT"=>36000000),
	),
);