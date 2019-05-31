<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if (!function_exists("showFilePropertyField"))
{
	function showFilePropertyField($name, $property_fields, $values, $max_file_size_show=50000)
	{
		$res = "";

		if (!is_array($values) || empty($values))
			$values = array(
				"n0" => 0,
			);

		if ($property_fields["MULTIPLE"] == "N")
		{
			$res = "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[0]\" id=\"".$name."[0]\"></label>";
		}
		else
		{
			$res = '
			<script type="text/javascript">
				function addControl(item)
				{
					var current_name = item.id.split("[")[0],
						current_id = item.id.split("[")[1].replace("[", "").replace("]", ""),
						next_id = parseInt(current_id) + 1;

					var newInput = document.createElement("input");
					newInput.type = "file";
					newInput.name = current_name + "[" + next_id + "]";
					newInput.id = current_name + "[" + next_id + "]";
					newInput.onchange = function() { addControl(this); };

					var br = document.createElement("br");
					var br2 = document.createElement("br");

					BX(item.id).parentNode.appendChild(br);
					BX(item.id).parentNode.appendChild(br2);
					BX(item.id).parentNode.appendChild(newInput);
				}
			</script>
			';

			$res .= "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[0]\" id=\"".$name."[0]\"></label>";
			$res .= "<br/><br/>";
			$res .= "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[1]\" id=\"".$name."[1]\" onChange=\"javascript:addControl(this);\"></label>";
		}

		return $res;
	}
}

