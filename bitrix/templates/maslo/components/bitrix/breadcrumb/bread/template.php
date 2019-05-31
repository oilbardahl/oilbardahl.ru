<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string

	if(empty($arResult))
		return "";

	$strReturn = '';

	//we can't use $APPLICATION->SetAdditionalCSS() here because we are inside the buffered function GetNavChain()

	$strReturn .= '<div class="breadcrumbs"><div class="container"><div id="first-diler-promo">Первый официальный ритейлер в России</div><ul class="pull-right breadcrumb">';
	$itemSize = count($arResult);
	for($index = 0; $index < $itemSize; $index++)
	{
		$title = htmlspecialcharsex($arResult[$index]["TITLE"]);

		$nextRef = ($index < $itemSize-2 && $arResult[$index+1]["LINK"] <> ""? ' itemref="bx_breadcrumb_'.($index+1).'"' : '');
		$child = ($index > 0? ' itemprop="child"' : '');
		$arrow = ($index > 0? '<i class="fa fa-angle-right"></i>' : '');

		if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
		{
			$strReturn .= '
				<li itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
					<a href="'.$arResult[$index]["LINK"].'" title="'.$title.'" itemprop="url">
						'.$title.'
					</a>
				</li>';
		}
		else
		{
			if (strpos($_SERVER['REQUEST_URI'], '/lc_diler/') !== false) {
				$strReturn .= '
					<li class="active"> </li>';
			} else {
				$strReturn .= '
					<li class="active">'.$title.'</li>';
			}
		}
	}

	$strReturn .= '</ul></div></div>';

	return $strReturn;

