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
$this->setFrameMode(true);
$arrayClass = array('collapseOne','collapseTwo','collapseThree');
?>
<? if ($arResult["ITEMS"]):?>
	<h2 class="title-type">Часто задаваемые вопросы</h2>
	<!-- Accordion -->
	<div class="accordion-v2 plus-toggle">
	  <div class="panel-group" id="accordion-v2">
	    <? foreach($arResult["ITEMS"] as $key=>$arItem):?>
			<div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title"> <a data-toggle="collapse" class="collapsed" data-parent="#accordion-v2" href="#<?=$arrayClass[$key]?>-v2"><?=$arItem['NAME']?></a> </h4>
              </div>
              <div id="<?=$arrayClass[$key]?>-v2" class="panel-collapse collapse">
                <div class="panel-body"><?=$arItem['PREVIEW_TEXT']?></div>
              </div>
            </div>
	    <? endforeach;?>
	  </div>
	</div>
<? endif?>