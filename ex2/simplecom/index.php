<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("simplecom");
?><?$APPLICATION->IncludeComponent(
	"www:simplecomp.exam2",
	"",
	Array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CODE_CLASSIF_PRODUCT" => "FIRM",
		"DETAIL_URL" => "catalog_exam/#SECTION_ID#/#ELEMENT_CODE#",
		"IBLOCK_ID" => "2",
		"IBLOCK_ID_CLASSIF" => "7",
		"SORT_BY1" => "NAME",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC"
	)
);?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>