<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>
<div>
    <?foreach ($arResult["ITEMS"] as $arItem):?>
        [<?=$arItem["USER_ID"]?>]&nbsp;&mdash;&nbsp;
        <b>
            <?=($arItem["USER_LOGIN"]);?>
        </b>
        <ul>
            <?foreach ($arItem["ITEMS"] as $el):?>
             <li><?=$el;?></li>
            <?endforeach;?>
        </ul>
    <?endforeach;?>
</div>
