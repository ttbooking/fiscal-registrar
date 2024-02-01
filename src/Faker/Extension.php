<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Faker;

use Faker\Factory;
use Faker\Generator;

class Extension extends Factory
{
    /** @var string[] */
    protected static array $extensionProviders = ['Commodity'];

    /**
     * @param  string  $locale
     */
    public static function extend(Generator $generator, $locale = self::DEFAULT_LOCALE): Generator
    {
        foreach (static::$extensionProviders as $provider) {
            $providerClassName = self::getProviderClassname($provider, $locale);
            $generator->addProvider(new $providerClassName($generator));
        }

        return $generator;
    }

    /**
     * @param  string  $provider
     * @param  string  $locale
     */
    protected static function getProviderClassname($provider, $locale = ''): string
    {
        if ($providerClass = self::findProviderClassname($provider, $locale)) {
            return $providerClass;
        }
        // fallback to default locale
        if ($providerClass = self::findProviderClassname($provider, static::DEFAULT_LOCALE)) {
            return $providerClass;
        }
        // fallback to no locale
        if ($providerClass = self::findProviderClassname($provider)) {
            return $providerClass;
        }

        throw new \InvalidArgumentException(sprintf('Unable to find provider "%s" with locale "%s"', $provider, $locale));
    }

    /**
     * @param  string  $provider
     * @param  string  $locale
     */
    protected static function findProviderClassname($provider, $locale = ''): ?string
    {
        $providerClass = 'TTBooking\\FiscalRegistrar\\Faker\\'
            .($locale ? sprintf('Provider\%s\%s', $locale, $provider) : sprintf('Provider\%s', $provider));

        if (class_exists($providerClass, true)) {
            return $providerClass;
        }

        return null;
    }
}
