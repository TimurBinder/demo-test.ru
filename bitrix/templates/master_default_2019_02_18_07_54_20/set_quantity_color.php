<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

	$basket_num = count($_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID]);
	
	//Ищем товар в корзине с данным id
	foreach($_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID] as $key=>$arItem)
	{
		if($arItem['ELEMENT_ID'] == $_POST["item_id"] && !$arItem['COLOR'] && !$arItem['KIND'])
		{
			$basket_num = $key;
			break;
		}
		if($arItem['ELEMENT_ID'] == $_POST["item_id"] && $arItem['COLOR'] && $arItem['COLOR'] == $_POST["FIELD_COLOR"])
		{
			$basket_num = $key;
			break;
		}
		if($arItem['ELEMENT_ID'] == $_POST["item_id"] && $arItem['KIND'] && $arItem['KIND'] == $_POST["FIELD_KIND"])
		{
			$basket_num = $key;
			break;
		}
	}

if(isset($_POST['id']))
{

	$id = intval($_POST['id']);

	if(CModule::IncludeModule("iblock")) 
	{ 
	    $arKind = array();
		$arSelect1 = Array("ID", "NAME", "PROPERTY_KIND");
		$arFilter1 = Array("IBLOCK_ID"=>3, "ACTIVE"=>"Y", "ID"=>$id);
        $res1 = CIBlockElement::GetList(Array(), $arFilter1, false, Array(), $arSelect1);
        while($ob1 = $res1->GetNextElement())
        {
            $arFields1 = $ob1->GetFields();
            $arKind[] = $arFields1["PROPERTY_KIND_VALUE"];
        }
        
        $arPrice = array();
		$arSelect1 = Array("ID", "NAME", "PROPERTY_PRICE_KIND");
		$arFilter1 = Array("IBLOCK_ID"=>3, "ACTIVE"=>"Y", "ID"=>$id);
        $res1 = CIBlockElement::GetList(Array(), $arFilter1, false, Array(), $arSelect1);
        while($ob1 = $res1->GetNextElement())
        {
            $arFields1 = $ob1->GetFields();
            $arPrice[] = $arFields1["PROPERTY_PRICE_KIND_VALUE"];
        }
        
		$arSelect = Array("ID", "NAME", "PROPERTY_PRICE", "PROPERTY_PRICE_KIND", "PROPERTY_KIND");
		$arFilter = Array("IBLOCK_ID"=>3, "ACTIVE"=>"Y", "ID"=>$id);
		$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
		$arItem = $res->GetNext();
	}
	
	?>
    <style>
    .set_quantity_color{
        width: 440px !important;
    }
    #FIELD_COLOR_msdd{
        width: 400px !important;
    }
    </style>
	
	<a href="#" class="btn-close">x</a>
	<div class="set_quantity_color_mess"></div>
	<form action="" method="POST" id="form_set_quantity_color">
		<input type="hidden" name="basket_num" value="<?=$basket_num?>">
		<input type="hidden" name="item_id" value="<?=$id?>">
		<div class="row">
			<div class="form-group col col-md-12 field-wrap">
				<label for="FIELD_PRODUCT_QUANTITY" class="control-label">Количество товара:<span class="required">*</span></label>
				<input type="text" id="FIELD_PRODUCT_QUANTITY" name="FIELD_PRODUCT_QUANTITY" value="1" class="form-control" required="">
			</div>
		</div>
		
		<?
			// получаем возможные варианты цветов и цен для тротуара, кевлара и т.д. (аналог SKU)
			//use \Bitrix\Main\Loader;
			//use Bitrix\Highloadblock as HL;
			//use Bitrix\Main\Entity;
			CModule::IncludeModule('iblock');
			CModule::IncludeModule('highloadblock');

			$arColor = array();
			$element_id = $id;
			if ($element_id) {
				$hl_codes = array();
				$res = CIBlockElement::GetProperty(3, $element_id, array("sort" => "asc"), array("CODE" => "COLOR_REF"));
				while ($ob = $res->GetNext())
				{
					$hl_codes[] = $ob['VALUE'];
				}
				
				
				
				$hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(1)->fetch();
				$entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
				$entityClass = $entity->getDataClass();
				
				$colors = array();
				foreach ($hl_codes as $hl_code) {
				   $res = $entityClass::getList(array(
					   'select' => array('*'),
					   'order' => array('ID' => 'ASC'),
					   'filter' => array('UF_XML_ID' => $hl_code)
				   ));
				   $color = array();	   
				   $color = $res->fetch();
				   $color['UF_FILE_PATH'] = CFile::GetPath($color["UF_FILE"]);	   	   	   
				   $colors[] = $color;
				}
				
				$color_prices = array();
				$res = CIBlockElement::GetProperty(3, $element_id, array("sort" => "asc"), array("CODE" => "PRICE_COLOR"));
				while ($ob = $res->GetNext())
				{
					$color_prices[] = $ob['VALUE'];
				}
				
				
				if (is_array($colors) && is_array($color_prices) && count($colors) > 0 && count($color_prices) > 0 && count($colors) == count($color_prices)) {
					$arColor['EXT']['COLORS'] = $colors;
					$arColor['EXT']['COLOR_PRICES'] = $color_prices;
				}
			}
		?>
		<?if(count($arColor['EXT']['COLOR_PRICES'][0])>0):?>
		<div class="row">
			<div class="form-group col col-md-12 field-wrap">
				<label for="FIELD_COLOR" class="control-label">Цвет:<span class="required">*</span></label>
				<select class="form-control" name="FIELD_COLOR" id="FIELD_COLOR" required>
				<?				
					foreach ($arColor['EXT']['COLORS'] as $i => $arValue){
				?>
					<option data-image="<?=$arValue['UF_FILE_PATH']?>" value="<?=$arValue['UF_NAME']?>" data-price="<?=intval(str_replace(" ", "", $arColor['EXT']['COLOR_PRICES'][$i]))?>"><?=$arValue['UF_NAME']?> (<?=$arColor['EXT']['COLOR_PRICES'][$i]?>)</option>
				<?} ?>
				</select>
				
			</div>
			
		</div>
		<?endif;?>
        
		<?if(!empty($arKind[0]) && !count($arColor['EXT']['COLOR_PRICES'][0])):?>
		<div class="row">
			<div class="form-group col col-md-12 field-wrap">
				<label for="FIELD_COLOR" class="control-label">Вид:<span class="required">*</span></label>
				<select class="form-control" name="FIELD_KIND" id="FIELD_COLOR" required>
				<?		
                    $i=0;		
					foreach ($arKind as $k => $v){
				?>
					<option data-image="" value="<?=$v?>" data-price="<?=intval(str_replace(" ", "", $arPrice[$k]))?>"><?=$v?> (<?=$arPrice[$k]?>)</option>
				
                <?$i++;
                } ?>
				</select>
				
			</div>
			
		</div>
		<?endif;?>
		
		<div class="row">
			<div class="form-group col col-md-12 field-wrap">
				<label for="FIELD_PRICE_ALL" class="control-label">Цена (итого, руб.):<span class="required">*</span></label>
				<input type="text" id="FIELD_PRICE_ALL" name="FIELD_PRICE_ALL" class="form-control disabled" disabled>
			</div>
		</div>
		
		<div class="rsform__bottom">
            <div class="rsform__bottom-button">
				<input type="hidden" name="FIELD_PRICE" id="FIELD_PRICE" value="<?=intval($arItem['PROPERTY_PRICE_VALUE'])?>">
                <input type="hidden" name="PARAMS_HASH" value="">
                <input type="submit" class="btn btn-primary" name="submit" value="Отправить">
            </div>
            <div class="rsform__bottom-ps"><span class="required">*</span> - Поля обязательные для заполнения</div>
        </div>
	</div>
	</form>
	
	
	<script>
		$('#form_set_quantity_color').submit(function(){
			$.ajax({
				type: "POST",
				url: "<?=SITE_TEMPLATE_PATH?>/set_quantity_color.php",
				data: $(this).serialize(),
				success: function (html) {
					$('.set_quantity_color_mess').html(html); 
					location.reload();
				}
			});
			return false;
		});
	</script>
	<script>
		$(function(){
			//Закрытие модального окна
			$('.btn-close').click(function(){
				$('.set_quantity_color').hide();
				$('.set_quantity_color_shaddow').hide();
				return false;
			});
			
			// расчет итоговой цены
			if($('#FIELD_COLOR').length){
				var price = $('#FIELD_COLOR option:selected').attr("data-price");
			}else{
				var price = $('#FIELD_PRICE').val();
			}
			
			var quantity = Math.round($('#FIELD_PRODUCT_QUANTITY').val());
			$('#FIELD_PRICE').val(price);
			$('#FIELD_PRICE_ALL').val(price * quantity);
			$('#FIELD_COLOR').change(function(){
				if($('#FIELD_COLOR').length){
					var price = $('#FIELD_COLOR option:selected').attr("data-price");
				}else{
					var price = $('#FIELD_PRICE').val();
				}
				var quantity = Math.round($('#FIELD_PRODUCT_QUANTITY').val());
				$('#FIELD_PRICE').val(price);
				$('#FIELD_PRICE_ALL').val(price * quantity);
			});
			$('#FIELD_PRODUCT_QUANTITY').change(function(){
				if($('#FIELD_COLOR').length){
					var price = $('#FIELD_COLOR option:selected').attr("data-price");
				}else{
					var price = $('#FIELD_PRICE').val();
				}
				
				var quantity = Math.round($('#FIELD_PRODUCT_QUANTITY').val());
				$('#FIELD_PRICE').val(price);
				$('#FIELD_PRICE_ALL').val(price * quantity);
			});
			
			// цвета в селекте
			$('#FIELD_COLOR').msDropDown();
		});

		</script>
	<?
	
	
}

