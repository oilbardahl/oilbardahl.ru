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
?>
<script src="<?=SITE_TEMPLATE_PATH?>/assets/plugins/jquery/jquery.min.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/assets/plugins/jquery/jquery-migrate.min.js"></script>
<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">
	<?foreach($arResult["HIDDEN"] as $arItem):?>
		<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
	<?endforeach;?>
	<?
		foreach($arResult["ITEMS"] as $key=>$arItem){
			if(
				empty($arItem["VALUES"])
				|| isset($arItem["PRICE"]) || $arItem['CODE']=='rating'
			)
				continue;

			if (
				$arItem["DISPLAY_TYPE"] == "A"
				&& (
					$arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0
				)
			)
				continue;
			?>	
			<?php if (strpos($_SERVER['REQUEST_URI'], '/lc_diler/') !== false) { ?>
				<?php //if ($arItem['ID'] == '119' || $arItem['ID'] == '121' || $arItem['ID'] == '125' || $arItem['ID'] == '122'): ?>
					<div class="panel-group" id="<?=$arItem['CODE']?>">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h2 class="panel-title">
									<a data-toggle="collapse" data-parent="#<?=$arItem['CODE']?>" href="#collapse<?=$arItem['CODE']?>">
										<?if ($arItem["NAME"] == "Допуски (дилер)") {
											$arItem["NAME"] = "Допуски";
										} elseif ($arItem["NAME"] == "Категория товара (дилер)") {
											$arItem["NAME"] = "Категория товара";
										} elseif ($arItem["NAME"] == "Литраж (дилер)") {
											$arItem["NAME"] = "Литраж";
										}?>
										<span><?=$arItem["NAME"]?></span>
										<i class="fa fa-angle-down"></i>
									</a>
								</h2>
							</div>
							<?php if (strpos($_SERVER['REQUEST_URI'], '/lc_diler/') !== false) { ?>
								<div id="collapse<?=$arItem['CODE']?>" class="panel-collapse collapse">
							<?php } else {?>
								<div id="collapse<?=$arItem['CODE']?>" class="panel-collapse collapse in">
							<?php }?>
								<div class="panel-body">
								<!-- поиск по фильтру -->

									<? if($arItem['ID'] == '119'){ ?>
										<div class="item_selected_param">
											<?php foreach($arResult['ITEMS'] as $property){
												foreach($property['VALUES'] as $values){
													if($values['CHECKED'] == 1 && $property['ID'] == '119'){
														$class = "";
														if ($ar["CHECKED"])
															$class.= " bx-active";
														if ($ar["DISABLED"])
															$class.= " disabled";
											            echo '<div class="filter_selected_param"><span>'.$values['VALUE'].'</span>';
											            ?>
											            <label for="<?=$values["CONTROL_ID"]?>" data-role="label_<?=$values["CONTROL_ID"]?>" class="<?=$values["CONTROL_ID"]?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($values["CONTROL_ID"])?>')); BX.removeClass(this, 'bx-active');"><i class="fa fa-times" aria-hidden="true"></i></label>
											        	<?php
											            echo '</div>';
											            $flag = 'n';
													}
												}
											}?>
										</div>
										<input type="text" name="pv[2]" data-param-id="2" class="filter_text" placeholder="" data-value="">
									<? } ?>

									<!-- категория товаров -->
									<? if($arItem['ID'] == '122'){ ?>
										<div class="item_selected_param">
											<?php foreach($arResult['ITEMS'] as $property){
												foreach($property['VALUES'] as $values){
													if($values['CHECKED'] == 1 && $property['ID'] == '122'){
														$class = "";
														if ($ar["CHECKED"])
															$class.= " bx-active";
														if ($ar["DISABLED"])
															$class.= " disabled";
														$dealer_category_selecteddd = $values['VALUE'];
											            echo '<div class="filter_selected_param"><span class"selected_category_dealer">'.$values['VALUE'].'</span>';
											            ?>
											            <label for="<?=$values["CONTROL_ID"]?>" data-role="label_<?=$values["CONTROL_ID"]?>" class="<?=$values["CONTROL_ID"]?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($values["CONTROL_ID"])?>')); BX.removeClass(this, 'bx-active');"><i class="fa fa-times" aria-hidden="true"></i></label>
											        	<?php
											            echo '</div>';
											            $flag = 'n';
													}
												}
											}?>
										</div>

										<?
										$chek_filter = false;
											foreach($arItem["VALUES"] as $val => $ar) {
												if($ar['CHECKED']) {
													$chek_filter = true;
												}
											}
										if ($chek_filter == false) { ?>
											<input type="text" name="pv[2]" data-param-id="2" class="filter_text" placeholder="" data-value="">
										<?php } ?>
									<? } ?>
									<!-- / категория товаров -->

									<? if($arItem['ID'] == '121'){ ?>
										<div class="item_selected_param">
											<?php foreach($arResult['ITEMS'] as $property){
												foreach($property['VALUES'] as $values){
													if($values['CHECKED'] == 1 && $property['ID'] == '121'){
														$class = "";
														if ($ar["CHECKED"])
															$class.= " bx-active";
														if ($ar["DISABLED"])
															$class.= " disabled";
											            echo '<div class="filter_selected_param"><span>'.$values['VALUE'].'</span>';
											            ?>
											            <label for="<?=$values["CONTROL_ID"]?>" data-role="label_<?=$values["CONTROL_ID"]?>" class="<?=$values["CONTROL_ID"]?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($values["CONTROL_ID"])?>')); BX.removeClass(this, 'bx-active');"><i class="fa fa-times" aria-hidden="true"></i></label>
											        	<?php
											            echo '</div>';
											            $flag = 'n';
													}
												}
											}?>
										</div>
										<input type="text" name="pv[2]" data-param-id="2" class="filter_text" placeholder="" data-value="">
									<? } ?>

									<? if($arItem['ID'] == '125'){ ?>
										<div class="item_selected_param">
											<?php foreach($arResult['ITEMS'] as $property){
												foreach($property['VALUES'] as $values){
													if($values['CHECKED'] == 1 && $property['ID'] == '125'){
														$class = "";
														if ($ar["CHECKED"])
															$class.= " bx-active";
														if ($ar["DISABLED"])
															$class.= " disabled";
											            echo '<div class="filter_selected_param"><span>'.$values['VALUE'].'</span>';
											            ?>
											            <label for="<?=$values["CONTROL_ID"]?>" data-role="label_<?=$values["CONTROL_ID"]?>" class="<?=$values["CONTROL_ID"]?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($values["CONTROL_ID"])?>')); BX.removeClass(this, 'bx-active');"><i class="fa fa-times" aria-hidden="true"></i></label>
											        	<?php
											            echo '</div>';
											            $flag = 'n';
													}
												}
											}?>
										</div>
										<input type="text" name="pv[2]" data-param-id="2" class="filter_text" placeholder="" data-value="">
									<? } ?>
								<!-- / поиск по фильтру -->
								<?php if ($chek_filter == false) { ?>
									<ul class="list-unstyled checkbox-list">									
										<?foreach ($arItem["VALUES"] as $val => $ar):?>
										
											<li class="search_li">
												<?
													$class = "";
													if ($ar["CHECKED"])
														$class.= " bx-active";
													if ($ar["DISABLED"])
														$class.= " disabled";
												?>
												<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="checkbox bx-filter-param-label <?=$class?> <?=$ar["CONTROL_ID"]?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'bx-active');">
												<input
													style="display: none"
													type="checkbox"
													name="<?=$ar["CONTROL_NAME"]?>"
													id="<?=$ar["CONTROL_ID"]?>"
													value="<?=$ar["HTML_VALUE"]?>"
													<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
																									/>
												<i></i>
												<span class="text_span_for_s"><?=$ar['VALUE']?></span>
												</label>
											</li>
										<?endforeach?>
									</ul>
								<?php } else { ?>
									<?php if ($arItem['ID'] == '122'){ ?>
										<ul class="list-unstyled checkbox-list display_hide">									
											<?foreach ($arItem["VALUES"] as $val => $ar):?>
											
												<li class="search_li">
													<?
														$class = "";
														if ($ar["CHECKED"])
															$class.= " bx-active";
														if ($ar["DISABLED"])
															$class.= " disabled";
													?>
													<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="checkbox bx-filter-param-label <?=$class?> <?=$ar["CONTROL_ID"]?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'bx-active');">
													<input
														style="display: none"
														type="checkbox"
														name="<?=$ar["CONTROL_NAME"]?>"
														id="<?=$ar["CONTROL_ID"]?>"
														value="<?=$ar["HTML_VALUE"]?>"
														<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
																										/>
													<i></i>
													<span class="text_span_for_s"><?=$ar['VALUE']?></span>
													</label>
												</li>
											<?endforeach?>
										</ul>
									<?php } else { ?>
										<ul class="list-unstyled checkbox-list">									
											<?foreach ($arItem["VALUES"] as $val => $ar):?>
											
												<li class="search_li">
													<?
														$class = "";
														if ($ar["CHECKED"])
															$class.= " bx-active";
														if ($ar["DISABLED"])
															$class.= " disabled";
													?>
													<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="checkbox bx-filter-param-label <?=$class?> <?=$ar["CONTROL_ID"]?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'bx-active');">
													<input
														style="display: none"
														type="checkbox"
														name="<?=$ar["CONTROL_NAME"]?>"
														id="<?=$ar["CONTROL_ID"]?>"
														value="<?=$ar["HTML_VALUE"]?>"
														<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
																										/>
													<i></i>
													<span class="text_span_for_s"><?=$ar['VALUE']?></span>
													</label>
												</li>
											<?endforeach?>
										</ul>
									<?php }?>
									
								<?php }?>
								</div>
							</div>
			  	  		</div>
			  		</div><!--/end panel group-->
				<?php //endif ?>
			<?php } elseif ($arItem['ID'] != '98' && $arItem['ID'] != '104' && $arItem['ID'] != '101' && $arItem['ID'] != '102') { ?>
				<div class="panel-group" id="<?=$arItem['CODE']?>">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h2 class="panel-title">
								<a data-toggle="collapse" data-parent="#<?=$arItem['CODE']?>" href="#collapse<?=$arItem['CODE']?>">
									<?=$arItem["NAME"]?>
									<i class="fa fa-angle-down"></i>
								</a>
							</h2>
						</div>
						<div id="collapse<?=$arItem['CODE']?>" class="panel-collapse collapse in">
							<div class="panel-body">
								<ul class="list-unstyled checkbox-list">
									<?foreach ($arItem["VALUES"] as $val => $ar):?>
										<li>
											<?
												$class = "";
												if ($ar["CHECKED"])
													$class.= " bx-active";
												if ($ar["DISABLED"])
													$class.= " disabled";
											?>

											<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="checkbox bx-filter-param-label <?=$class?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'bx-active');">
											<input
												style="display: none"
												type="checkbox"
												name="<?=$ar["CONTROL_NAME"]?>"
												id="<?=$ar["CONTROL_ID"]?>"
												value="<?=$ar["HTML_VALUE"]?>"
												<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
											/>
											<i></i>
											<?=$ar['VALUE']?>
											</label>
										</li>
									<?endforeach?>
								</ul>
							</div>
						</div>
			  	  </div>
			  </div><!--/end panel group-->
			<?php }?>



		<?
		}
		?>

		<?foreach($arResult["ITEMS"] as $key=>$arItem)//prices
		{
			$key = $arItem["ENCODED_ID"];
			if(isset($arItem["PRICE"])):
				if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
					continue;

				$precision = 2;
				if (Bitrix\Main\Loader::includeModule("currency"))
				{
					$res = CCurrencyLang::GetFormatDescription($arItem["VALUES"]["MIN"]["CURRENCY"]);
					$precision = $res['DECIMALS'];
				}
				?>
				<div class="panel-group" id="accordion-v4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h2 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion-v4" href="#collapseFour">
									<?=$arItem["NAME"]?>
									<i class="fa fa-angle-down"></i>
								</a>
							</h2>
						</div>
						<input
							class="min-price"
							type="hidden"
							name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
							id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
							value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
							size="5"
							onchange="console.log('eee'); smartFilter.keyup(this)"
						/>
						<input
							class="max-price"
							type="hidden"
							name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
							id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
							value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
							size="5"
							onchange="smartFilter.keyup(this)"
						/>
						<script>
						jQuery(document).ready(function() {
						  slider = jQuery('.slider-snap').noUiSlider({
			                start: [ <?echo ($arItem["VALUES"]["MIN"]["HTML_VALUE"] ? $arItem["VALUES"]["MIN"]["HTML_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"])?>, <?echo ($arItem["VALUES"]["MAX"]["HTML_VALUE"] ? $arItem["VALUES"]["MAX"]["HTML_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"])?> ],
			                connect: true,
			                range: {
			                    'min': <?echo $arItem["VALUES"]["MIN"]["VALUE"]?>,
			                    'max': <?echo $arItem["VALUES"]["MAX"]["VALUE"]?>
			                }
				            }).change(function(){
							   jQuery('#<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>').change();
							});
				            jQuery('.slider-snap').Link('lower').to(jQuery('.slider-snap-value-lower'));
            				jQuery('.slider-snap').Link('upper').to(jQuery('.slider-snap-value-upper'));
            				jQuery('.slider-snap').Link('upper').to(jQuery('#<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>'));
            				jQuery('.slider-snap').Link('lower').to(jQuery('#<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>'));
 						});
						</script>
						<div id="collapseFour" class="panel-collapse collapse in">
							<div class="panel-body">
								<div class="slider-snap"></div>
								<p class="slider-snap-text">
									<span class="slider-snap-value-lower"></span>
									<span class="slider-snap-value-upper"></span>
								</p>
							</div>
						</div>
					</div>
				</div><!--/end panel group-->
			<? endif?>
		<?}?>
		<?
		foreach($arResult["ITEMS"] as $key=>$arItem){
			if ($arItem['CODE']!=='rating'):
				continue;
			endif;
			//pr($arItem);
		?>
		<?php if (strpos($_SERVER['REQUEST_URI'], '/lc_diler/') !== false) {
			} else { ?>

			<div class="panel-group margin-bottom-30" id="accordion-v6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion-v6" href="#collapseSix">
								Рейтинг
								<i class="fa fa-angle-down"></i>
							</a>
						</h2>
					</div>
					<input
						class="min-price"
						type="hidden"
						name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
						id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
						value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
						size="5"
						onchange="smartFilter.keyup(this)"
					/>
					<input
						class="max-price"
						type="hidden"
						name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
						id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
						value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
						size="5"
						onchange="smartFilter.keyup(this)"
					/>

					<div id="collapseSix" class="panel-collapse collapse in">
						<div class="panel-body">
							<div class="stars-ratings stars-ratings-label">
								<? if ($arItem["VALUES"]["MAX"]["HTML_VALUE"]):?>
										<? for($i=1; $i<=5;$i++):?>
											<?if ($i<=$arItem["VALUES"]["MAX"]["HTML_VALUE"]):?>
												    <input type="radio" name="stars-rating" checked="checked" id="stars-rating-<?=$i?>">
													<label for="stars-rating-<?=$i?>"><i class="fa fa-star"></i></label>
												<?else:?>
													<input type="radio" name="stars-rating" id="stars-rating-<?=$i?>">
													<label for="stars-rating-<?=$i?>"><i class="fa fa-star"></i></label>
											<?endif;?>
										<? endfor?>
									<?else:?>
										<input type="radio" name="stars-rating" id="stars-rating-1">
										<label for="stars-rating-1"><i class="fa fa-star"></i></label>
										<input type="radio" name="stars-rating" id="stars-rating-2">
										<label for="stars-rating-2"><i class="fa fa-star"></i></label>
										<input type="radio" name="stars-rating" id="stars-rating-3">
										<label for="stars-rating-3"><i class="fa fa-star"></i></label>
										<input type="radio" name="stars-rating" id="stars-rating-4">
										<label for="stars-rating-4"><i class="fa fa-star"></i></label>
										<input type="radio" name="stars-rating" id="stars-rating-5">
										<label for="stars-rating-5"><i class="fa fa-star"></i></label>
								<? endif;?>
							</div>
						</div>
					</div>
				</div>
			</div><!--/end panel group-->
		<?php }?>
		<?}?>
	<?php if (strpos($_SERVER['REQUEST_URI'], '/lc_diler/') !== false) { ?>
	<?php } else { ?>
	<input
		class="btn-u btn-brd btn-brd-hover btn-u-lg btn-u-sea-shop btn-block"
		type="submit"
		id="del_filter"
		name="del_filter"
		value="Сбросить фильтр"
	/>
	<?php } ?>
	<input
		class="btn btn-themes"
		type="hidden"
		id="set_filter"
		name="set_filter"
		value="<?=GetMessage("CT_BCSF_SET_FILTER")?>"
	/>
</form>
<script>
	var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
</script>