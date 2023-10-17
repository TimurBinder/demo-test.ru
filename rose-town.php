<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("rose-town");
?><main> <section class="choose-appartment" id="choose-appartment">
<div class="container">
	<div class="row mt-lg-0 mt-4">
		<h2>Подобрать квартиру</h2>
	</div>
	<form method="GET" class="row mt-5">
		 <!-- Количество комнат -->
		<div data-radio="" class="col-xxl-2 col-lg-3 col-md-4 col-6">
 <figure id="rooms-count-wrap"> <figcaption>Количество комнат</figcaption>
			<div id="roomsCount" class="d-flex justify-content-between">
				<div class="select d-flex mt-3">
					<p>
						 1
					</p>
 <input type="radio" value="1" name="roomsCount">
				</div>
				<div class="select d-flex mt-3">
					<p>
						 2
					</p>
 <input type="radio" value="2" name="roomsCount">
				</div>
				<div class="select d-flex mt-3">
					<p>
						 3
					</p>
 <input type="radio" value="3" name="roomsCount">
				</div>
			</div>
 </figure>
		</div>
		 <!-- Стоимость -->
		<div data-range="" id="price-wrap" class="col-xxl-3 col-md-6 col-12 order-xxl-0 order-lg-3 order-md-2 mt-xxl-0 mt-lg-4 mt-3">
 <figure id="price-block"> <figcaption>Стоимость, млн.руб.</figcaption>
			<div id="price" class="select extremum mt-3 position-relative">
				<div class="d-flex justify-content-between w-100 h-100">
					<p class="value value-start d-flex">
						 от 6.3
					</p>
					<p class="value value-end d-flex">
						 до 12.9
					</p>
				</div>
				<div class="progress">
					<div>
					</div>
				</div>
				<div class="range">
 <input type="range" step="0.1" value="6.3" min="6.3" max="12.9" class="range-start"> <input type="range" step="0.1" value="12.9" min="6.3" max="12.9" class="range-end">
				</div>
			</div>
 </figure>
		</div>
		 <!-- Площадь -->
		<div data-range="" id="square-wrap" class="col-xxl-3 col-md-6 col-12 order-xxl-0 order-lg-4 order-md-2 mt-xxl-0 mt-lg-4 mt-3">
 <figure id="square-block"> <figcaption>Площадь, м2</figcaption>
			<div id="price" class="select extremum mt-3 position-relative">
				<div class="d-flex justify-content-between w-100 h-100">
					<p class="value value-start d-flex">
						 от 37,3
					</p>
					<p class="value value-end d-flex">
						 до 84,6
					</p>
				</div>
				<div class="progress">
					<div>
					</div>
				</div>
				<div class="range">
 <input type="range" step="0.1" value="37.3" min="37.3" max="84.6" class="range-start"> <input type="range" step="0.1" value="84.6" min="37.3" max="84.6" class="range-end">
				</div>
			</div>
 </figure>
		</div>
		 <!-- Кнопка -->
		<div class="col-xxl-3 col-lg-5 col-12 mt-lg-0 mt-5 order-xxl-0 order-lg-2 order-md-3 order-2 d-flex align-items-end">
 <button href="#homes" class="button option">Показать варианты</button>
		</div>
		 <!-- Тип -->
		<div data-select="" id="type-wrap" class="col-xxl-3 col-lg-2 col-md-6 col-12 order-xxl-0 order-lg-1 order-md-1 order-1 mt-4 d-xxl-block d-md-flex justify-content-md-end position-relative">
 <figure id="type-block"> <figcaption></figcaption>
			<div class="select mt-3">
 <span class="value">Шахматка</span> <span class="arrow ms-3"> </span>
				<select name="type" id="type">
				</select>
				<p>
				</p>
				<div class="select-values position-absolute">
					<option value="Шахматка">Шахматка</option>
					<option value="Тип1">Тип1</option>
					<option value="Тип2">Тип2</option>
					<option value="Тип3">Тип3</option>
					<option value="Тип4">Тип4</option>
					<option value="Тип5">Тип5</option>
				</div>
			</div>
 </figure>
		</div>
	</form>
	 <!-- Дома -->
	<div id="homes" class="row mt-5">
		<div class="row mt-5">
			<h3>Выберите квартиру</h3>
		</div>
		 <?$APPLICATION->IncludeComponent(
	"",
	"",
	Array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"IBLOCK_ID" => "3",
		"IBLOCK_TYPE" => "catalog"
	)
);?>
		<div class="status row">
			<div class="col-xxl-6 col-xl-7 col-12">
				<div class="row mt-5">
					<div class="onsale col-md-3 mt-md-0 mt-3 col-6 d-flex justify-content-md-center align-items-center">
						<div class="circle me-3">
						</div>
						<div class="status">
							 В продаже
						</div>
					</div>
					<div class="reserve col-md-3 mt-md-0 mt-3 col-6 d-flex justify-content-md-center align-items-center">
						<div class="circle me-3">
						</div>
						<div class="status">
							 Резерв
						</div>
					</div>
					<div class="booking col-md-3 mt-md-0 mt-3 col-6 d-flex justify-content-md-center align-items-center">
						<div class="circle me-3">
						</div>
						<div class="status">
							 Бронь
						</div>
					</div>
					<div class="sold col-md-3 col-6 mt-md-0 mt-3 d-flex justify-content-md-center align-items-center">
						<div class="circle me-3">
						</div>
						<div class="status">
							 Продано
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	 <!-- Информация о квартире -->
	<div id="appartment-info" class="appartment-block row">
		<form class="row" method="POST">
 <input type="hidden" name="home" value="1"> <input type="hidden" name="entrance" value="1"> <input type="hidden" name="appartment" value="1"> <input type="hidden" name="square" value="37,2"> <input type="hidden" name="roomsCount" value="1"> <input type="hidden" name="price" value="6733200"> <input type="hidden" name="hypothec" value="12000">
			<div class="col-xl-3 col-12">
				<div class="appartment-info mb-5 d-xl-block text-lg-start d-flex flex-column align-items-center text-center">
					<div class="title">
						<p>
 <span class="rooms-count">1</span> комнатная квартира
						</p>
						<p class="room-class">
							 1G
						</p>
					</div>
 <figure class="mt-5"> <figcaption>Площадь</figcaption>
					<p class="square">
						 37,2 м2
					</p>
 </figure> <figure class="mt-4"> <figcaption>Сторона света</figcaption>
					<p class="light-side">
						 восток
					</p>
 </figure> <figure class="mt-4"> <figcaption>Ипотека</figcaption>
					<p class="hypothec">
						 от 12 000 руб/мес
					</p>
 </figure> <figure class="mt-4"> <figcaption>Стоимость</figcaption>
					<p class="price">
						 6 733 200 руб
					</p>
 </figure> <button class="button mt-5">Оформить</button>
				</div>
			</div>
			<div class="col-xl-3 col-12 mt-5 d-xl-block d-flex flex-column align-items-center">
				<div class="appartment-plan">
 <img alt="План квартиры" src="/local/templates/roseTown/src/media/img/roomPlan.png">
				</div>
			</div>
			<div class="col-xl-6 col-12 mt-5 d-flex justify-content-end">
				<div class="appartment-slider slider">
					<div class="images">
 <img src="/local/templates/roseTown/src/media/img/roomSlide.png" class="selected" alt="Квартира"> <img src="/local/templates/roseTown/src/media/img/roomSlide.png" alt="Квартира"> <img src="/local/templates/roseTown/src/media/img/roomSlide.png" alt="Квартира"> <img src="/local/templates/roseTown/src/media/img/roomSlide.png" alt="Квартира">
					</div>
					<div class="slider-progress mt-5">
 <img src="/local/templates/roseTown/src/media/icons/arrowLeft.svg" class="arrow arrow-left">
						<div class="score">
 <span class="current">1</span>/<span class="total">4</span>
						</div>
 <img src="/local/templates/roseTown/src/media/icons/arrowRight.svg" class="arrow arrow-right">
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
 </section> <section class="info">
