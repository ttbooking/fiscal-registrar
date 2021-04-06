<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Faker\Provider\ru_RU;

use TTBooking\FiscalRegistrar\Faker\Provider\Commodity as BaseCommodity;

class Commodity extends BaseCommodity
{
    protected static array $commodityNames = [
        'Апельсины', 'Арбуз', 'Бананы', 'Булка', 'Булочка', 'Вино', 'Виноград', 'Вишня', 'Грибы', 'Дыня', 'Какао',
        'Капуста', 'Картофель', 'Клубника', 'Кофе', 'Лещ', 'Лимон', 'Мармелад', 'Молоко', 'Мука', 'Пельмени', 'Персики',
        'Печенье', 'Пиво', 'Пирожок', 'Пряники', 'Сливы', 'Сметана', 'Сок', 'Тыква', 'Чипсы', 'Шоколад', 'Яблоки',
    ];

    protected static array $commodityCharacteristics = [
        'Аппетитный', 'Ароматный', 'Большой', 'Вкусный', 'Волшебный', 'Кислый', 'Крупный', 'Маленький', 'Сладкий',
        'Смачный',
    ];
}
