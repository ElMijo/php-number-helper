<?php
/*
 * This file is part of the PHPTools package.
 *
 * (c) Jerry Anselmi <jerry.anselmi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPTools\Helpers\Number;

/**
 * This class allows you to work with numbers and their format in a transparent way.
 *
 * @author Jerry Anselmi <jerry.anselmi@gmail.com>
 */
class Number
{
    const DEFAULT_LOCALE = 'en';

    /**
     * The __construct
     * @param string|int|float $raw the numeric value to process
     * @param string $locale The original locale of the $raw value.
     */
    function __construct($raw, $locale = self::DEFAULT_LOCALE)
    {
        if (!in_array(gettype($raw), ['string', 'integer', 'double'])) {
            throw new \InvalidArgumentException(sprintf(
                "Raw value type [%s] not supported",
                gettype($raw)
            ));
        }
        $this->raw = strval($raw);
        $this
            ->loadData()
            ->setLocale($locale)
        ;
    }

    /**
     * Set locale of the value.
     * @param $this
     */
    public function setLocale($locale)
    {
        $this->locale = $this->isValidLocale($locale)
            ?$locale
            :static::DEFAULT_LOCALE
        ;

        list(
            $this->thousands,
            $this->decimal
        ) = $this->getFormatByLocale($locale);

        $this->value = $this->parseFloat($this->raw);
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->format($decimal = 2);
    }

    /**
     * Get the float value.
     * @return integer
     */
    public function float()
    {
        return $this->value;
    }

    /**
     * Get the integer value.
     * @return integer
     */
    public function integer()
    {
        return intval($this->value);
    }

    /**
     * Apply format to  the numeric value.
     * @param  integer $decimal
     * @param  boolean $withThousands
     * @return string
     */
    public function format($decimal = 2, $withThousands = true)
    {
        $thousands = $withThousands ? $this->thousands : '';
        return number_format($this->value, $decimal, $this->decimal, $thousands);
    }

    /**
     * Convert format by locale.
     * @param  string  $locale
     * @param  integer $decimals
     * @param  boolean $withThousands
     * @return string
     */
    public function convert($locale, $decimals = 2, $withThousands = true)
    {
        $decimal = $this->decimal;
        $thousands = $this->thousands;
        if ($this->isValidLocale($locale)) {
            list($thousands, $decimal) = $this->getFormatByLocale($locale);
        }

        return number_format(
            $this->value,
            $decimals,
            $decimal,
            $withThousands ? $thousands : ''
        );
    }

    /**
     * Add each of the arguments that are passed to the method to the numerical value.
     *
     * The method does the operation sequentially according to the order of the last arguments
     *
     * @return $this
     */
    public function sum()
    {
        $this->operation(func_get_args(), '+');
        return $this;
    }

    /**
     * It subtracts each of the arguments that are passed to the method to the numerical value.
     *
     * The method does the operation sequentially according to the order of the last arguments
     *
     * @return $this
     */
    public function subtract()
    {
        $this->operation(func_get_args(), '-');
        return $this;
    }

    /**
     * Multiply the numeric value by each of the arguments that are passed to the method
     *
     * The method does the operation sequentially according to the order of the last arguments
     *
     * @return $this
     */
    public function multiply()
    {
        $this->operation(func_get_args(), '*');
        return $this;
    }

    /**
     * Divide the numeric value between the arguments that are passed to the method
     *
     * The method does the operation sequentially according to the order of the last arguments
     *
     * @return $this
     */
    public function divide()
    {
        $this->operation(func_get_args(), '/');
        return $this;
    }

    /**
     * Computes the module by divisor
     * @param  string|integer|float $divider
     * @return float
     */
    public function modulo($divider)
    {
        return fmod($this->value, $divider);
    }

    /**
     * Allows to obtain the format by locale
     * @param  string $locale
     * @return string
     */
    private function getFormatByLocale($locale)
    {
        return $this->isDecimalPoint($locale) ? [',','.'] : ['.',','];
    }

    /**
     * Valid if locale y supported
     * @param  string  $locale
     * @return boolean
     */
    private function isValidLocale($locale)
    {
        return in_array($locale, array_merge(
            $this->localePoint,
            $this->localeComma
        ));
    }

    /**
     * Allows you to perform the basic mathematical operations
     * @param  array  $args
     * @param  string $operation
     * @return $this
     */
    private function operation($args, $operation)
    {
        foreach ($args as $arg) {
            switch ($operation) {
                case '+':$this->value+= $this->arg($arg)->float();break;
                case '-':$this->value-= $this->arg($arg)->float();break;
                case '*':$this->value*= $this->arg($arg)->float();break;
                case '/':$this->value/= $this->arg($arg)->float();break;
            }
        }
        return $this;
    }

    /**
     * Load locale data.
     * @return $this
     */
    private function loadData()
    {
        $this->localePoint = $this->loadFileData(
            'locale_decimal_point'
        );
        $this->localeComma = $this->loadFileData(
            'locale_decimal_comma'
        );
        return $this;
    }

    /**
     * Load locale data from file
     * @param  string $filename
     * @return array|null
     */
    private function loadFileData($filename)
    {
        return file(
            realpath(__DIR__."/data/$filename.txt"),
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );
    }

    /**
     * Ensures that the locale use decimal point
     * @param  string $locale
     * @return boolean
     */
    private function isDecimalPoint($locale)
    {
        return in_array($locale, $this->localePoint);
    }

    /**
     * parse string value to float.
     * @param  string $value
     * @return float
     */
    private function parseFloat($value)
    {
        return floatval(str_replace(
            $this->decimal,
            '.', str_replace($this->thousands, '', $value)
        ));
    }

    /**
     * Ensures that the argument is an Number object
     * @param  mixed $arg
     * @return PHPTools\NumberHelper\Number
     */
    private function arg($arg)
    {
        return is_a($arg, static::class) ? $arg : new Number($arg);
    }
}
