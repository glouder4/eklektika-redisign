<?php
namespace intec\eklectika;

use Bitrix\Main\Loader;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Fuser;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Context;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use CCatalogMeasureRatio;
use CStartShopBasket;
use CStartShopPrice;

class BasketActions
{
    /**
     * @var Basket
     */
    protected $basket = null;
    /**
     * @var boolean
     */
    protected $base = true;

    /**
     * @inheritdoc
     */
    public function beforeAction ($action)
    {
        if (parent::beforeAction($action)) {
            if (!Loader::includeModule('iblock'))
                return false;

            if (
                !Loader::includeModule('catalog') ||
                !Loader::includeModule('sale')
            ) {
                if (!Loader::includeModule('intec.startshop')) {
                    return false;
                } else {
                    $this->base = false;
                }
            }

            return true;
        }

        return false;
    }


    /**
     * Возвращает экземпляр корзины текущего пользователя.
     * @return Basket
     */
    protected static function getBasket() {
		return Basket::loadItemsForFUser(
			Fuser::getId(),
			Context::getCurrent()->getSite()
		);
    }

    /**
     * Вовзращает элемент корзины.
     * @param string $module
     * @param integer $id
     * @param Basket|null $basket
     * @return BasketItem|null
     */
    protected static function getBasketItem($module, $id, $basket = null)
    {
        if ($basket === null)
            $basket = self::getBasket();

        if (empty($basket))
            return null;

        /** @var BasketItem $item */
        foreach ($basket as $item)
            if ($item->getField('MODULE') == $module && $item->getProductId() == $id)
                return $item;

        return null;
    }

    /**
     * Возвращает структуру элементов.
     * @param $id
     * @return array
     */
    protected static function getElements($id) {
        $sections = [];
        $elements = [];
        $result = \CIBlockElement::GetList([], [
            'ID' => $id
        ]);

        while ($element = $result->Fetch()) {
            $element = [
                'id' => Type::toInteger($element['ID']),
                'name' => $element['NAME'],
                'isDelay' => false,
                'section' => $element['IBLOCK_SECTION_ID'],
                'price' => null,
                'quantity' => 1
            ];

            if (!empty($element['section']) && !ArrayHelper::isIn($element['section'], $sections))
                $sections[] = $element['section'];

            $elements[] = $element;
        }

        if (!empty($sections)) {
            $result = \CIBlockSection::GetList([], [
                'ID' => $sections
            ]);

            $sections = [];

            while ($section = $result->Fetch()) {
                $section = [
                    'id' => Type::toInteger($section['ID']),
                    'name' => $section['NAME']
                ];

                $sections[$section['id']] = $section;
            }

            unset($section, $sectionsResult);

            foreach ($elements as &$element) {
                if (!empty($element['section'])) {
                    $section = isset($sections[$element['section']]) ? $sections[$element['section']] : null;

                    if (!empty($section)) {
                        $element['section'] = $section;
                    } else {
                        $element['section'] = null;
                    }
                } else {
                    $element['section'] = null;
                }
            }

            unset($element);
        }

        unset($sections, $result);
       
		foreach ($elements as &$element) {
			/** @var BasketItem $item */
			$item = self::getBasketItem('catalog', $element['id']);

			if (empty($item)) {
				unset($element['price'], $element['quantity']);
				continue;
			}

			$element['isDelay'] = $item->getField('DELAY') === 'Y';
			$element['price'] = $item->getPrice();
			$element['quantity'] = $item->getField('QUANTITY');
			$element['quantity'] = Type::toInteger($element['quantity']);
		}

		unset($element);

        return $elements;
    }

    /**
     * Возвращает структуру элемента.
     * @param $id
     * @return array|mixed
     */
    protected static function getElement($id)
    {
        $result = static::getElements($id);

        if (count($result) > 0) {
            $result = $result[0];
        } else {
            $result = null;
        }

        return $result;
    }

