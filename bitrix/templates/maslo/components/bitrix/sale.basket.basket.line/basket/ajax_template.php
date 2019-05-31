<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$this->IncludeLangFile('template.php');

$cartId = $arParams['cartId'];

require(realpath(dirname(__FILE__)).'/top_template.php');

if ($arParams["SHOW_PRODUCTS"] == "Y" && $arResult['NUM_PRODUCTS'] > 0)
{
	$summaryAll = 0;
?>
		<div class="badge-open">
			<ul class="list-unstyled mCustomScrollbar" data-mcs-theme="minimal-dark">
			<?foreach ($arResult["CATEGORIES"] as $category => $items):
				if (empty($items))
					continue;
				?>
				<?foreach ($items as $v):?>
				<li>
					<?
                    $summaryAll+=($v['QUANTITY']*$v['PRICE']);
					?>
					<?if ($arParams["SHOW_IMAGE"] == "Y" && $v["PICTURE_SRC"]):?>
						<?if($v["DETAIL_PAGE_URL"]):?>
							<a href="<?=$v["DETAIL_PAGE_URL"]?>"><img src="<?=$v["PICTURE_SRC"]?>" alt="<?=$v["NAME"]?>"></a>
						<?else:?>
							<img src="<?=$v["PICTURE_SRC"]?>" alt="<?=$v["NAME"]?>" />
						<?endif?>
					<?endif?>
					<button type="button" class="close" onclick="<?=$cartId?>.removeItemFromCart(<?=$v['ID']?>)">x</button>
					<div class="overflow-h">
						<span><?=$v["NAME"]?></span>
						<small><?=$v['QUANTITY']?> x <?=$v['PRICE_FMT']?></small>
					</div>
				</li>
				<?endforeach?>
			<?endforeach?>
			</ul>

			<div class="subtotal">
				<div class="overflow-h margin-bottom-10">
					<span>Итого:</span>
					<span class="pull-right subtotal-cost"><?=CurrencyFormat($summaryAll,"RUB")?></span>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<a href="<?=$arParams['PATH_TO_BASKET']?>" class="btn-u btn-brd btn-brd-hover btn-u-sea-shop btn-block">В корзину</a>
					</div>
					<div class="col-xs-6">
						<a href="<?=$arParams['PATH_TO_ORDER']?>" class="btn-u btn-u-sea-shop btn-block">Оформить заказ</a>
					</div>
				</div>
			</div>
		</div>
	<script>
		BX.ready(function(){
			<?=$cartId?>.fixCart();
		});
	</script>
<?
}
?>