<div class="container">
	<div class="row">
		<div class="col-lg-3 col-12 position-relative d-flex flex-column align-items-center">
			<div class="num">
				 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/infoCards/1/num.php"
	)
);?>
			</div>
			<div class="num-desc">
				 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/infoCards/1/desc.php"
	)
);?>
			</div>
			<hr>
		</div>
		<div class="col-lg-3 col-12 mt-lg-0 mt-4 position-relative d-flex flex-column align-items-center">
			<div class="num">
				 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/infoCards/2/num.php"
	)
);?>
			</div>
			<div class="num-desc">
				 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/infoCards/2/desc.php"
	)
);?>
			</div>
			<hr>
		</div>
		<div class="col-lg-3 col-12 mt-lg-0 mt-4 position-relative d-flex flex-column align-items-center">
			<div class="num">
				 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/infoCards/3/num.php"
	)
);?>
			</div>
			<div class="num-desc">
				 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/infoCards/3/desc.php"
	)
);?>
			</div>
			<hr>
		</div>
		<div class="col-lg-3 col-12 mt-lg-0 mt-4 position-relative d-flex flex-column align-items-center">
			<div class="num">
				 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/infoCards/4/num.php"
	)
);?>
			</div>
			<div class="num-desc">
				 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/infoCards/4/desc.php"
	)
);?>
			</div>
		</div>
	</div>
