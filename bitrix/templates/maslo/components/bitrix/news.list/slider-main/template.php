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
$arImagesChips = array(
		"LEFT_Y"=>array(20,68,120,176,236),
		"LEFT_X"=>array(120,205,273,324,358),
		"RIGHT_Y"=>array(30,80,130,180,230),
		"RIGHT_X"=>array(680,760,840,900,980),
		"RIGHT_HOFFSET"=>array(30,55,75,100,125),
	);
?>
<!--=== Slider ===-->
<div class="tp-banner-container">
	<div class="tp-banner">
		<ul>
			<?foreach($arResult["ITEMS"] as $indexSlide=>$arItem):?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
                <!-- SLIDE -->
					<li class="revolution-mch-1" data-transition="fade" data-slotamount="5" data-masterspeed="1000" data-title="<?=$arItem['NAME']?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<!-- MAIN IMAGE -->
						<? if ($arItem['PROPERTIES']['FON_PICTURE']['VALUE']):?>
							<img src="<?=CFile::GetPath($arItem['PROPERTIES']['FON_PICTURE']['VALUE'])?>"  alt="darkblurbg"  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
						<? endif?>

						<? if ($arItem['PROPERTIES']['POSITION_SLIDE']['VALUE_XML_ID']=='left'):?>
								<!-- LAYER -->
								<div class="tp-caption revolution-ch1 sft start"
									data-x="165"
									data-y="center"
									data-voffset="30"
									data-speed="1500"
									data-start="500"
									data-easing="Back.easeInOut"
									data-endeasing="Power1.easeIn"
									data-endspeed="300">
									<?=$arItem['NAME']?>
								</div>
							<? else: ?>
								<!-- LAYER -->
								<div class="tp-caption revolution-ch1 sft start"
									data-x="700"
									data-y="bottom"
									data-voffset="-190"
									data-speed="1500"
									data-start="500"
									data-easing="Back.easeInOut"
									data-endeasing="Power1.easeIn"
									data-endspeed="300">
									<?=$arItem['NAME']?>
								</div>
                        <? endif?>

						<? if ($arItem['PROPERTIES']['LINE_TEXT']['VALUE']):?>
							<? $curIndex = 0; ?>
							<? foreach($arItem['PROPERTIES']['LINE_TEXT']['VALUE'] as $pVal):?>
								<?$curIndex++;?>
								<? if ($arItem['PROPERTIES']['POSITION_SLIDE']['VALUE_XML_ID']=='left'):?>
										<!-- LAYER -->
										<div class="tp-caption revolution-ch4 sft start"
											data-x="165"
											data-y="center"
											data-voffset="<?=40+($curIndex*30)?>"
											data-speed="1500"
											data-start="<?=(500+($curIndex*100))?>"
											data-easing="Back.easeInOut"
											data-endeasing="Power1.easeIn"
											data-endspeed="300">
											<?=$pVal?>
										</div>
									<?else: ?>
										<!-- LAYER -->
										<div class="tp-caption revolution-ch4 sft start"
											data-x="700"
											data-y="bottom"
											data-voffset="<?=-190+($curIndex*30)?>"
											data-speed="1500"
											data-start="<?=(500+($curIndex*100))?>"
											data-easing="Back.easeInOut"
											data-endeasing="Power1.easeIn"
											data-endspeed="300">
											<?=$pVal?>
										</div>
								<?endif?>
							<? endforeach;?>
						<? endif;?>

						<? if ($arItem['PROPERTIES']['URL_BUY']['VALUE']):?>
							<? if ($arItem['PROPERTIES']['POSITION_SLIDE']['VALUE_XML_ID']=='left'):?>
									<!-- LAYER -->
									<div class="tp-caption sft"
										data-x="165"
										data-y="center"
										data-voffset="<?=(count($arItem['PROPERTIES']['LINE_TEXT']['VALUE'])*30)+80?>"
										data-speed="1600"
										data-start="1500"
										data-easing="Power4.easeOut"
										data-endspeed="300"
										data-endeasing="Power1.easeIn"
										data-captionhidden="off"
										style="z-index: 6">
										<a href="<?=$arItem['PROPERTIES']['URL_BUY']['VALUE']?>" class="btn-u btn-brd btn-brd-hover btn-u-light slider-buy-btn"><? if (trim($arItem['PROPERTIES']['TEXT_BUY']['VALUE'] == "")) { ?>Купить<? } else { echo $arItem['PROPERTIES']['TEXT_BUY']['VALUE']; } ?></a>
									</div>
								<?else:?>
									<!-- LAYER -->
									<div class="tp-caption sft"
											data-x="700"
											data-y="bottom"
											data-voffset="<?=-230+(count($arItem['PROPERTIES']['LINE_TEXT']['VALUE'])*30)+80?>"
											data-speed="1600"
											data-start="1500"
											data-easing="Power4.easeOut"
											data-endspeed="300"
											data-endeasing="Power1.easeIn"
											data-captionhidden="off"
											style="z-index: 6">
										<a href="<?=$arItem['PROPERTIES']['URL_BUY']['VALUE']?>" class="btn-u btn-brd btn-brd-hover btn-u-light slider-buy-btn"><? if (trim($arItem['PROPERTIES']['TEXT_BUY']['VALUE'] == "")) { ?>Купить<? } else { echo $arItem['PROPERTIES']['TEXT_BUY']['VALUE']; } ?></a>
									</div>
							<? endif;?>
						<? endif;?>

						<? if ($arItem['PROPERTIES']['MORE_PHOTOS']['VALUE']):?>
							<? $curIndex = 0; ?>
							<? foreach($arItem['PROPERTIES']['MORE_PHOTOS']['VALUE'] as $arValFile):?>
									<? $curIndex++; ?>
									<? if ($arItem['PROPERTIES']['POSITION_SLIDE']['VALUE_XML_ID']=='left'):?>
										    <!-- LAYER -->
										    <div class="tp-caption lft"
												data-x="<?=$arImagesChips['LEFT_X'][$curIndex-1]?>"
												data-y="<?=$arImagesChips['LEFT_Y'][$curIndex-1]?>"
												data-speed="1600"
												data-start="<?=1000+($curIndex*500)?>"
												data-easing="Power4.easeOut"
												data-endspeed="300"
												data-endeasing="Power1.easeIn"
												data-captionhidden="off"
												style="z-index: <?=(10+count($arItem['PROPERTIES']['URL_BUY']['VALUE']))-$curIndex?>">
												<img style="width: <?=220-($curIndex*40)?>px;" src="<?=CFile::GetPath($arValFile)?>">
											</div>
										<?else:?>
											<!-- LAYER -->
											<div class="tp-caption lfr"
												data-x="<?=$arImagesChips['RIGHT_X'][$curIndex-1]?>"
												data-hoffset="<?=$arImagesChips['RIGHT_HOFFSET'][$curIndex-1]?>"
												data-y="<?=$arImagesChips['RIGHT_Y'][$curIndex-1]?>"
												data-speed="1600"
												data-start="<?=1000+($curIndex*500)?>"
												data-easing="Power4.easeOut"
												data-endspeed="300"
												data-endeasing="Power1.easeIn"
												data-captionhidden="off"
												style="z-index: <?=(10+count($arItem['PROPERTIES']['URL_BUY']['VALUE']))-$curIndex?>">
												<img style="width: <?=220-($curIndex*40)?>px;" src="<?=CFile::GetPath($arValFile)?>">
											</div>
									<? endif?>
								<? endforeach?>
						<? endif;?>
				</li>
				<!-- END SLIDE -->
			<? endforeach?>
		</ul>
		<div class="tp-bannertimer tp-bottom"></div>
	</div>
</div>
<!--=== End Slider ===-->