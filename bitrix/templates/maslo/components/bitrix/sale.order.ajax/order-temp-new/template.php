<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if($USER->IsAuthorized() || $arParams["ALLOW_AUTO_REGISTER"] == "Y")
{
	if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
	{
		if(strlen($arResult["REDIRECT_URL"]) > 0)
		{
			$APPLICATION->RestartBuffer();
			?>
			<script type="text/javascript">
				window.top.location.href='<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';
			</script>
			<?
			die();
		}

	}
}

$APPLICATION->SetAdditionalCSS($templateFolder."/style_cart.css");
$APPLICATION->SetAdditionalCSS($templateFolder."/style.css");
?>

<a name="order_form"></a>

<div id="order_form_div" class="order-checkout">
<NOSCRIPT>
	<div class="errortext"><?=GetMessage("SOA_NO_JS")?></div>
</NOSCRIPT>

<?
if (!function_exists("getColumnName"))
{
	function getColumnName($arHeader)
	{
		return (strlen($arHeader["name"]) > 0) ? $arHeader["name"] : GetMessage("SALE_".$arHeader["id"]);
	}
}

if (!function_exists("cmpBySort"))
{
	function cmpBySort($array1, $array2)
	{
		if (!isset($array1["SORT"]) || !isset($array2["SORT"]))
			return -1;

		if ($array1["SORT"] > $array2["SORT"])
			return 1;

		if ($array1["SORT"] < $array2["SORT"])
			return -1;

		if ($array1["SORT"] == $array2["SORT"])
			return 0;
	}
}
?>