if (!function_exists("PrintPropsForm"))
{
	function PrintPropsForm($arSource = array(), $locationTemplate = ".default")
	{
		if (!empty($arSource))
		{
			?>
				<div class="row margin-bottom-30">
                  <div class="col-sm-6">
                    <input id="name" type="text" placeholder="<?=$arSource[1]['~NAME']?><? if ($arSource[1]['REQUIED']=='Y'):?>*<?endif?>" value="<?=$arSource[1]['VALUE']?>" name="<?=$arSource[1]['FIELD_NAME']?>" class="form-control <? if ($arSource[1]['REQUIED']=='Y'):?>required<?endif?>">
                    <input id="phone" type="tel" placeholder="<?=$arSource[3]['~NAME']?><? if ($arSource[3]['REQUIED']=='Y'):?>*<?endif?>" value="<?=$arSource[3]['VALUE']?>" name="<?=$arSource[3]['FIELD_NAME']?>" class="form-control <? if ($arSource[3]['REQUIED']=='Y'):?>required<?endif?>">
                  </div>
                  <div class="col-sm-6">
                    <input id="<?=$arSource[9]['FIELD_NAME']?>" type="text" placeholder="<?=$arSource[9]['~NAME']?><? if ($arSource[9]['REQUIED']=='Y'):?>*<?endif?>" value="<?=$arSource[9]['VALUE']?>" name="<?=$arSource[9]['FIELD_NAME']?>" class="form-control <? if ($arSource[9]['REQUIED']=='Y'):?>required<?endif?>">
                    <input id="<?=$arSource[2]['FIELD_NAME']?>" type="text" placeholder="<?=$arSource[2]['~NAME']?><? if ($arSource[2]['REQUIED']=='Y'):?>*<?endif?>" value="<?=$arSource[2]['VALUE']?>" name="<?=$arSource[2]['FIELD_NAME']?>" class="form-control <? if ($arSource[2]['REQUIED']=='Y'):?>required<?endif?> email">
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6 city-selected-block">
                 		<?
							$value = 0;
							if (is_array($arSource[6]["VARIANTS"]) && count($arSource[6]["VARIANTS"]) > 0)
							{
								foreach ($arSource[6]["VARIANTS"] as $arVariant)
								{
									if ($arVariant["SELECTED"] == "Y")
									{
										$value = $arVariant["ID"];
										break;
									}
								}
								if (!$value){									//$value = $arSource[39]['DEFAULT_VALUE'];
								}

								$val = '';
								if ($value):
									$arLocs = CSaleLocation::GetByID($value);
					                $country_name =  $arLocs["COUNTRY_NAME_ORIG"];
					                $city_name = $arLocs["CITY_NAME_ORIG"];
					                $val = $country_name . ", " . $city_name;
				                endif;
							}
                 		?>
                 	  <input id="select_city" type="text" placeholder="<?=$arSource[6]['~NAME']?><? if ($arSource[6]['REQUIED']=='Y'):?>*<?endif?>" value="<?=$val?>" name="select_city" class="form-control <? if ($arSource[6]['REQUIED']=='Y'):?>required<?endif?>" onchange="loadCityChange(this);" onkeyup="loadCityChange(this);">
                  	  <input id="<?=$arSource[6]['FIELD_NAME']?>" type="hidden" placeholder="<?=$arSource[6]['~NAME']?><? if ($arSource[6]['REQUIED']=='Y'):?>*<?endif?>" value="<?=$value?>" name="<?=$arSource[6]['FIELD_NAME']?>" class="form-control <? if ($arSource[6]['REQUIED']=='Y'):?>required<?endif?>">
                      <div class="load-city-list"></div>
                  </div>
                </div>
                <input id="<?=$arSource[4]['FIELD_NAME']?>" type="text" placeholder="<?=$arSource[4]['~NAME']?><? if ($arSource[4]['REQUIED']=='Y'):?>*<?endif?>" value="<?=$arSource[4]['VALUE']?>" name="<?=$arSource[4]['FIELD_NAME']?>" class="form-control <? if ($arSource[4]['REQUIED']=='Y'):?>required<?endif?>">
                <div class="row">
                  <div class="col-sm-4">
                	 <input id="<?=$arSource[5]['FIELD_NAME']?>" type="text" placeholder="<?=$arSource[5]['~NAME']?><? if ($arSource[5]['REQUIED']=='Y'):?>*<?endif?>" value="<?=$arSource[5]['VALUE']?>" name="<?=$arSource[5]['FIELD_NAME']?>" class="form-control <? if ($arSource[5]['REQUIED']=='Y'):?>required<?endif?>">
                  </div>
                  <div class="col-sm-4">
                	<input id="<?=$arSource[10]['FIELD_NAME']?>" type="text" placeholder="<?=$arSource[10]['~NAME']?><? if ($arSource[10]['REQUIED']=='Y'):?>*<?endif?>" value="<?=$arSource[10]['VALUE']?>" name="<?=$arSource[10]['FIELD_NAME']?>" class="form-control <? if ($arSource[10]['REQUIED']=='Y'):?>required<?endif?>">
                  </div>
                  <div class="col-sm-4">
                 	<input id="<?=$arSource[11]['FIELD_NAME']?>" type="text" placeholder="<?=$arSource[11]['~NAME']?><? if ($arSource[11]['REQUIED']=='Y'):?>*<?endif?>" value="<?=$arSource[11]['VALUE']?>" name="<?=$arSource[11]['FIELD_NAME']?>" class="form-control <? if ($arSource[11]['REQUIED']=='Y'):?>required<?endif?>">
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-4">
                	 <input id="<?=$arSource[12]['FIELD_NAME']?>" type="text" placeholder="<?=$arSource[12]['~NAME']?><? if ($arSource[12]['REQUIED']=='Y'):?>*<?endif?>" value="<?=$arSource[12]['VALUE']?>" name="<?=$arSource[12]['FIELD_NAME']?>" class="form-control <? if ($arSource[12]['REQUIED']=='Y'):?>required<?endif?>">
                  </div>
                  <div class="col-sm-4">
                     <input id="<?=$arSource[7]['FIELD_NAME']?>" type="text" placeholder="<?=$arSource[7]['~NAME']?><? if ($arSource[7]['REQUIED']=='Y'):?>*<?endif?>" value="<?=$arSource[7]['VALUE']?>" name="<?=$arSource[7]['FIELD_NAME']?>" class="form-control <? if ($arSource[7]['REQUIED']=='Y'):?>required<?endif?>">
                  </div>
                  <div class="col-sm-4"> </div>
                </div>
                <label class="checkbox text-left"> Поля отмеченные звездочкой * обязательны к заполнению </label>
			<?
		}
	}
}
?>