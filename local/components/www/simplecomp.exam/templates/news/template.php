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
    <?foreach($arResult["NEW_ITEMS"] as $NEW_ITEM):?>
        <div>
            <b><?=$NEW_ITEM["NAME_PROP"];?></b>
            <span><?=$NEW_ITEM["DATE_ACTIVE_FROM"];?></span>

        (
        <?foreach ($NEW_ITEM["SECTION_ITEMS"] as $val):?>
        <?=$val["NAME"]; echo end($NEW_ITEM["SECTION_ITEMS"]) == $val ? "" : ", ";?>

        <?endforeach;?>
        )
        </div><br>
        <ul>
            <?foreach ($NEW_ITEM["ITEMS"] as $elem): ?>
                <li>
                    <?=$elem["NAME"]. " - " . $elem["PRICE"] . " - " . $elem["ARTNUMBER"] . " - " . $elem["MATERIAL"];?>
                </li>
            <?endforeach;?>
        </ul>
    <?endforeach;die;?>
</div>