    /**
     * Создание товара в корзине.
     * @param array $data Данные элемента инфоблока.
     * @post int $id Идентификатор элемента инфоблока.
     * @post int $quantity Количество. Необязательно.
     * @post array $properties Свойства, добавляемые в корзину. Необязательны.
     * @post string $currency Код валюты. Необязателен.
     * @post string $delay Добавить в отложенные. (Y/N).
     * @return bool
     */
    public static function createItem($data = []) {
        $id = ArrayHelper::getValue($data, 'id');
        $id = Type::toInteger($id);
        $price = ArrayHelper::getValue($data, 'price');
        $quantity = ArrayHelper::getValue($data, 'quantity');
        
		$quantity = Type::toFloat($quantity);
		$ratio = CCatalogMeasureRatio::getList([], ['PRODUCT_ID' => $id]);
		$ratio = $ratio->Fetch();
		$ratio = !empty($ratio) ? Type::toFloat($ratio['RATIO']) : 1;
		$quantity = $quantity < $ratio ? $ratio : $quantity;
		$properties = ArrayHelper::getValue($data, 'properties');
		$currency = ArrayHelper::getValue($data, 'currency');
		$delay = ArrayHelper::getValue($data, 'delay');
		$delay = $delay == 'Y' ? 'Y' : 'N';

		if (empty($id))
			return false;

		if (empty($currency))
			$currency = CurrencyManager::getBaseCurrency();

		$arElement = \CIBlockElement::GetByID($id)->GetNext();

		if (empty($arElement))
			return false;

		$arProduct = \CCatalogSku::GetProductInfo($id);

		$basket = self::getBasket();

		if ($item = self::getBasketItem('catalog', $id)) {
			$item->setFields(['DELAY' => $delay]);
		} else {
			/** @var BasketItem $item */
			$item = $basket->createItem('catalog', $id);
			$item->setFields([
				'PRODUCT_ID' => $id,
				'QUANTITY' => $quantity,
				'CURRENCY' => $currency,
				'DELAY' => $delay,
				'LID' => Context::getCurrent()->getSite(),
				'PRODUCT_PROVIDER_CLASS' => class_exists('\Bitrix\Catalog\Product\CatalogProvider') ?
					'\Bitrix\Catalog\Product\CatalogProvider' :
					'CCatalogProductProvider',
				'CATALOG_XML_ID' => $arElement['IBLOCK_EXTERNAL_ID'],
				'PRODUCT_XML_ID' => $arElement['EXTERNAL_ID']
			]);
		}

		$collection = $item->getPropertyCollection();

		if (!empty($arProduct) && Type::isArray($properties)) {
			$properties = \CIBlockPriceTools::GetOfferProperties(
				$id,
				$arElement['IBLOCK_ID'],
				$properties
			);

			if (!empty($properties))
				$collection->setProperty($properties);
		}

		$required = [];

		if (!empty($arElement['IBLOCK_EXTERNAL_ID']))
			$required[] = [
				'NAME' => 'Catalog XML_ID',
				'CODE' => 'CATALOG.XML_ID',
				'VALUE' => $arElement['IBLOCK_EXTERNAL_ID'],
				'SORT' => 100
			];

		if (!empty($arElement['EXTERNAL_ID']))
			$required[] = [
				'NAME' => 'Product XML_ID',
				'CODE' => 'PRODUCT.XML_ID',
				'VALUE' => $arElement['EXTERNAL_ID'],
				'SORT' => 100
			];

		if (!empty($required))
			$collection->setProperty($required);

		$basket->save();
       

        return $id;
    }

    /**
     * Множественное добавление товаров в корзину
     * @return array
     */
    public static function actionAddMultiple($data){
        
        $elements = [];

        if (!empty($data))
            foreach ($data as $item) {
                $result = self::createItem($item);

                if ($result !== false)
                    $elements[] = $result;
            }

        if (!empty($elements))
            $elements = self::getElements($elements);

        return $elements;
    }

    /**
     * Добавление товара в корзину
     * @return bool
     */
    public static function actionAdd($data)
    {
        $element = self::createItem($data);

        if ($element !== false) {
            $element = self::getElement($element);
        } else {
            $element = null;
        }

        return $element;
    }

    /**
     * Изменение количества товара в корзине.
     * @post int $id Идентификатор элемента инфоблока.
     * @post int $quantity Количество. Необязательно.
     * @return bool
     */
    public static function actionSetQuantity($data)
    {       
        $id = ArrayHelper::getValue($data, 'id');
        $id = Type::toInteger($id);

     
		$quantity = ArrayHelper::getValue($data, 'quantity');
		$quantity = Type::toFloat($quantity);
		$ratio = CCatalogMeasureRatio::getList([], ['PRODUCT_ID' => $id]);
		$ratio = $ratio->Fetch();
		$ratio = !empty($ratio) ? Type::toFloat($ratio['RATIO']) : 1;
		$quantity = $quantity < $ratio ? $ratio : $quantity;

		$basket = self::getBasket();

		if ($item = self::getBasketItem('catalog', $id)) {
			$item->setFields(['QUANTITY' => $quantity]);
			$basket->save();
		}
        

        return self::getElement($id);
    }

    /**
     * Возвращает список товаров в корзине.
     * @return array
     */
    public function actionGetItems()
    {
        $result = null;
        $id = [];

        
		$basket = self::getBasket();

		foreach ($basket as $item)
			if ($item->getField('MODULE') == 'catalog')
				$id[] = $item->getProductId();

       
        if (!empty($id)) {
            $result = self::getElements($id);
        } else {
            $result = [];
        }

        return $result;
    }

    /**
     * Удаление товара из корзины.
     * @post int $id Идентификатор элемента инфоблока.
     * @return bool
     */
    public function actionRemove($data)
    {       
        $id = ArrayHelper::getValue($data, 'id');
        $id = Type::toInteger($id);
        $result = null;

        if (empty($id))
            return false;
       
		$basket = self::getBasket();

		if ($item = self::getBasketItem('catalog', $id)) {
			$result = self::getElement($id);

			$item->delete();
			$basket->save();
		}
       

        return $result;
    }

    /**
     * Очистка корзины.
     * @post string $basket Очищать ли корзину. (Y/N).
     * @post string $delay Очищать ли отложенные. (Y/N).
     * @return bool
     */
    public static function actionClear(){
		
		$basket = true;
		$delay = true;

		/** @var BasketItem[] $items */
		$items = self::getBasket();

		foreach ($items as $item) {
			if (!$item->isDelay() && $basket)
				$id[] = $item->getField('PRODUCT_ID');

			if ($item->isDelay() && $delay)
				$id[] = $item->getField('PRODUCT_ID');
		}

		if (!empty($id))
			$result = self::getElements($id);

		foreach ($items as $item) {
			if (!$item->isDelay() && $basket)
				$item->delete();

			if ($item->isDelay() && $delay)
				$item->delete();
		}

		$items->save();
     

        return $result;
    }
}