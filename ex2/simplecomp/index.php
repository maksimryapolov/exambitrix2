<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Мой комп");
?><?$APPLICATION->IncludeComponent(
	"www:simplecomp.exam",
	"news",
	Array(
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CODE_USER_PROP" => "UF_NEWS_LINK",
		"IBLOCK_ID" => "2",
		"IBLOCK_NEW" => "1"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>