</div>
 </section> <section id="location" class="location">
<div class="container">
	<div class="row">
		 <!-- Слайдер -->
		<div class="col-lg-6">
			<div class="location-slider slider d-lg-block d-flex flex-column align-items-center">
				<div class="images">
 <img src="/local/templates/roseTown/src/media/img/houseSlide.png" class="selected" alt="Квартира"> <img src="/local/templates/roseTown/src/media/img/roomSlide.png" alt="Квартира"> <img src="/local/templates/roseTown/src/media/img/roomSlide.png" alt="Квартира"> <img src="/local/templates/roseTown/src/media/img/roomSlide.png" alt="Квартира">
				</div>
				<div class="location-slider-progress slider-progress mt-5">
 <img src="/local/templates/roseTown/src/media/icons/arrowLeft.svg" class="arrow arrow-left">
					<div class="score">
 <span class="current">1</span>/<span class="total">4</span>
					</div>
 <img src="/local/templates/roseTown/src/media/icons/arrowRight.svg" class="arrow arrow-right">
				</div>
			</div>
		</div>
		 <!-- Информация -->
		<div class="col-lg-6 mt-lg-0 mt-5 text-lg-start text-center">
			<div class="row">
				<h2>Уникальное расположение</h2>
			</div>
			<div class="row">
				<p class="intro">
					 Наш жилой комплекс создан для современных людей, стремящихся жить в экологически чистом районе, но при этом быть рядом со всеми городскими событиями. <br>
 <br>
					 Роза Town располагается в уютном живописном месте, окруженном зеленью, между ТРК Сити Молл и аквапарком. ЖК прекрасно подходит для комфортной жизни в городской среде. В шаговой доступности находятся детский сад, школа, спортивные площадки, центры красоты, магазины, аквапарк, а также ледовый комплекс Арена Сити.
				</p>
			</div>
			<div class="row d-lg-block d-flex justify-content-center">
 <a class="button option" href="#">Подробнее</a>
			</div>
		</div>
	</div>
</div>
 </section> <section class="achievement">
<div class="container">
	<div class="row">
		 <!-- Карточка -->
		<div class="col-lg-4">
			<div class="row">
				<div class="card">
					<div class="col-4">
						 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/achievements/1/image.php"
	)
);?>
					</div>
					<div class="col-8">
						<h4>
						<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/achievements/1/title.php"
	)
);?> </h4>
						<div class="mt-2">
							 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/achievements/1/desc.php"
	)
);?>
						</div>
					</div>
				</div>
			</div>
		</div>
		 <!-- Карточка -->
		<div class="col-lg-4 mt-lg-0 mt-4">
			<div class="row">
				<div class="card">
					<div class="col-4">
						 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/achievements/2/image.php"
	)
);?>
					</div>
					<div class="col-8">
						<h4>
						<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/achievements/2/title.php"
	)
);?> </h4>
						<div class="mt-2">
							 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/achievements/2/desc.php"
	)
);?>
						</div>
					</div>
				</div>
			</div>
		</div>
		 <!-- Карточка -->
		<div class="col-lg-4 mt-lg-0 mt-4">
			<div class="row">
				<div class="card">
					<div class="col-4">
						 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/achievements/3/image.php"
	)
);?>
					</div>
					<div class="col-8">
						<h4>
						<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/achievements/3/title.php"
	)
);?> </h4>
						<div class="mt-2">
							 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/achievements/3/desc.php"
	)
);?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
 </section> <section id="contacts" class="info contacts">
