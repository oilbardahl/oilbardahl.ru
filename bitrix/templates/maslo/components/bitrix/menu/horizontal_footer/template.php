<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

if (empty($arResult["ALL_ITEMS"]))
	return;

CUtil::InitJSCore();

if (file_exists($_SERVER["DOCUMENT_ROOT"].$this->GetFolder().'/themes/'.$arParams["MENU_THEME"].'/colors.css'))
	$APPLICATION->SetAdditionalCSS($this->GetFolder().'/themes/'.$arParams["MENU_THEME"].'/colors.css');

$menuBlockId = "catalog_menu_".$this->randString();
?>
<?foreach($arResult["MENU_STRUCTURE"] as $itemID => $arColumns):?>
	<!-- Simple List -->
				<?if (is_array($arColumns) && count($arColumns) > 0):?>
				<div class="col-md-2 col-sm-3">
				<div class="row">
					<div class="col-sm-12 col-xs-12">	
					<h2 class="thumb-headline"><?=$arResult["ALL_ITEMS"][$itemID]["TEXT"]?></h2>
						<?foreach($arColumns as $key=>$arRow):?>
							<ul class="list-unstyled simple-list margin-bottom-20">
							<?foreach($arRow as $itemIdLevel_2=>$arLevel_3):?>  <!-- second level-->
								<li class="bx-nav-2-lvl">
									<a
										href="<?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"]?>"
										<?if($arResult["ALL_ITEMS"][$itemIdLevel_2]["SELECTED"]):?>class="bx-active"<?endif?>
									>
										<?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["TEXT"]?>
									</a>
								<?if (is_array($arLevel_3) && count($arLevel_3) > 0):?>
									<ul class="bx-nav-list-3-lvl">
									<?foreach($arLevel_3 as $itemIdLevel_3):?>	<!-- third level-->
										<li class="bx-nav-3-lvl">
											<a
												href="<?=$arResult["ALL_ITEMS"][$itemIdLevel_3]["LINK"]?>"
												<?if($arResult["ALL_ITEMS"][$itemIdLevel_3]["SELECTED"]):?>class="bx-active"<?endif?>
											>
												<?=$arResult["ALL_ITEMS"][$itemIdLevel_3]["TEXT"]?>
											</a>
										</li>
									<?endforeach;?>
									</ul>
								<?endif?>
								</li>
							<?endforeach;?>
							</ul>
						<?endforeach;?>
						</div>
					</div>
				</div>
				<?endif?>	
<?endforeach;?>