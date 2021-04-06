<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Faker\Provider\en_US;

use TTBooking\FiscalRegistrar\Faker\Provider\Commodity as BaseCommodity;

class Commodity extends BaseCommodity
{
    protected static array $commodityNames = [
        'Apples', 'Bananas', 'Beer', 'Cabbage', 'Cacao', 'Cherry', 'Chips', 'Chocolate', 'Coffee', 'Cookies', 'Flour',
        'Grapes', 'Juice', 'Lemon', 'Melon', 'Mushrooms', 'Oranges', 'Peaches', 'Pie', 'Potatoes', 'Pumpkin',
        'Strawberries', 'Watermelon', 'Wine',
    ];

    protected static array $commodityCharacteristics = [
        'Big', 'Fragrant', 'Little', 'Magic', 'Savoury', 'Small', 'Sour', 'Spicy', 'Sweet', 'Yummy',
    ];
}
