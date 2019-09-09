<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("123");
?><?$APPLICATION->IncludeComponent(
	"www:simplecomp.exam-materials",
	"",
	Array(
		"PRODUCTS_IBLOCK_ID" => "2"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>