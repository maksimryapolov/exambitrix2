<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);?>
<div class="news-list">
    <div>
        <a href="<?=$APPLICATION->GetCurPage();?>?F=Y">
            <?=$APPLICATION->GetCurPage();?>?F=Y
        </a>
    </div>
    <br>
    <b>Каталог:</b>
    <ul>
        <?
        foreach ($arResult["ITEMS"] as $arItem):
            $this->AddEditAction($arItem['ID'], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('NEWS_DELETE_CONFIRM')));
            ?>

            <li id="<?=$this->GetEditAreaId($arItem["ID"]);?>">
                <b><?=$arItem["NAME"];?></b>
            </li>
            <ul>
                <?foreach ($arItem["ELEM"] as $arEl):
                    $this->AddEditAction($arEl['ID'], $arEl["EDIT_LINK"], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arEl['ID'], $arEl["DELETE_LINK"], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('NEWS_DELETE_CONFIRM')));
                    ?>
                    <li id="<?=$this->GetEditAreaId($arEl["ID"]);?>">
                        <?=$arEl["NAME"];?> &mdash;
                        <?=$arEl["PRICE"];?> &mdash;
                        <?=$arEl["MATERIAL"];?> &mdash;
                        <?=$arEl["ARTNUMBER"];?> &mdash;
                        (<?=$arEl["DETAIL"];?>)
                    </li>
                <?endforeach;?>
            </ul>
        <?endforeach;?>
    </ul>
</div>
