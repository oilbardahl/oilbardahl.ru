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

CJSCore::Init(array('fx', 'popup', 'window', 'ajax'));
?>

<a name="order_form"></a>

<div id="order_form_div" class="order-checkout">
<NOSCRIPT>
	<p class="bg-danger"><?=GetMessage("SOA_NO_JS")?></p>
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
			foreach($arResult["ERROR"] as $v){
				?><p class="bg-danger"><?=$v?></p><?
			}
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
				}
				BX.closeWait();
				var StepMakeOrder = false;
				var StepMakeOrder = '<?=SITE_DIR?>personal/cart/';

				$(document).ready(function () {
					$('.load-city-list').hide();
					StepWizard.initStepWizard();
				    $("#order_form_content .actions ul li").eq(1).find('a').click();
				});

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
				foreach($arResult["ERROR"] as $v){
					?><p class="bg-danger"><?=$v?></p><?
				}
				?>
				<script type="text/javascript">
					top.BX.scrollToNode(top.BX('ORDER_FORM'));
				</script>
				<?
			}
			?>
					<div class="header-tags">
			          <div class="overflow-h">
			            <h2>Корзина</h2>
			            <p>Проверьте ваш заказ</p>
			            <i class="rounded-x fa fa-check"></i> </div>
			        </div>
            		<section>
            		</section>
            	    <div class="header-tags">
			          <div class="overflow-h">
			            <h2>Оформление</h2>
			            <p>Доставка и оплата</p>
			            <i class="rounded-x fa fa-home"></i> </div>
			        </div>
			        <section class="billing-info">

						    	<h2 class="title-type">Информация для оплаты и доставки</h2>
						    	<div class="billing-info-inputs checkbox-list">
						    		<? include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props.php"); ?>
						    	</div>

            		</section>

			            <div class="row">
			            	<div class="col-md-6 md-margin-bottom-50">
				              	<h2 class="title-type">Выберите метод оплаты</h2>

								<?
								include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/person_type.php");
								///nclude($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props.php");
								include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
								if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d")
								{
									include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
								}
								else
								{
									include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
								}

								include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/related_props.php");

								//include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/summary.php");
								if(strlen($arResult["PREPAY_ADIT_FIELDS"]) > 0)
									echo $arResult["PREPAY_ADIT_FIELDS"];
								?>
							</div>
							<div class="col-md-6">
								 <?$APPLICATION->IncludeComponent(
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
								);?>
							</div>
						</div>

						<div class="coupon-code">
				          <div class="row">
				            <div class="col-sm-4 sm-margin-bottom-30">
				             </div>
				            <div class="col-sm-3 col-sm-offset-5">
				              <ul class="list-inline total-result">
				                <li>
				                  <h4>Сумма:</h4>
				                  <div class="total-result-in"> <span><?=$arResult["ORDER_PRICE_FORMATED"]?></span> </div>
				                </li>
				                <? if ($arResult['DELIVERY_PRICE_FORMATED']):?>
					                <li>
					                  <h4>Доставка:</h4>
					                  <div class="total-result-in"> <span class="text-right"><?=$arResult['DELIVERY_PRICE_FORMATED']?></span> </div>
					                </li>
				                <? endif?>
				                <li class="divider"></li>
				                <li class="total-price">
				                  <h4>Всего:</h4>
				                  <div class="total-result-in"> <span><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></span> </div>
				                </li>
				              </ul>
				            </div>
				          </div>
				        </div>
			<?if($_POST["is_ajax_post"] != "Y")
			{
				?>

					</div>
					<input type="hidden" name="confirmorder" id="confirmorder" value="Y">
					<input type="hidden" name="profile_change" id="profile_change" value="N">
					<input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
					<input type="hidden" name="json" value="Y">
				</form>
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