<div class="container">
	<div class="row align-items-end text-lg-start text-center">
		<div class="col-lg-6">
			<div class="offer">
				<h2>Надежный застройщик</h2>
				<p class="mt-lg-5 mt-4">
					 Строительный холдинг «SSD Group» — динамично развивающаяся многопрофильная группа компаний, осуществляющая полный комплекс работ от проектирования до строительства «под ключ» жилья по Сахалинской области.
				</p>
			</div>
		</div>
		<div class="col-lg-6 mt-lg-0 mt-5">
			<div class="contacts ms-5">
				<div class="row">
					<div class="col-xl-1 me-xl-3 col-2">
 <img src="/local/templates/roseTown/src/media/icons/location.png" alt="Локация">
					</div>
					<div class="col-10">
						<div>
							 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/city.php"
	)
);?>
						</div>
						 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/street.php"
	)
);?>
						<div>
						</div>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-xl-1 me-xl-3 col-2">
 <img src="/local/templates/roseTown/src/media/icons//phone.png" alt="Телефон">
					</div>
					<div class="col-10">
						<div>
							 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/burger-numbers/1.php"
	)
);?>
						</div>
						<p>
							 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH."/include_areas/burger-numbers/2.php"
	)
);?>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
 </section> <section id="commissioning" class="commissioning">
<div class="container">
	<div class="row">
		<h2>Ввод в эксплуатацию</h2>
	</div>
	<div class="row mt-5">
		<p class="title-caption">
			 Выдача ключей: 2023
		</p>
	</div>
	 <!-- Карта -->
	<div class="row mt-5">
		<div class="col-lg-4 col-md-6">
			<div class="card mt-4 d-flex flex-column align-items-center">
				<p class="num">
					 1
				</p>
				<p class="num-desc">
					 дом
				</p>
				<p class="caption mt-3">
					 введен в эксплуатацию
				</p>
 <a class="button mt-4" href="#">Выбрать квартиру</a>
			</div>
		</div>
		 <!-- Карта -->
		<div class="col-lg-4 col-md-6">
			<div class="card mt-4 d-flex flex-column align-items-center">
				<p class="num">
					 2
				</p>
				<p class="num-desc">
					 дом
				</p>
				<p class="caption mt-3">
					 введен в эксплуатацию
				</p>
 <a class="button mt-4" href="#">Выбрать квартиру</a>
			</div>
		</div>
		 <!-- Карта -->
		<div class="col-lg-4 col-md-6">
			<div class="card mt-4 d-flex flex-column align-items-center">
				<p class="num">
					 3
				</p>
				<p class="num-desc">
					 дом
				</p>
				<p class="caption mt-3">
					 введен в эксплуатацию
				</p>
 <a class="button mt-4" href="#">Выбрать квартиру</a>
			</div>
		</div>
		 <!-- Карта -->
		<div class="col-lg-4 col-md-6 mt-lg-5 mt-4">
			<div class="card d-flex flex-column align-items-center">
				<p class="num">
					 4
				</p>
				<p class="num-desc">
					 дом
				</p>
				<p class="caption mt-3">
					 введен в эксплуатацию
				</p>
 <a class="button mt-4" href="#">Выбрать квартиру</a>
			</div>
		</div>
		 <!-- Карта -->
		<div class="col-lg-4 col-md-6 mt-lg-5 mt-4">
			<div class="card d-flex flex-column align-items-center">
				<p class="num">
					 5
				</p>
				<p class="num-desc">
					 дом
				</p>
				<p class="caption mt-3">
					 введен в эксплуатацию
				</p>
 <a class="button mt-4" href="#">Выбрать квартиру</a>
			</div>
		</div>
		 <!-- Карта -->
		<div class="col-lg-4 col-md-6 mt-lg-5 mt-4">
			<div class="card close d-flex flex-column align-items-center">
				<p class="num">
					 6
				</p>
				<p class="num-desc">
					 дом
				</p>
				<p class="caption mt-3">
					 введен в эксплуатацию
				</p>
				<p class="date mt-4">
					 Октябрь 2023
				</p>
			</div>
		</div>
	</div>
</div>
 </section> <section class="strict-visible">