<div class="bx_order_make">
	<?
	if(!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N")
	{
		if(!empty($arResult["ERROR"]))
		{
			foreach($arResult["ERROR"] as $v)
				echo ShowError($v);
		}
		elseif(!empty($arResult["OK_MESSAGE"]))
		{
			foreach($arResult["OK_MESSAGE"] as $v)
				echo ShowNote($v);
		}

		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");
	}
	else
	{
		if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
		{
			if(strlen($arResult["REDIRECT_URL"]) == 0)
			{
				include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/confirm.php");
			}
		}
		else
		{
			?>
			<script type="text/javascript">

			<?if(CSaleLocation::isLocationProEnabled()):?>

				<?
				// spike: for children of cities we place this prompt
				$city = \Bitrix\Sale\Location\TypeTable::getList(array('filter' => array('=CODE' => 'CITY'), 'select' => array('ID')))->fetch();
				?>

				BX.saleOrderAjax.init(<?=CUtil::PhpToJSObject(array(
					'source' => $this->__component->getPath().'/get.php',
					'cityTypeId' => intval($city['ID']),
					'messages' => array(
						'otherLocation' => '--- '.GetMessage('SOA_OTHER_LOCATION'),
						'moreInfoLocation' => '--- '.GetMessage('SOA_NOT_SELECTED_ALT'), // spike: for children of cities we place this prompt
						'notFoundPrompt' => '<div class="-bx-popup-special-prompt">'.GetMessage('SOA_LOCATION_NOT_FOUND').'.<br />'.GetMessage('SOA_LOCATION_NOT_FOUND_PROMPT', array(
							'#ANCHOR#' => '<a href="javascript:void(0)" class="-bx-popup-set-mode-add-loc">',
							'#ANCHOR_END#' => '</a>'
						)).'</div>'
					)
				))?>);

			<?endif?>

			var BXFormPosting = false;
			function submitForm(val)
			{
				if (BXFormPosting === true)
					return true;

				BXFormPosting = true;
				if(val != 'Y')
					BX('confirmorder').value = 'N';

				var orderForm = BX('ORDER_FORM');
				BX.showWait();

				<?if(CSaleLocation::isLocationProEnabled()):?>
					BX.saleOrderAjax.cleanUp();
				<?endif?>

				var StepMakeOrder = false;
				var StepMakeOrder = '<?=SITE_DIR?>personal/cart/';

				$(document).ready(function () {
					//$('.load-city-list').hide();
					//StepWizard.initStepWizard();
				    //$("#order_form_content .actions ul li").eq(1).find('a').click();
				    //$("#order_form_content .actions ul li").eq($("#order_form_content .actions ul li").length-1).find('a').text('��������');
				    //$("#order_form_content .actions ul li").eq(0).find('a').text('�����');
				});

				BX.ajax.submit(orderForm, ajaxResult);

				return true;
			}
			
			

			function ajaxResult(res)
			{
				var orderForm = BX('ORDER_FORM');
				try
				{
					// if json came, it obviously a successfull order submit

					var json = JSON.parse(res);
					BX.closeWait();

					if (json.error)
					{
						BXFormPosting = false;
						return;
					}
					else if (json.redirect)
					{
						window.top.location.href = json.redirect;
					}
				}
				catch (e)
				{
					// json parse failed, so it is a simple chunk of html

					BXFormPosting = false;
					BX('order_form_content').innerHTML = res;

					<?if(CSaleLocation::isLocationProEnabled()):?>
						BX.saleOrderAjax.initDeferredControl();
					<?endif?>
				}

				BX.closeWait();
				BX.onCustomEvent(orderForm, 'onAjaxSuccess');

				if ($("#ID_DELIVERY_ID_2").attr("checked") == "checked") {
					$("#delivery-props").hide();
				} else {
					$("#delivery-props").show();
				}
			}


			function SetContact(profileId)
			{
				BX("profile_change").value = "Y";
				submitForm();
			}
			</script>
			<?if($_POST["is_ajax_post"] != "Y")
			{
				?>

<form class="shopping-cart sky-form" action="<?=$APPLICATION->GetCurPage();?>" method="POST" name="ORDER_FORM" id="ORDER_FORM" enctype="multipart/form-data">
				<?=bitrix_sessid_post()?>
                <div class="wizard">
	               		<div class="steps clearfix"><ul role="tablist"><li role="tab" class="first done" aria-disabled="false" aria-selected="false"><a id="order_form_content-t-0" href="<?=SITE_DIR?>personal/cart/" aria-controls="order_form_content-p-0"><span class="number">1.</span>
						          <div class="overflow-h">
						            <h2>�������</h2>
						            <p>��������� ��� �����</p>
						            <i class="rounded-x fa fa-check"></i>
						          </div>
						</a></li><li role="tab" class="last current" aria-disabled="false" aria-selected="true">
							<a id="order_form_content-t-1" href="#order_form_content-h-1" aria-controls="order_form_content-p-1"><span class="current-info audible"></span><span class="number">2.</span>
				          <div class="overflow-h">
				            <h2>����������</h2>
				            <p>�������� � ������</p>
				            <i class="rounded-x fa fa-home"></i> </div>
				        </a></li></ul></div>
				        <br/>
				<div id="order_form_content">

				<?
			}
			else
			{
				$APPLICATION->RestartBuffer();
			}

			if($_REQUEST['PERMANENT_MODE_STEPS'] == 1)
			{
				?>
				<input type="hidden" name="PERMANENT_MODE_STEPS" value="1" />
				<?
			}

			if(!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y")
			{
				foreach($arResult["ERROR"] as $v)
					echo ShowError($v);
				?>
				<script type="text/javascript">
					top.BX.scrollToNode(top.BX('ORDER_FORM'));
				</script>
				<?
			}
			?>
			<?
			if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d")
			{
				//include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
				include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
			}
			else
			{
				include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
				//include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
			}
			?>
			<h2 class="title-type">���������� ��� ������ � ��������</h2>
            <div class="billing-info-inputs checkbox-list">
			<?
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/person_type.php");
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props.php");
			?>
			</div>
			<?
			//include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/related_props.php");
            ?>
			<br />
            <div class="row">
				<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");?>
			</div>
            <?
			//include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/summary.php");
			if(strlen($arResult["PREPAY_ADIT_FIELDS"]) > 0)
				echo $arResult["PREPAY_ADIT_FIELDS"];
			?>

			<?if($_POST["is_ajax_post"] != "Y")
			{
				?>
					</div>

					<input type="hidden" name="confirmorder" id="confirmorder" value="Y">
					<input type="hidden" name="profile_change" id="profile_change" value="N">
					<input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
					<input type="hidden" name="json" value="Y">
                    <div class="actions clearfix">
	            		<? 
						$arBasketItems = array();
						$dbBasketItems = CSaleBasket::GetList(
						        array(
						                "NAME" => "ASC",
						                "ID" => "ASC"
						            ),
						        array(
						                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
						                "LID" => SITE_ID,
						                "ORDER_ID" => "NULL"
						            ),
						        false,
						        false,
						        array("ID", "CALLBACK_FUNC", "MODULE", 
						              "PRODUCT_ID", "QUANTITY", "DELAY", 
						              "CAN_BUY", "PRICE", "WEIGHT")
						    );
						while ($arItems = $dbBasketItems->Fetch())
						   {
							  $countPrice += $arItems["PRICE"]*$arItems["QUANTITY"];
						   }
						   
						   //������������� ���� ������ �� ����� ����� �� �������
						   $countPrice = $arResult["JS_DATA"]["TOTAL"]["ORDER_PRICE"];

	            		?>	
	            		<script>
	            			$( document ).ready(function() {
	            				$('#order_form_content').change(function(){
									var $diliveryPrise = $(this).find('.row .delivery-change-block .checkbox').find('input[checked]').parent(".checkbox").find(".bx_result_price b").html();
									var $diliveryPrise = $diliveryPrise.replace(/\s+/g,'');
									var $numdiliveryPrise = parseInt($diliveryPrise);
									var $numdiliveryPrise1 = $numdiliveryPrise + <?php echo $countPrice;?> + " ���";
									var discountPrice = $numdiliveryPrise + <?php echo $countPrice;?> + <?php echo $arResult["JS_DATA"]["TOTAL"]["DISCOUNT_PRICE"]; ?>;
									$('#umdiliveryPriseID').empty();
	            					$('#umdiliveryPriseID').text($numdiliveryPrise1);
									$('#discount-price').text(discountPrice);
								});
	            				
            				});
	            		</script>
							<p style="text-align: center; font-size: 18px;"><strong>�����:</strong>
							<?php if ($arResult["JS_DATA"]["TOTAL"]["DISCOUNT_PRICE"]) { ?><span id="discount-price" style="font-size: 12px; text-decoration: line-through"><?= $arResult["JS_DATA"]["TOTAL"]["ORDER_TOTAL_PRICE"] + $arResult["JS_DATA"]["TOTAL"]["DISCOUNT_PRICE"]; ?></span><?php } ?>
							<span id="umdiliveryPriseID" style="color: red;"><?php echo $countPrice;?> ���</span></p>
						</p>
                    	<p style="text-align: center;">�������� ������ "�������� �����" �� ������������ � <a style="color: #FFD600;display: inline-block;font-size: 16px;min-width: auto;padding: 0;text-align: left;background: transparent;text-decoration: none;text-transform: none;" href="/info/rules_sale_for_online_shop/" target="_blank">�������</a>�</p>
						<a href="javascript:void();" onclick="submitForm('Y'); return false;" id="ORDER_CONFIRM_BUTTON" class="checkout" role="menuitem">�������� �����</a><div class="button_to_back_end"><input class="button_to_back" type="button" onclick="history.back();" value="�����"/></div></li>
					</div>
					</div>
				</form>
				
				<br /><br />
				
				<div class="col-md-12">
				<h3 class="title-type question">�� ���� ��������: +7 (800) 500-49-67 (������ �� ������ ����������)</h3>
				
					 <? /* $APPLICATION->IncludeComponent(
						"bitrix:news.line",
						"help-in-cart",
						array(
							"IBLOCK_TYPE" => "services",
							"IBLOCKS" => array(
								0 => "8",
							),
							"NEWS_COUNT" => "3",
							"FIELD_CODE" => array(
								0 => "NAME",
								1 => "PREVIEW_TEXT",
								2 => "",
							),
							"SORT_BY1" => "ACTIVE_FROM",
							"SORT_ORDER1" => "DESC",
							"SORT_BY2" => "SORT",
							"SORT_ORDER2" => "ASC",
							"DETAIL_URL" => "",
							"ACTIVE_DATE_FORMAT" => "d.m.Y",
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "300",
							"CACHE_GROUPS" => "N",
							"COMPONENT_TEMPLATE" => "help-in-cart"
						),
						false
					); */ ?>
				</div>
				
				
				<?
				if($arParams["DELIVERY_NO_AJAX"] == "N")
				{
					?>
					<div style="display:none;"><?$APPLICATION->IncludeComponent("bitrix:sale.ajax.delivery.calculator", "", array(), null, array('HIDE_ICONS' => 'Y')); ?></div>
					<?
				}
			}
			else
			{
				?>
				<script type="text/javascript">
					top.BX('confirmorder').value = 'Y';
					top.BX('profile_change').value = 'N';
				</script>
				<?
				die();
			}
		}
	}
	?>
	</div>
</div>

<?if(CSaleLocation::isLocationProEnabled()):?>

	<div style="display: none">
		<?// we need to have all styles for sale.location.selector.steps, but RestartBuffer() cuts off document head with styles in it?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:sale.location.selector.steps",
			".default",
			array(
			),
			false
		);?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:sale.location.selector.search",
			".default",
			array(
			),
			false
		);?>
	</div>

<?endif?>
<script>
	$(document).ready(function(){
		console.log("test");
	});
</script>