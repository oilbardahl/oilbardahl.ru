<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/header.php");
CJSCore::Init(array("fx"));
global $APPLICATION;
$curPage = $APPLICATION->GetCurPage(true);
?>
<? $mainDir = ($APPLICATION->GetCurDir()==SITE_DIR && $APPLICATION->GetCurPage()==SITE_DIR); ?>
<?php 
	// редирект пользователей с группой "Дилер" на страницу личного кабинета дилера
	if ((in_array(19, $USER->GetUserGroupArray()) == "1") && (in_array(1, $USER->GetUserGroupArray()) != "1")){
		if ((strpos($_SERVER['REQUEST_URI'], '/lc_diler/') === false) and (strpos($_SERVER['REQUEST_URI'], '/personal/') === false) and (strpos($_SERVER['REQUEST_URI'], '/contacts/') === false)) {
			header("Location: /lc_diler/");
		}
	}
?>
<?php if ((strpos($_SERVER['REQUEST_URI'], '/personal/order/detail/') !== false) && (isset($_GET['access']))) {
	 if (strpos($_SERVER['REQUEST_URI'], 'check_payment_info') === false){
		$url_for_get = $_SERVER['REQUEST_URI']."&check_payment_info=yes&#payment_info";
		header("Location: https://oilbardahl.ru".$url_for_get."");
	}
}?>
<!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<?php 
		$full_version = 'full_version';
		if (isset($_GET[$full_version])) {
			?><meta name="viewport" content="width=1200, initial-scale=1.0"><?
		} elseif (strpos($_SERVER['REQUEST_URI'], '/lc_diler/') !== false) {
			?><meta name="viewport" content="width=1200, initial-scale=1.0"><?
		} else {
			?><meta name="viewport" content="width=device-width, initial-scale=1.0"><?
		}
	?>
	<!--<meta name="viewport" content="width=750" />-->
	<meta name="format-detection" content="telephone=no">
	<!-- JS Global Compulsory -->
	<script async defer src="<?=SITE_TEMPLATE_PATH?>/assets/plugins/jquery/jquery.min.js"></script>
	<script async defer src="<?=SITE_TEMPLATE_PATH?>/assets/plugins/jquery/jquery-migrate.min.js"></script>
	<script async defer src="<?=SITE_TEMPLATE_PATH?>/assets/plugins/bootstrap/js/bootstrap.min.js"></script>

	<!-- Favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="<?=SITE_TEMPLATE_PATH?>/images/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?=SITE_TEMPLATE_PATH?>/images/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?=SITE_TEMPLATE_PATH?>/images/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?=SITE_TEMPLATE_PATH?>/images/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?=SITE_TEMPLATE_PATH?>/images/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?=SITE_TEMPLATE_PATH?>/images/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?=SITE_TEMPLATE_PATH?>/images/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?=SITE_TEMPLATE_PATH?>/images/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=SITE_TEMPLATE_PATH?>/images/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?=SITE_TEMPLATE_PATH?>/images/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=SITE_TEMPLATE_PATH?>/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?=SITE_TEMPLATE_PATH?>/images/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=SITE_TEMPLATE_PATH?>/images/favicon-16x16.png">
    <link rel="manifest" href="<?=SITE_TEMPLATE_PATH?>/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?=SITE_TEMPLATE_PATH?>/images/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

	<!-- Web Fonts -->
	<link rel='stylesheet' type='text/css' href='<?=SITE_TEMPLATE_PATH?>/assets/css/oil-font.css'>

	<!-- CSS Global Compulsory -->
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/plugins/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/css/shop.style.css">

	<!-- CSS Header and Footer -->
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/css/headers/header-v5.css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/css/footers/footer-v4.css">

	<!-- CSS Implementing Plugins -->
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/plugins/animate.css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/plugins/line-icons/line-icons.css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/plugins/sky-forms-pro/skyforms/css/sky-forms.css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/plugins/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/plugins/jquery-steps/css/custom-jquery.steps.css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/plugins/scrollbar/css/jquery.mCustomScrollbar.css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/plugins/owl-carousel/owl-carousel/owl.carousel.css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/plugins/fancybox/source/jquery.fancybox.css">
 	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/plugins/noUiSlider/jquery.nouislider.min.css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/plugins/revolution-slider/rs-plugin/css/settings.css">
	<!-- CSS Theme -->
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/css/theme-colors/default.css" id="style_color">
	<!-- CSS Customization -->
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/css/custom.css">
	<!-- mobile style -->
	<script async defer src="<?=SITE_TEMPLATE_PATH?>/assets/js/jquery.selectric.min.js"></script>
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/css/selectric.css">
	<?php if (strpos($_SERVER['REQUEST_URI'], '/lc_diler/') !== false) { ?>
		<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/css/style_diler.css">
	<?php } ?>

	<title><?$APPLICATION->ShowTitle()?></title>
	 <?$APPLICATION->ShowHead();?>
	<?CUtil::InitJSCore(Array("ajax"));?>
	<script type="text/javascript">
        function SITE_TEMPLATE_DIR(){return '<?=SITE_TEMPLATE_PATH?>/'};
    </script>
	<meta name="yandex-verification" content="1d457ca1f094708b" />
	<meta name="google-site-verification" content="RpOwOwRF9GxfP4WboMcyHUScMIBUBRqeAqSbu5IHohw" />
	
	<!-- Yandex.Metrika counter -->
	<script type="text/javascript" >
	    (function (d, w, c) {
	        (w[c] = w[c] || []).push(function() {
	            try {
	                w.yaCounter37203285 = new Ya.Metrika2({
	                    id:37203285,
	                    clickmap:true,
	                    trackLinks:true,
	                    accurateTrackBounce:true,
	                    webvisor:true
	                });
	            } catch(e) { }
	        });

	        var n = d.getElementsByTagName("script")[0],
	            s = d.createElement("script"),
	            f = function () { n.parentNode.insertBefore(s, n); };
	        s.type = "text/javascript";
	        s.async = true;
	        s.src = "https://mc.yandex.ru/metrika/tag.js";

	        if (w.opera == "[object Opera]") {
	            d.addEventListener("DOMContentLoaded", f, false);
	        } else { f(); }
	    })(document, window, "yandex_metrika_callbacks2");
	</script>
	<noscript><div><img src="https://mc.yandex.ru/watch/37203285" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->
	
	<?php
	switch (parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)) {
	case "/personal/cart/":
	?>
	<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=lojEqWLDXlmvHpa6qpxgEOyEUOdafWiXzrmTOWI8Efyr5bDq5HW*J7YNakm5*9t3S2NU7gaJlMzdfo*vNvOYaJayRbHitzGiT348nRuF4hfu6l604KF6x*PsFsVjDX/KEI6i4BRFT2BtZlHL*8wD8AYm3WZgafHSkaggqL9RhsQ-&pixel_id=1000068494';</script>
	<?php
		break;
	case "/personal/order/make/":
	?>
	<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=icHRrHT2icneuGUqzW8dPCNaiNVxpVmpOo0AIlq/xjG4/T7NQ93VjeJy9oNDoop9MUj39I*BXZFSWvPL/KEe4yLEUEUhyuSb0lKzS*V4GJ56qp7cROtU9IS5Vp63lxmjcIhD6/VLxA7/l24TRpiM53PKFw/E*iWfw6F3PBmvk4c-&pixel_id=1000068486';</script>
	<?php
		break;
	default:
	
	}
	
	?>
	<script async defer src="<?=SITE_TEMPLATE_PATH?>/assets/js/jquery.maskedinput.min.js"></script>
	<script>
		jQuery(document).ready(function() {
		    if (jQuery(window).width() <= '508'){
		        jQuery('button[class=navbar-toggle]').removeAttr('data-target', '.navbar-responsive-collapse' );
		        jQuery("head").append(jQuery("<link rel='stylesheet' href='/mobile-style.css' type='text/css' />"));
				jQuery(".navbar-toggle").click(function() {
					jQuery(".navbar-responsive-collapse").fadeIn("fast").addClass("in");
					jQuery(".navbar-nav").fadeIn("fast").addClass("mobile-active");
					jQuery(".mobile-serch").fadeIn("fast").addClass("mobile-active");
					jQuery(".close-mobile-menu").fadeIn("fast").addClass("close-mobile-menu-active");
				});
				jQuery(".close-mobile-menu").click(function() {
					jQuery(".navbar-nav").fadeOut("fast").removeClass("mobile-hide");
					jQuery(".input-serch-mobile").fadeOut("fast").removeClass("mobile-active");
					jQuery(".mobile-serch-imput").fadeOut("fast").removeClass("mobile-active");
					jQuery(".navbar-responsive-collapse").fadeOut("fast").removeClass("in");
					jQuery(".mobile-serch").fadeOut("fast").removeClass("mobile-active");
					jQuery(".close-mobile-menu").fadeOut("fast").removeClass("close-mobile-menu-active");
				});
				jQuery(".mobile-serch").click(function() {
					jQuery(".mobile-serch").fadeOut("fast").removeClass("mobile-active");
					jQuery(".navbar-nav").fadeIn("fast").addClass("mobile-hide");
					jQuery(".mobile-serch-imput").fadeIn("fast").addClass("mobile-active");
					jQuery(".input-serch-mobile").fadeIn("fast").addClass("mobile-active");
					jQuery(".search-open").fadeIn("fast").addClass("mobile-active");
				});
		    } else {
		    	jQuery('button[class=navbar-toggle]').attr('data-target', '.navbar-responsive-collapse' );
		    }

		});
	</script>
	<script async defer type="text/javascript" src="/dilery/become-a-dealer/custom.js"></script>
	<?php if ((strpos($_SERVER['REQUEST_URI'], '/personal/order/') !== false)){ ?>
				<script type="text/javascript"> 
			function PrintElem(elem) 
			{ 
				Popup($(elem).html()); 
			} 
				function Popup(data) 
			{ 
				var mywindow = window.open('', 'Cчет на оплату', 'height=600,width=800'); 
				mywindow.document.write('<html><head><title>Cчет на оплату</title>'); 
				mywindow.document.write('</head><body >'); 
				mywindow.document.write(data); 
				mywindow.document.write('</body></html>'); 
				mywindow.document.close(); // necessary for IE >= 10 
				mywindow.focus(); // necessary for IE >= 10 
				mywindow.print(); 
				mywindow.close(); 
				return true; 
			} 
		</script>
		<script async defer src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script>
		<script async defer src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
		<script>

			function print() {
				// $(this).parent().find('nobr:contains("СЧЕТ")').text().replace(/ /ig, '_')
				const filename  =  'Счет.pdf';

				html2canvas(document.querySelector('#print_bill_on_list')).then(canvas => {
					let pdf = new jsPDF('p', 'mm', 'a4');
					pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, 0, 211, 180);
					pdf.save(filename);
				});
			}
		</script>
	<?php } ?>