if(isset($_POST['FIELD_PRODUCT_QUANTITY']))
{
    
foreach($_SESSION['REDSIGN_LIGHTBASKET_COLOR'] as $k => $v)
{
	if(!isset($_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$k]))
	{
		unset($_SESSION['REDSIGN_LIGHTBASKET_COLOR'][$k]);
	}
}
//Добавляем параметр вид
foreach($_SESSION['REDSIGN_LIGHTBASKET_KIND'] as $k => $v)
{
	if(!isset($_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$k]))
	{
		unset($_SESSION['REDSIGN_LIGHTBASKET_KIND'][$k]);
	}
}    
    
	if(CModule::IncludeModule("iblock")) 
	{ 
		$id = intval($_POST['item_id']);
		//$basket_num = $_POST['basket_num'];
		$quantity = $_POST['FIELD_PRODUCT_QUANTITY'];
		
		if($quantity > 0)
		{
			$arSelect = Array("ID", "NAME", "PROPERTY_PRICE");
			$arFilter = Array("IBLOCK_ID"=>3, "ACTIVE"=>"Y", "ID"=>$id);
			$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
			$arItem = $res->GetNext();
			
			if($arItem['ID']>0)
			{
				if(!isset($_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['ID']))
				{
					$_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['ID'] = $basket_num  + 1;
					$_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['ELEMENT_ID'] = $id;
					$_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['PRICE'] = $arItem['PROPERTY_PRICE_VALUE'];
					$_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['DISCOUNT_PRICE'] = $arItem['PROPERTY_PRICE_VALUE'];
					$_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['FULL_PRICE'] = $arItem['PROPERTY_PRICE_VALUE'];
					$_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['CURRENCY'] = "# руб";
					$_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['DISCOUNT'] = 0;
                    $_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['QUANTITY'] = $_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['QUANTITY'] + $quantity;
				}else{
				    $_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['QUANTITY'] = $_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['QUANTITY']+$quantity;
				}
				//$_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['QUANTITY'] = $quantity;
				if(isset($_POST['FIELD_COLOR']))
				{	
					$_SESSION['REDSIGN_LIGHTBASKET_COLOR'][$basket_num]= $_POST['FIELD_COLOR'];
					$_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['COLOR'] = $_POST['FIELD_COLOR'];
					$_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['PRICE'] = $_POST['FIELD_PRICE'];
					$_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['DISCOUNT_PRICE'] = $_POST['FIELD_PRICE'];
					$_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['FULL_PRICE'] = $_POST['FIELD_PRICE'];
				}
                if(isset($_POST['FIELD_KIND']))
				{	
					$_SESSION['REDSIGN_LIGHTBASKET_KIND'][$basket_num] = $_POST['FIELD_KIND'];
					$_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['KIND'] = $_POST['FIELD_KIND'];
					$_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['PRICE'] = $_POST['FIELD_PRICE'];
					$_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['DISCOUNT_PRICE'] = $_POST['FIELD_PRICE'];
					$_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID][$basket_num]['FULL_PRICE'] = $_POST['FIELD_PRICE'];
				}
				echo "Товар добавлен в корзину";
				
			}
		}else{
			echo "Ошибка! Неверное кол-во";
		}
	}
	
}

//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>
