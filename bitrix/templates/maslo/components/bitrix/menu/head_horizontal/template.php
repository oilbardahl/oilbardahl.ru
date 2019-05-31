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
<?
//pr($arResult["ALL_ITEMS"]);
?>

<!-- Nav Menu -->
<!-- Collect the nav links, forms, and other content for toggling -->
<div class="collapse navbar-collapse navbar-responsive-collapse">
		<ul class="nav navbar-nav">
		<?foreach($arResult["MENU_STRUCTURE"] as $itemID => $arColumns):?>
			<!-- first level-->
			<li
				<?if (is_array($arColumns) && count($arColumns) > 0):?>
				class="dropdown menu_on_color menu_on_mobile <?if($arResult["ALL_ITEMS"][$itemID]["SELECTED"]):?>active<?endif?>"
				<?endif?>
				>
				<a
					href="<?if (is_array($arColumns) && count($arColumns) > 2):?>javascript:void(0);<?else:?><?=$arResult["ALL_ITEMS"][$itemID]["LINK"]?><?endif?>" <?if (is_array($arColumns) && count($arColumns) > 2):?>class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown"<?endif?>>
					<?=$arResult["ALL_ITEMS"][$itemID]["TEXT"]?>
				</a>
			<?if (is_array($arColumns) && count($arColumns) > 0):?>
					<?foreach($arColumns as $key=>$arRow):?>
						<ul class="dropdown-menu">
						<?foreach($arRow as $itemIdLevel_2=>$arLevel_3):?>  <!-- second level-->
							<li class="bx-nav-2-lvl">
								<a <?if($arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"] == "/upload/electronic_catalog_auto_chemical_goods.pdf"):?>  target="_blank" <?endif?> <?if($arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"] == "/upload/Bardahl_Catalog.pdf"):?>  target="_blank" <?endif?>
									href="<?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"]?>"
									<?if($arResult["ALL_ITEMS"][$itemIdLevel_2]["SELECTED"]):?>class="bx-active"<?endif?>
								>
									<span><?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["TEXT"]?></span>
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
			<?endif?>
			</li>
		<?endforeach;?>
		</ul>
</div>