</head>
<body class="header-fixed">
    <div>
		<button class="close-mobile-menu"><i class="fa fa-times" aria-hidden="true"></i></button>
	</div>
<?	if ($_SERVER['REQUEST_URI'] == "/avtomobili/"){
		//header("HTTP/1.1 301 Moved Permanently"); 
		//header("Location: https://oilbardahl.ru/avto/"); 
		//exit(); 
	} elseif ($_SERVER['REQUEST_URI'] == "/o-nas/"){
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: https://oilbardahl.ru/info/history/"); 
		exit();
	}
?>

<? global $USER; ?>
<div id="panel"><?$APPLICATION->ShowPanel();?></div>
<div class="wrapper">
		<!--=== Header v5 ===-->
		<div class="header-v5 header-static">
			<!-- Topbar v3 -->
			<div class="topbar-v3">
				<div class="search-open">
					<div class="container">
					<?
                	$APPLICATION->IncludeComponent(
						"bitrix:search.title",
						"search",
						array(
							"NUM_CATEGORIES" => "1",
							"TOP_COUNT" => "17",
							"ORDER" => "date",
							"USE_LANGUAGE_GUESS" => "N",
							"CHECK_DATES" => "Y",
							"SHOW_OTHERS" => "N",
							"PAGE" => SITE_DIR."search.php",
							"CATEGORY_0_TITLE" => "",
							"CATEGORY_0" => array(
								0 => "iblock_catalog",
							),
							"CATEGORY_0_iblock_1c_catalog" => array(
								0 => "35",
							),
							"SHOW_INPUT" => "Y",
							"INPUT_ID" => "title-search-input",
							"CONTAINER_ID" => "title-search",
							"COMPONENT_TEMPLATE" => "search",
							"CATEGORY_0_iblock_catalog" => array(
								0 => "2",
							)
						),
						false
					);
				    ?>
					</div>
				</div>
				<div class="container">
					<div class="row" style="margin-right: -15px;margin-left: -15px;">
						<div class="col-sm-7">
							<!-- Topbar Navigation -->
							<ul class="left-topbar">
								<li>
									<a><i class="fa fa-phone" style="color: #ffdd00"></i>&nbsp;<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/phone.php"), false);?></a>
								</li>
								<li>
									<a href="/contacts/"><i class="fa fa-map-marker" style="color: #ffdd00"></i>&nbsp;<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/adress-head.php"), false);?></a>
								</li>
								<li>
									<a><i class="fa fa-phone" style="color: #ffdd00"></i>&nbsp;<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/phone800.php"), false);?></a>
								</li>
								<li style="margin-right: 0;">
									<a style="color: #FFD600 !important; text-transform: uppercase;" href="/contacts/">Контакты</a>
								</li>
							</ul><!--/end left-topbar-->
						</div>
						<div class="col-sm-77">
							<!--<a href="/rasprodazha/">Большая распродажа</a>-->
						</div>
						<div class="col-sm-5">
							<ul class="list-inline right-topbar pull-right">
								<li>
									<? if ($USER->IsAuthorized()):?>
											<a href="<?=SITE_DIR?>personal/">
												<i class="fa fa-user" style="color: #ffdd00"></i> <?=($USER->GetFirstName() ? $USER->GetFirstName() : $USER->GetLogin())?>
											</a> |
											<a href="?logout=yes"><i class="fa fa-sign-out" style="color: #ffdd00"></i>&nbsp;Выход</a>
										<?else: ?>
											 <a href="<?=SITE_DIR?>login/" class="auth">Войти</a>&nbsp;|
                            				 <a href="<?=SITE_DIR?>login/?register=yes" class="reg">Регистрация</a>
									<? endif?>
								</li>
								<li><i class="search fa fa-search search-button"></i></li>
							</ul>
						</div>
					</div>
				</div><!--/container-->
			</div>
			<!-- End Topbar v3 -->
			
			<!-- Navbar -->
			<div class="navbar navbar-default mega-menu" role="navigation">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="<?=SITE_DIR?>">
							<img id="logo-header" src="<?=SITE_TEMPLATE_PATH?>/assets/img/logo-bardahl.jpg" alt="Logo">
						</a>
					</div>
					<div class="mobile-login">
						<a href="<?=SITE_DIR?>login/"><i class="fa fa-user" aria-hidden="true"></i></a>
					</div>
					<!-- Shopping Cart -->
					<?

					// скрываем корзину меню если пользователь в группе "дилер"
					if ((in_array(1, $USER->GetUserGroupArray()) == "1") || (in_array(19, $USER->GetUserGroupArray()) != "1")){
				        $APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "basket", Array(
								"PATH_TO_BASKET" => SITE_DIR."personal/cart/",	// Страница корзины
									"PATH_TO_PERSONAL" => SITE_DIR."personal/",	// Страница персонального раздела
									"SHOW_PERSONAL_LINK" => "N",	// Отображать персональный раздел
									"SHOW_NUM_PRODUCTS" => "Y",	// Показывать количество товаров
									"SHOW_TOTAL_PRICE" => "Y",	// Показывать общую сумму по товарам
									"SHOW_EMPTY_VALUES" => "Y",	// Выводить нулевые значения в пустой корзине
									"SHOW_PRODUCTS" => "Y",	// Показывать список товаров
									"POSITION_FIXED" => "N",	// Отображать корзину поверх шаблона
									"COMPONENT_TEMPLATE" => ".default",
									"SHOW_AUTHOR" => "N",	// Добавить возможность авторизации
									"PATH_TO_REGISTER" => SITE_DIR."login/",	// Страница регистрации
									"PATH_TO_PROFILE" => SITE_DIR."personal/",	// Страница профиля
									"SHOW_DELAY" => "N",	// Показывать отложенные товары
									"SHOW_NOTAVAIL" => "N",	// Показывать товары, недоступные для покупки
									"SHOW_SUBSCRIBE" => "N",	// Показывать товары, на которые подписан покупатель
									"SHOW_IMAGE" => "Y",	// Выводить картинку товара
									"SHOW_PRICE" => "Y",	// Выводить цену товара
									"SHOW_SUMMARY" => "Y",	// Выводить подытог по строке
									"PATH_TO_ORDER" => SITE_DIR."personal/order/make/",	// Страница оформления заказа
								),
								false,
								array(
								"0" => ""
								)
							);
                		?>

						<?$APPLICATION->IncludeComponent(
							"bitrix:menu",
							"head_horizontal",
							array(
								"ROOT_MENU_TYPE" => "left",
								"MENU_CACHE_TYPE" => "A",
								"MENU_CACHE_TIME" => "36000000",
								"MENU_CACHE_USE_GROUPS" => "Y",
								"MENU_THEME" => "site",
								"CACHE_SELECTED_ITEMS" => "N",
								"MENU_CACHE_GET_VARS" => array(
								),
								"MAX_LEVEL" => "2",
								"CHILD_MENU_TYPE" => "sub_left",
								"USE_EXT" => "Y",
								"DELAY" => "N",
								"ALLOW_MULTI_SELECT" => "N",
								"COMPONENT_TEMPLATE" => "head_horizontal"
							),
							false
						);?>
					<?php } ?>
				</div>
			</div>
			<i class="search fa fa-search search-button mobile-serch"></i>
			<div class="mobile-serch-imput">
				<div class="search-open">
					<div class="container">
						<?
						$APPLICATION->IncludeComponent(
							"bitrix:search.title",
							"search",
							array(
								"NUM_CATEGORIES" => "1",
								"TOP_COUNT" => "17",
								"ORDER" => "date",
								"USE_LANGUAGE_GUESS" => "N",
								"CHECK_DATES" => "Y",
								"SHOW_OTHERS" => "N",
								"PAGE" => SITE_DIR."search.php",
								"CATEGORY_0_TITLE" => "",
								"CATEGORY_0" => array(
									0 => "iblock_catalog",
								),
								"CATEGORY_0_iblock_1c_catalog" => array(
									0 => "35",
								),
								"SHOW_INPUT" => "Y",
								"INPUT_ID" => "title-search-input",
								"CONTAINER_ID" => "title-search",
								"COMPONENT_TEMPLATE" => "search",
								"CATEGORY_0_iblock_catalog" => array(
									0 => "2",
								)
							),
							false
						);
					    ?>
					</div>
				</div>
			</div>
			<!-- End Navbar -->
	</div>

	<? if ($mainDir):?>
		<?$APPLICATION->IncludeComponent("bitrix:news.list", "slider-main", Array(
			"DISPLAY_DATE" => "N",	// Выводить дату элемента
				"DISPLAY_NAME" => "N",	// Выводить название элемента
				"DISPLAY_PICTURE" => "N",	// Выводить изображение для анонса
				"DISPLAY_PREVIEW_TEXT" => "N",	// Выводить текст анонса
				"AJAX_MODE" => "Y",	// Включить режим AJAX
				"IBLOCK_TYPE" => "services",	// Тип информационного блока (используется только для проверки)
				"IBLOCK_ID" => "4",	// Код информационного блока
				"NEWS_COUNT" => "20",	// Количество новостей на странице
				"SORT_BY1" => "ACTIVE_FROM",	// Поле для первой сортировки новостей
				"SORT_ORDER1" => "DESC",	// Направление для первой сортировки новостей
				"SORT_BY2" => "SORT",	// Поле для второй сортировки новостей
				"SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
				"FILTER_NAME" => "",	// Фильтр
				"FIELD_CODE" => array(	// Поля
					0 => "ID",
					1 => "",
				),
				"PROPERTY_CODE" => array(	// Свойства
					0 => "URL_BOY",
					1 => "LINE_TEXT",
					2 => "",
				),
				"CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
				"DETAIL_URL" => "",	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
				"PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода (только для типа текст)
				"ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
				"SET_TITLE" => "N",	// Устанавливать заголовок страницы
				"SET_BROWSER_TITLE" => "N",	// Устанавливать заголовок окна браузера
				"SET_META_KEYWORDS" => "N",	// Устанавливать ключевые слова страницы
				"SET_META_DESCRIPTION" => "N",	// Устанавливать описание страницы
				"SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
				"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
				"HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
				"PARENT_SECTION" => "",	// ID раздела
				"PARENT_SECTION_CODE" => "",	// Код раздела
				"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
				"CACHE_TYPE" => "A",	// Тип кеширования
				"CACHE_TIME" => "3600",	// Время кеширования (сек.)
				"CACHE_FILTER" => "Y",	// Кешировать при установленном фильтре
				"CACHE_GROUPS" => "Y",	// Учитывать права доступа
				"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
				"DISPLAY_BOTTOM_PAGER" => "N",	// Выводить под списком
				"PAGER_TITLE" => "Новости",	// Название категорий
				"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
				"PAGER_TEMPLATE" => "",	// Шаблон постраничной навигации
				"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
				"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
				"PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
				"SET_STATUS_404" => "N",	// Устанавливать статус 404
				"SHOW_404" => "N",	// Показ специальной страницы
				"MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
				"PAGER_BASE_LINK" => "",
				"PAGER_PARAMS_NAME" => "arrPager",
				"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
				"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
				"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
				"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
				"COMPONENT_TEMPLATE" => ".default"
			),
			false
		);?>
		
		<div id="what-is-bardahl">
			<a id="wib-link" href="/info/what-is-bardahl/">Что такое <span>Bardahl</span></a>
		</div>
		
		<?
            global $arSaleElement;
            $arSaleElement = array(
                "!CATALOG_QUANTITY"=>false,
                "!PROPERTY_VIEW_BEST_FOR_MAG" => false
            );
            ?>
			
			
		
	<?php /*	
		<div class="bg-yellow">
		   <div class="container content promo-main-block">
			    <div class="heading heading-v4 margin-bottom-20">
					<h2>Акции</h2>
				</div>
				<!--=== Illustration v2 ===-->
				<div class="illustration-v2">
						<div class="row">
								<!-- 1 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/prisadki_v_toplivo/fuel_treatment/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/a61/a6170df8b8778d77b80193be0d38aaa2.jpg" alt="FUEL TREATMENT 500 ML" title="FUEL TREATMENT 500 ML">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/prisadki_v_toplivo/fuel_treatment/">FUEL TREATMENT 500 ML</a></h4>
													<span class="gender">
														Комплексно повышает качество бензина																									
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">985</span>
													<span class="title-price new-price">916 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=389" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 2 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/prisadki_v_dvigatel/full_metal_400ml/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/d0c/d0c0a4ae24a2e533c8a54d9ddf50946e.jpg" alt="FULL METAL 400ML" title="FULL METAL 400ML">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/prisadki_v_dvigatel/full_metal_400ml/">FULL METAL 400ML</a></h4>
													<span class="gender">
														Присадка в моторное масло																									
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">1700</span>
													<span class="title-price new-price">1581 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=387" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 3 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/prisadki_v_dvigatel/hydraulic_valve_lifters_300ml/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/09e/09ec4187f680a1a7e57e0a731dc8af62.jpg" alt="Hydraulic Valve Lifters 300ml" title="Hydraulic Valve Lifters 300ml">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/prisadki_v_dvigatel/hydraulic_valve_lifters_300ml/">Hydraulic Valve Lifters 300ml</a></h4>
													<span class="gender">
														Присадка в масло для очистки гидрокомпенсаторов.																									
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">695</span>
													<span class="title-price new-price">646 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=398" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 4 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/servisnaya_produktsiya/hygien_1_125ml/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/3c0/3c079016a78e02b544782d6e3c65f790.jpg" alt="HYGIEN 1 125ML" title="HYGIEN 1 125ML">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/servisnaya_produktsiya/hygien_1_125ml/">HYGIEN 1 125ML</a></h4>
													<span class="gender">
														Очиститель кондиционера №1																									
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">700</span>
													<span class="title-price new-price">651 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=464" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>			
						</div>
						
						<div class="row">
								<!-- 5 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/servisnaya_produktsiya/hygien_2_125ml/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/1f4/1f4d8711c96398eb2d2086350b51ff77.jpg" alt="HYGIEN 2 125ML" title="HYGIEN 2 125ML">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/servisnaya_produktsiya/hygien_2_125ml/">HYGIEN 2 125ML</a></h4>
													<span class="gender">
														Обработка кондиционера №2																									
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">700</span>
													<span class="title-price new-price">651 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=465" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 6 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/prisadki_v_dvigatel/maxi_compression_475ml/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/05c/05c3d9decfdddbe75e2c3a6aa1b5742e.jpg" alt="MAXI COMPRESSION 475ML" title="MAXI COMPRESSION 475ML">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/prisadki_v_dvigatel/maxi_compression_475ml/">MAXI COMPRESSION 475ML</a></h4>
													<span class="gender">
														Улучшает свойства любых моторных масел.																									
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">1000</span>
													<span class="title-price new-price">930 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=400" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 7 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_kpp_i_reduktor/atf_multi_7_gear_1l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/835/835cd6f0e8867f59a14f29c2b15705e0.jpg" alt="ATF MULTI 7 GEAR 1L" title="ATF MULTI 7 GEAR 1L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_kpp_i_reduktor/atf_multi_7_gear_1l/">ATF MULTI 7 GEAR 1L</a></h4>
													<span class="gender">
														Синтетическое масло для АКПП																								
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">900</span>
													<span class="title-price new-price">837 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=469" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>
								<!-- 8 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/prisadki_v_okhlazhdayushchuyu_zhidkost/radiator_cleaner_300ml/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/c6d/c6db70b99e5f214d11330d5fc2a2eca2.jpg" alt="RADIATOR CLEANER 300ML" title="RADIATOR CLEANER 300ML">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/prisadki_v_okhlazhdayushchuyu_zhidkost/radiator_cleaner_300ml/">RADIATOR CLEANER 300ML</a></h4>
													<span class="gender">
														Присадка в систему охлаждения для удаления накипи.																								
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">315</span>
													<span class="title-price new-price">292 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=403" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>
						</div>
						
						<div class="row">
								<!-- 9 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/prisadki_v_okhlazhdayushchuyu_zhidkost/radiator_oil_remover_300ml/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/c6d/c6db70b99e5f214d11330d5fc2a2eca2.jpg" alt="RADIATOR OIL REMOVER 300ML" title="RADIATOR OIL REMOVER 300ML">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/prisadki_v_okhlazhdayushchuyu_zhidkost/radiator_oil_remover_300ml/">RADIATOR OIL REMOVER 300ML</a></h4>
													<span class="gender">
														Присадка в систему охлаждения для удаления масла.																									
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">620</span>
													<span class="title-price new-price">576 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=403" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 10 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_dvigatel/technos_c60_5w40_1l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/4dc/4dc701f28ae74d3efce4d8c429640255.jpg" alt="TECHNOS C60 5W40 1L" title="TECHNOS C60 5W40 1L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_dvigatel/technos_c60_5w40_1l/">TECHNOS C60 5W40 1L</a></h4>
													<span class="gender">
														ACEA C3, API SN-CF, MB 229.31-226.5, BMW Longlife 04, VW 502.00-505.01, PORSCHE A40, Ford WSS-M2C917A, Renault RN0700-RN0710, GM dexos 2.																								
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">1200</span>
													<span class="title-price new-price">1116 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=408" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 11 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_dvigatel/technos_c60_5w30_1l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/1d4/1d4ca322229674b38930ae7904d27919.jpg" alt="TECHNOS C60 5W30 1L" title="TECHNOS C60 5W30 1L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_dvigatel/technos_c60_5w30_1l/">TECHNOS C60 5W30 1L</a></h4>
													<span class="gender">
														ACEA C3, API SN-CF, MB 229.51, BMW Longlife 04, GM dexos 2.																								
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">1200</span>
													<span class="title-price new-price">1116 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=411" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 12 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_dvigatel/xtc_5w30_5l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/28d/28d65ebe8bf3489efe63786cf3eff0ef.jpg" alt="XTC 5W30 5L" title="XTC 5W30 5L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_dvigatel/xtc_5w30_5l/">XTC 5W30 5L</a></h4>
													<span class="gender">
														ACEA C3 (12), API SN, MB 229.51/229.52, VW 502.00/505.00/505.01, DEXOS 2, BMW LL-04.																								
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">2445</span>
													<span class="title-price new-price">2274 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=348" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
						</div>
						
						
						<div class="row">
								<!-- 13 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_dvigatel/xtc_5w40_5l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/831/8311102596dea8aa6976909e958f619d.jpg" alt="XTC 5W40 5L" title="XTC 5W40 5L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_dvigatel/xtc_5w40_5l/">XTC 5W40 5L</a></h4>
													<span class="gender">
														ACEA A3/B4, VW 502.00/505.00, MB 229.3/226.5, GM LL-B-25, Porsche A40, FIAT 9.55535-M2, PSA B71 2296, RN0700/RN0710, BMW LL-01.																								
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">2100</span>
													<span class="title-price new-price">1953 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=382" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 14 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_dvigatel/xtc_c60_0w40_1l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/f6a/f6afc4d5e83d6184daba2430168ae416.jpg" alt="XTC C60 0W40 1L" title="XTC C60 0W40 1L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_dvigatel/xtc_c60_0w40_1l/">XTC C60 0W40 1L</a></h4>
													<span class="gender">
														ACEA A3-B4, API SN-CF, MB 229.5, BMW Longlife-01, VW 502.00-505.00, PORSCHE A40, Renault RN0700-RN0710.																								
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">1400</span>
													<span class="title-price new-price">1302 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=383" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 15 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_dvigatel/xtc_c60_5w40_1l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/95f/95f05b637df63ee864372bb02c8e6000.jpg" alt="XTC C60 5W40 1L" title="XTC C60 5W40 1L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_dvigatel/xtc_c60_5w40_1l/">XTC C60 5W40 1L</a></h4>
													<span class="gender">
														ACEA A3-B4, API SN-CF, MB 229.5, BMW Longlife-01, VW 502.00-505.00, PORSCHE A40, Renault RN0700-RN0710, OPEL LL-B-025.																							
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">1200</span>
													<span class="title-price new-price">1116 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=384" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 16 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_dvigatel/xtec_5w30_c2_1l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/076/0761b12bf10dec0dea52dfe3a5cab146.jpg" alt="XTEC 5W30 C2 1L" title="XTEC 5W30 C2 1L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_dvigatel/xtec_5w30_c2_1l/">XTEC 5W30 C2 1L</a></h4>
													<span class="gender">
														ACEA C2, API SN/CF, FIAT 9.55535-S1, RN0700.																						
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">730</span>
													<span class="title-price new-price">678 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=363" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
						</div>
						<div class="row">
								<!-- 17 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_dvigatel/xtec_5w30_c2_5l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/f8f/f8fb74d2e449b62ef61cc99f86852cef.jpg" alt="XTEC 5W30 C2 5L" title="XTEC 5W30 C2 5L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_dvigatel/xtec_5w30_c2_5l/">XTEC 5W30 C2 5L</a></h4>
													<span class="gender">
														ACEA C2, API SN/CF, FIAT 9.55535-S1, RN0700.																								
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">2620</span>
													<span class="title-price new-price">2436 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=364" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 18 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_dvigatel/xtec_5w30_c3_5l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/d52/d52db89a555c39d7aa63040c9a89297b.jpg" alt="XTEC 5W30 C3 5L" title="XTEC 5W30 C3 5L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_dvigatel/xtec_5w30_c3_5l/">XTEC 5W30 C3 5L</a></h4>
													<span class="gender">
														ACEA C3, VW 504.00/507.00, MB 229.51, BMW LL-04, Porsche C30.																								
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">3355</span>
													<span class="title-price new-price">3120 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=366" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 19 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_dvigatel/xtec_5w40_5l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/519/519ddbf7cb7a63d8bc4c321292d8c8b4.jpg" alt="XTEC 5W40 5L" title="XTEC 5W40 5L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_dvigatel/xtec_5w40_5l/">XTEC 5W40 5L</a></h4>
													<span class="gender">
														ACEA C2/C3 (12), API SN/SM/CF, MB 229.31/229.1/229.3, BMW LL-04, VW 500.00/502.00/505.00/505.01, FORD WSS-M2C-917A, RN0700/0710, GM DEXOS 2, Porsche A40.																							
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">2750</span>
													<span class="title-price new-price">2557 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=370" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 20 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_kpp_i_reduktor/xtg_cvt_1l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/779/77938dc5d7b5e2f061750d373b5d97a9.jpg" alt="XTG CVT 1L" title="XTG CVT 1L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_kpp_i_reduktor/xtg_cvt_1l/">XTG CVT 1L</a></h4>
													<span class="gender">
														Синтетическое масло для вариаторных КПП																							
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">850</span>
													<span class="title-price new-price">790 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=377" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>
						</div>	
						<div class="row">
								<!-- 21 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_kpp_i_reduktor/xtg_dct_1l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/33c/33c60feaac97a44ca489c5e9e7881c63.jpg" alt="XTG DCT 1L" title="XTG DCT 1L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_kpp_i_reduktor/xtg_dct_1l/">XTG DCT 1L</a></h4>
													<span class="gender">
														Синтетическое масло для КПП с двойным сцеплением																								
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">1050</span>
													<span class="title-price new-price">976 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=378" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 22 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_dvigatel/xts_0w20_1l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/6f9/6f9d3a12a6e416749432f465e579265d.jpg" alt="XTS 0W20 1L" title="XTS 0W20 1L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_dvigatel/xts_0w20_1l/">XTS 0W20 1L</a></h4>
													<span class="gender">
														Масло нового поколения, 100% синтетика, разработанная для оптимальной чистоты двигателя. Масло BARDAHL серии XTS 0w20 обладает высокими моющими свойствами, уменьшает выбросы, защищает двигатель от износа и чрезмерных отложений.																								
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">910</span>
													<span class="title-price new-price">846 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=350" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 23 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_dvigatel/xts_0w30_1l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/148/14864817ad65ebdab57b5bdfde5774ad.jpg" alt="XTS 0W30 1L" title="XTS 0W30 1L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_dvigatel/xts_0w30_1l/">XTS 0W30 1L</a></h4>
													<span class="gender">
														ACEA A1/B1 A5/B5, VW 503.00, 506.00, 506.01.																								
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">1150</span>
													<span class="title-price new-price">1069 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=352" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 24 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_dvigatel/xts_0w40_1l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/d28/d286077eb3207a6813a8ed138f814d98.jpg" alt="XTS 0W40 1L" title="XTS 0W40 1L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_dvigatel/xts_0w40_1l/">XTS 0W40 1L</a></h4>
													<span class="gender">
														ACEA A3/B4, API SM/CF, BMW LL-01, MB 229.3, Porsche A40, VW 502.00/505.00, RN0700/0710.																								
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">890</span>
													<span class="title-price new-price">827 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=354" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>
						</div>	
							<div class="row">
								<!-- 25 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_dvigatel/xts_0w40_5l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/2ef/2efd810d8a027de7a20ff741f03a4c47.jpg" alt="XTS 0W40 5L" title="XTS 0W40 5L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_dvigatel/xts_0w40_5l/">XTS 0W40 5L</a></h4>
													<span class="gender">
														ACEA A3/B4, API SM/CF, BMW LL-01, MB 229.3, Porsche A40, VW 502.00/505.00, RN0700/0710.																								
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">3675</span>
													<span class="title-price new-price">3417 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=355" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 26 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_dvigatel/xts_10w60_1l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/f1b/f1b282a264cf4e12bb755ad34174d594.jpg" alt="XTS 10W60 1L" title="XTS 10W60 1L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_dvigatel/xts_10w60_1l/">XTS 10W60 1L</a></h4>
													<span class="gender">
														API SL/CF, ACEA A3/B4.																							
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">910</span>
													<span class="title-price new-price">850 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=359" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 27 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_dvigatel/xts_5w30_1l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/42f/42f0d521668b74ed128c3580cc642a56.jpg" alt="XTS 5W30 1L" title="XTS 5W30 1L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_dvigatel/xts_5w30_1l/">XTS 5W30 1L</a></h4>
													<span class="gender">
														API SL/CF, ACEA A1/B1 (12) A5/B5 (12), FORD WSS-M2C913-D, RN 0700, Jaguar Land Rover STJR.03.5003.																						
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">695</span>
													<span class="title-price new-price">646 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=357" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
								<!-- 28 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/maslo_v_dvigatel/xts_5w30_5l/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/985/985f046adf02ea358320be0a97fe48e3.jpg" alt="XTS 5W30 5L" title="XTS 5W30 5L">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/maslo_v_dvigatel/xts_5w30_5l/">XTS 5W30 5L</a></h4>
													<span class="gender">
														API SL/CF, ACEA A1/B1 (12) A5/B5 (12), FORD WSS-M2C913-D, RN 0700, Jaguar Land Rover STJR.03.5003.																					
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">2480</span>
													<span class="title-price new-price">2306 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=358" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
							</div>
							<div class="row">
								<!-- 29 -->
								<div class="col-md-3 col-sm-6">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="/avtomobili/prisadki_v_kpp/manual_gear_box_stop_leak_150ml/">
													<img class="stack-images-list img-responsive" src="//812487.ssl.1c-bitrix-cdn.ru/upload/iblock/a54/a54427f5da6644e0918a27272ee05a99.jpg" alt="MANUAL GEAR BOX STOP LEAK 150ML" title="MANUAL GEAR BOX STOP LEAK 150ML">
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="/avtomobili/prisadki_v_kpp/manual_gear_box_stop_leak_150ml/">MANUAL GEAR BOX STOP LEAK 150ML</a></h4>
													<span class="gender">
														Присадка для предотвращения течей в МКПП																						
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price-old">745</span>
													<span class="title-price new-price">693 руб.</span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="/?action=ADD2BASKET&amp;id_slider=417" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>									
								</div>	
							</div>
				</div>
			</div>
	</div>
	
	*/ ?>

			
			<? $GLOBALS['arrFilter'] = Array("ACTIVE" => "Y", "PROPERTY" => array('?NEWPRODUCT' =>'1')); ?>
            <? $APPLICATION->IncludeComponent("bitrix:catalog.top", "mag_best", Array(
				"IBLOCK_TYPE_ID" => "catalog",
					"IBLOCK_ID" => "2",	// Инфоблок
					"ELEMENT_SORT_FIELD" => "RAND",	// По какому полю сортируем элементы
					"ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки элементов
					"ELEMENT_SORT_FIELD2" => "name",	// Поле для второй сортировки элементов
					"ELEMENT_SORT_ORDER2" => "asc",	// Порядок второй сортировки элементов
					"HIDE_NOT_AVAILABLE" => "N",	// Не отображать товары, которых нет на складах
					"ELEMENT_COUNT" => "16",	// Количество выводимых элементов
					"LINE_ELEMENT_COUNT" => "16",	// Количество элементов выводимых в одной строке таблицы
					"PROPERTY_CODE" => array(	// Свойства
						0 => "",
						1 => "",
					),
					"OFFERS_FIELD_CODE" => array(
						0 => "ID",
						1 => "NAME",
						2 => "",
					),
					"OFFERS_PROPERTY_CODE" => array(
						0 => "",
						1 => "",
					),
					"OFFERS_SORT_FIELD" => "sort",
					"OFFERS_SORT_ORDER" => "asc",
					"OFFERS_SORT_FIELD2" => "id",
					"OFFERS_SORT_ORDER2" => "desc",
					"OFFERS_LIMIT" => "0",	// Максимальное количество предложений для показа (0 - все)
					"VIEW_MODE" => "SLIDER",	// Показ элементов
					"TEMPLATE_THEME" => "site",	// Цветовая тема
					"PRODUCT_DISPLAY_MODE" => "Y",
					"ADD_PICT_PROP" => "MORE_PHOTO",	// Дополнительная картинка основного товара
					"LABEL_PROP" => "-",	// Свойство меток товара
					"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
					"OFFER_TREE_PROPS" => array(
						0 => "COLOR_REF",
						1 => "SIZES_SHOES",
						2 => "SIZES_CLOTHES",
					),
					"SHOW_DISCOUNT_PERCENT" => "Y",	// Показывать процент скидки
					"SHOW_OLD_PRICE" => "Y",	// Показывать старую цену
					"ROTATE_TIMER" => "30",	// Время показа одного слайда, сек (0 - выключить автоматическую смену слайдов)
					"MESS_BTN_BUY" => "Купить",	// Текст кнопки "Купить"
					"MESS_BTN_ADD_TO_BASKET" => "В корзину",	// Текст кнопки "Добавить в корзину"
					"MESS_BTN_DETAIL" => "Подробнее",	// Текст кнопки "Подробнее"
					"MESS_NOT_AVAILABLE" => "Нет в наличии",	// Сообщение об отсутствии товара
					"SECTION_URL" => "",	// URL, ведущий на страницу с содержимым раздела
					"DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
					"BASKET_URL" => "#SITE_DIR#personal/cart/",	// URL, ведущий на страницу с корзиной покупателя
					"ACTION_VARIABLE" => "action",	// Название переменной, в которой передается действие
					"PRODUCT_ID_VARIABLE" => "id_slider",	// Название переменной, в которой передается код товара для покупки
					"PRODUCT_QUANTITY_VARIABLE" => "quantity",	// Название переменной, в которой передается количество товара
					"PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
					"SECTION_ID_VARIABLE" => "SECTION_ID",	// Название переменной, в которой передается код группы
					"CACHE_TYPE" => "N",	// Тип кеширования
					"CACHE_TIME" => "180",	// Время кеширования (сек.)
					"CACHE_GROUPS" => "N",	// Учитывать права доступа
					"DISPLAY_COMPARE" => "N",	// Разрешить сравнение товаров
					"PRICE_CODE" => array(	// Тип цены
						0 => "BASE",
					),
					"USE_PRICE_COUNT" => "N",	// Использовать вывод цен с диапазонами
					"SHOW_PRICE_COUNT" => "1",	// Выводить цены для количества
					"PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
					"PRODUCT_PROPERTIES" => "",	// Характеристики товара
					"USE_PRODUCT_QUANTITY" => "Y",	// Разрешить указание количества товара
					"CONVERT_CURRENCY" => "N",	// Показывать цены в одной валюте
					"OFFERS_CART_PROPERTIES" => array(
						0 => "ARTNUMBER",
						1 => "COLOR_REF",
						2 => "SIZES_SHOES",
						3 => "SIZES_CLOTHES",
					),
					"IBLOCK_TYPE" => "catalog",	// Тип инфоблока
					"FILTER_NAME" => "arrFilter",	// Имя массива со значениями фильтра для фильтрации элементов
					"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
					"ADD_PROPERTIES_TO_BASKET" => "Y",	// Добавлять в корзину свойства товаров и предложений
					"PARTIAL_PRODUCT_PROPERTIES" => "N",	// Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
					"COMPONENT_TEMPLATE" => ".default",
					"SHOW_CLOSE_POPUP" => "N",	// Показывать кнопку продолжения покупок во всплывающих окнах
					"SHOW_PAGINATION" => "Y",	// Показывать навигацию по слайдам
					"MESS_BTN_COMPARE" => "Сравнить",	// Текст кнопки "Сравнить"
					"SEF_MODE" => "N",	// Включить поддержку ЧПУ
					"ADD_TO_BASKET_ACTION" => "ADD",	// Показывать кнопку добавления в корзину или покупки
				),
				false
			); ?>
			
	<?$APPLICATION->IncludeComponent(
	"bitrix:news.line", 
	"without-baners", 
	array(
		"IBLOCK_TYPE" => "services",
		"IBLOCKS" => array(
			0 => "5",
		),
		"NEWS_COUNT" => "2",
		"FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "PREVIEW_PICTURE",
			3 => "PROPERTY_LINK_TO_PAGE",
			4 => "PROPERTY_BUY_TO_PAGE",
			5 => "PROPERTY_BUY_MOBILE",
			6 => "PROPERTY_LINK_MOBILE",
		),
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "DESC",
		"DETAIL_URL" => "news_detail.php?ID=#ELEMENT_ID#",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"CACHE_GROUPS" => "Y",
		"COMPONENT_TEMPLATE" => "without-baners"
	),
	false
	);?>
	
	<?$APPLICATION->IncludeComponent(
	"bitrix:news.line",
	"baners",
	array(
		"IBLOCK_TYPE" => "services",
		"IBLOCKS" => array(
			0 => "5",
		),
		"NEWS_COUNT" => "1",
		"FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "PREVIEW_PICTURE",
			3 => "PROPERTY_LINK_TO_PAGE",
			4 => "PROPERTY_BUY_TO_PAGE",
		),
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"DETAIL_URL" => "news_detail.php?ID=#ELEMENT_ID#",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"CACHE_GROUPS" => "Y",
		"COMPONENT_TEMPLATE" => "baners"
	),
	false
);?>
<?
            global $arSaleElement;
            $arSaleElement = array(
                "!CATALOG_QUANTITY"=>false,
                "!PROPERTY_SALELEADER" => false
            );
            ?>
    <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.top", 
	"mag_sale", 
	array(
		"IBLOCK_TYPE_ID" => "catalog",
		"IBLOCK_ID" => "2",
		"ELEMENT_SORT_FIELD" => "RAND",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "name",
		"ELEMENT_SORT_ORDER2" => "asc",
		"HIDE_NOT_AVAILABLE" => "N",
		"ELEMENT_COUNT" => "8",
		"LINE_ELEMENT_COUNT" => "4",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_FIELD_CODE" => array(
			0 => "ID",
			1 => "NAME",
			2 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"OFFERS_LIMIT" => "0",
		"VIEW_MODE" => "SLIDER",
		"TEMPLATE_THEME" => "site",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"LABEL_PROP" => "-",
		"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
		"OFFER_TREE_PROPS" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
		),
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"SHOW_OLD_PRICE" => "Y",
		"ROTATE_TIMER" => "30",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"BASKET_URL" => "#SITE_DIR#personal/cart/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id_slider",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "180",
		"CACHE_GROUPS" => "N",
		"DISPLAY_COMPARE" => "N",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_PROPERTIES" => array(
		),
		"USE_PRODUCT_QUANTITY" => "Y",
		"CONVERT_CURRENCY" => "N",
		"OFFERS_CART_PROPERTIES" => array(
			0 => "ARTNUMBER",
			1 => "COLOR_REF",
			2 => "SIZES_SHOES",
			3 => "SIZES_CLOTHES",
		),
		"IBLOCK_TYPE" => "catalog",
		"FILTER_NAME" => "arSaleElement",
		"CACHE_FILTER" => "N",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"COMPONENT_TEMPLATE" => "mag_sale",
		"SHOW_CLOSE_POPUP" => "N",
		"SHOW_PAGINATION" => "Y",
		"MESS_BTN_COMPARE" => "Сравнить",
		"SEF_MODE" => "N",
		"ADD_TO_BASKET_ACTION" => "ADD"
	),
	false
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.line",
	"line-services",
	array(
		"IBLOCK_TYPE" => "services",
		"IBLOCKS" => array(
			0 => "6",
		),
		"NEWS_COUNT" => "3",
		"FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "PROPERTY_TYPE_ICON",
			3 => "PROPERTY_LINK_TO_PAGE",
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
		"COMPONENT_TEMPLATE" => "line-services"
	),
	false
);?>
<?$APPLICATION->IncludeComponent("bitrix:news.line", "news_main_page", Array(
	"IBLOCK_TYPE" => "news",	// Тип информационного блока
		"IBLOCKS" => array(	// Код информационного блока
			0 => "1",
		),
		"NEWS_COUNT" => "3",	// Количество новостей на странице
		"FIELD_CODE" => array(	// Поля
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "PREVIEW_PICTURE",
			3 => "",
			4 => "",
			5 => "",
		),
		"SORT_BY1" => "ACTIVE_FROM",	// Поле для первой сортировки новостей
		"SORT_ORDER1" => "DESC",	// Направление для первой сортировки новостей
		"SORT_BY2" => "SORT",	// Поле для второй сортировки новостей
		"SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
		"DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
		"ACTIVE_DATE_FORMAT" => "FULL",	// Формат показа даты
		"CACHE_TYPE" => "A",	// Тип кеширования
		"CACHE_TIME" => "300",	// Время кеширования (сек.)
		"CACHE_GROUPS" => "N",	// Учитывать права доступа
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?>
	<?else:?>
		<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "bread", Array(
           "START_FROM" => "0",	// Номер пункта, начиная с которого будет построена навигационная цепочка
               "PATH" => "",	// Путь, для которого будет построена навигационная цепочка (по умолчанию, текущий путь)
               "SITE_ID" => "-",	// Cайт (устанавливается в случае многосайтовой версии, когда DOCUMENT_ROOT у сайтов разный)
           ),
           false
	    );?>
	    <div class="bg-white">
	    	<? if ($_SERVER['REAL_FILE_PATH']=='/catalog_main.php'):?>
				<div>
					<div>
				<?else:?>
				<div class="content margin-bottom-30">
					<div class="container">
			<? endif?>
					<? if ($_SERVER['REAL_FILE_PATH']!=='/catalog_main.php' && !CSite::InDir('/contacts/') && !CSite::InDir('/news/') && !CSite::InDir('/personal/')):?>
						<h1 class="title"><?$APPLICATION->ShowTitle(false)?></h1>
					<? endif?>
<? endif;?>