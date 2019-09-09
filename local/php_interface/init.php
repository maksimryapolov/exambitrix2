<?
if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/handle.php")) {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/handle.php";
}

if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/constant.php")) {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/constant.php";
}

if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/dump.php")) {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/dump.php";
}

if(defined("ERROR_404") && ERROR_404 == "Y") {
  CEventLog::add(array(
        "SEVERITY" => "INFO",
        "AUDIT_TYPE_ID" => "ERROR_404",
        "MODULE_ID" => "main",
        "DESCRIPTION" => $_SERVER["REQUEST_URI"],
  ));
}?>