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
?>

<!-- Subscribe -->
<div class="shop-subscribe">
	<?
		$frame = $this->createFrame("subscribe-form", false)->begin();
	?>
	<div class="container" class="subscribe-form"  id="subscribe-form">
		<div class="col-md-8 md-margin-bottom-20">
		    <h2>Подписка на еженедельные <strong>новости</strong></h2>
		</div>
		<form action="<?=$arResult["FORM_ACTION"]?>" method='POST'>
			<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
				<input type="hidden" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>"/>
			<?endforeach;?>
	 		<div class="col-md-4">
		        <div class="input-group">
			          <input type="text" name="sf_EMAIL" placeholder="<?=GetMessage("subscr_form_email_title")?>" class="form-control" size="20" value="<?=$arResult["EMAIL"]?>" title="<?=GetMessage("subscr_form_email_title")?>" />
			          <span class="input-group-btn">
			          	 <button class="btn" type="submit" name="OK"><i class="fa fa-envelope-o"></i></button>
			          </span>
		         </div>
		     </div>
	    </form>
	</div>
	<?
		$frame->beginStub();
	?>

	<form action="<?=$arResult["FORM_ACTION"]?>" method='POST'>
		<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
			<input type="hidden" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>"/>
		<?endforeach;?>
		<div class="col-md-4">
	        <div class="input-group">
		          <input type="text" name="sf_EMAIL" placeholder="<?=GetMessage("subscr_form_email_title")?>" class="form-control" size="20" value="<?=$arResult["EMAIL"]?>" title="<?=GetMessage("subscr_form_email_title")?>" />
		          <span class="input-group-btn">
		          	 <button class="btn" type="submit" name="OK"><i class="fa fa-envelope-o"></i></button>
		          </span>
	         </div>
	     </div>
	</form>
	<?
	$frame->end();
	?>
</div>
<!-- End Subscribe -->