<div class="container">
	<div class="row">
		<h2>Визуализация ЖК</h2>
	</div>
	<div class="row mt-md-5 mt-4">
		<div class="strict-visible-slider slider d-lg-block d-flex flex-column align-items-center">
			<div class="list mb-3">
				<div class="slide d-md-flex selected">
					<div class="col-md-6 col-12">
 <img src="/local/templates/roseTown/src/media/img/districtView1.png" class="w-100" alt="Квартира">
					</div>
					<div class="col-md-2 ms-md-2 me-md-2 col-12 mt-md-0 mt-1 d-flex flex-md-column flex-row justify-content-between">
 <img src="/local/templates/roseTown/src/media/img/districtView2.png" alt="Квартира"> <img src="/local/templates/roseTown/src/media/img/districtView3.png" alt="Квартира"> <img src="/local/templates/roseTown/src/media/img/districtView4.png" alt="Квартира">
					</div>
					<div class="col-md-3 col-12 me-md-1 mt-md-0 mt-1 d-md-block d-flex flex-md-column flex-row justify-content-between">
 <img src="/local/templates/roseTown/src/media/img/districtView5.png" alt="Квартира"> <img src="/local/templates/roseTown/src/media/img/districtView6.png" alt="Квартира">
					</div>
				</div>
				<div class="slide d-md-flex">
					<div class="col-md-6 col-12">
 <img src="/local/templates/roseTown/src/media/img/districtView1.png" class="w-100" alt="Квартира">
					</div>
					<div class="col-md-2 me-md-1 col-12 mt-md-0 mt-1 d-flex flex-md-column flex-row justify-content-between">
 <img src="/local/templates/roseTown/src/media/img/districtView2.png" alt="Квартира"> <img src="/local/templates/roseTown/src/media/img/districtView3.png" alt="Квартира"> <img src="/local/templates/roseTown/src/media/img/districtView4.png" alt="Квартира">
					</div>
					<div class="col-md-3 col-12 me-md-1 mt-md-0 mt-1 d-md-block d-flex flex-md-column flex-row justify-content-between">
 <img src="/local/templates/roseTown/src/media/img/districtView5.png" alt="Квартира"> <img src="/local/templates/roseTown/src/media/img/districtView6.png" alt="Квартира">
					</div>
				</div>
				<div class="slide d-md-flex">
					<div class="col-md-6 col-12">
 <img src="/local/templates/roseTown/src/media/img/districtView1.png" class="w-100" alt="Квартира">
					</div>
					<div class="col-md-2 me-md-1 col-12 mt-md-0 mt-1 d-flex flex-md-column flex-row justify-content-between">
 <img src="/local/templates/roseTown/src/media/img/districtView2.png" alt="Квартира"> <img src="/local/templates/roseTown/src/media/img/districtView3.png" alt="Квартира"> <img src="/local/templates/roseTown/src/media/img/districtView4.png" alt="Квартира">
					</div>
					<div class="col-md-3 col-12 me-md-1 mt-md-0 mt-1 d-md-block d-flex flex-md-column flex-row justify-content-between">
 <img src="/local/templates/roseTown/src/media/img/districtView5.png" alt="Квартира"> <img src="/local/templates/roseTown/src/media/img/districtView6.png" alt="Квартира">
					</div>
				</div>
			</div>
			<div class="strict-visible-slider-progress slider-progress mt-5">
 <img src="/local/templates/roseTown/src/media/icons/arrowLeft.svg" class="arrow arrow-left position-relative z-3">
				<div class="score">
 <span class="current">1</span>/<span class="total">4</span>
				</div>
 <img src="/local/templates/roseTown/src/media/icons/arrowRight.svg" class="arrow arrow-right position-relative z-3">
			</div>
		</div>
	</div>
</div>
 </section> <section id="plan" class="plan">
<div class="container">
	<div class="row">
		<h2>Планировки</h2>
	</div>
	<div class="row mt-5">
		 <!-- Количество комнат -->
		<div data-radio="" class="col-xxl-2 col-lg-3 col-md-4 col-6">
            <figure id="rooms-count-wrap"> <figcaption>Количество комнат</figcaption>
			<div id="roomsCount" class="d-flex justify-content-between">
				<div class="select d-flex mt-3">
					<p>
						 1
					</p>
                <input type="radio" value="1" name="roomsCount">
				</div>
				<div class="select d-flex mt-3">
					<p>
						 2
					</p>
                    <input type="radio" value="2" name="roomsCount">
				</div>
				<div class="select d-flex mt-3">
					<p>
						 3
					</p>
                    <input type="radio" value="3" name="roomsCount">
				</div>
			</div>
 </figure>
		</div>
	</div>

    <?$APPLICATION->IncludeComponent(
        "plans", 
        ".default", 
        array(
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "IBLOCK_ID" => "30",
            "IBLOCK_TYPE" => "system",
            "COMPONENT_TEMPLATE" => ".default"
        ),
        false
    );?>

	
</div>
 </section> <section class="map">
<div class="controls">
	<div class="plus">
		 +
	</div>
	<div class="minus mt-1">
		 -
	</div>
</div>
<div id="map">
</div>
 </section> </main><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>