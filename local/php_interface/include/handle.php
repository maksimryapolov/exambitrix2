<?
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("MyClass", "OnBeforeIBlockElementUpdateHandler"));

class MyClass
{
    function OnBeforeIBlockElementUpdateHandler(&$arFields) {
        if($arFields["IBLOCK_ID"] == IBLOCK_PRODUCT) {
            $counter = 0;

            if(CModule::IncludeModule('iblock') && $arFields["ACTIVE"] == "N") {
                  $arFilter = Array("IBLOCK_ID"=>$arFields["IBLOCK_ID"], "ID" => $arFields["ID"], "!SHOW_COUNTER" => false);
                  $res = CIBlockElement::GetList(Array(),   $arFilter, false, false, Array("ID","NAME", "SHOW_COUNTER"));
                  while($ar_fields = $res->GetNext())
                  {
                        $counter = $ar_fields["SHOW_COUNTER"]; 
                  }

                  if($counter > 2) {
                        global $APPLICATION;
                        $APPLICATION->throwException("Товар невозможно деактивировать, у него $counter просмотров");
                        return false;
                  }
            }
        }
            
    }
}

AddEventHandler("main", "OnBeforeEventAdd", array("OnMain", "OnBeforeEventAddHandler"));
AddEventHandler("main", "OnEpilog", array("OnMain", "Redirect404"));
AddEventHandler("main", "OnEpilog", array("OnMain", "Set_seo"));

class OnMain
{
    function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
      {
            $name_form = $arFields["AUTHOR"];
            $email = $arFields["AUTHOR_EMAIL"];

            $str = "Пользователь не авторизован, данные из формы: $name_form";
            if(CUser::IsAuthorized()) {
                $id_user = CUser::GetID();
                $rsUser = CUser::GetByID($id_user);
                $arUser = $rsUser->Fetch();

                $login = CUser::GetLogin();
                $name = $arUser["NAME"];
                $name_form = $arFields["AUTHOR"];

                $str = "Пользователь авторизован: $id_user ($login) $name, данные из формы: $name_form";
            }
            $arFields["AUTHOR"] = $str;

              CEventLog::add(array(
                  "SEVERITY" => "INFO",
                  "AUDIT_TYPE_ID" => "SEND_FORM",
                  "MODULE_ID" => "main",
                  "DESCRIPTION" => "Замена данных в отсылаемом письме - $str",
              ));

    }

      function Redirect404() {
            if(!defined('ADMIN_SECTION') && defined("ERROR_404") && ERROR_404 == "Y") 
            {
                  global $APPLICATION;
                  CHTTP::SetStatus("404 Not Found");
                  CEventLog::add(array(
                        "SEVERITY" => "INFO",
                        "AUDIT_TYPE_ID" => "ERROR_404",
                        "MODULE_ID" => "main",
                        "DESCRIPTION" => $_SERVER["REQUEST_URI"],
                  ));
            }
      }
    function Set_seo() {
        if (CModule::IncludeModule('iblock')) {
            $arFilter = Array("IBLOCK_ID"=>IBLOCK_SEO, "NAME"=>substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"],"?" )));
            $res = CIBlockElement::GetList(Array(), $arFilter, false, false, Array("NAME", "PROPERTY_title", "PROPERTY_description"));
            while($ar_fields = $res->GetNext())
            {
                if($ar_fields["NAME"] == substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"],"?" ))) {
                    $GLOBALS["APPLICATION"]->SetPageProperty("title", $ar_fields["PROPERTY_TITLE_VALUE"]);
                    $GLOBALS["APPLICATION"]->SetPageProperty("description", $ar_fields["PROPERTY_DESCRIPTION_VALUE"]);
                }
            }
        }
    }
}?>
