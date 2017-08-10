<?php
/*
 * This file is part of the PHPTools package.
 *
 * (c) Jerry Anselmi <jerry.anselmi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PHPTools\Helpers\Number\NumberDecimalCommaInterface;
use PHPTools\Helpers\Number\NumberDecimalPointInterface;

class NumberTest extends \PHPUnit_Framework_TestCase
{
    public function testNewClass()
    {
        $class = 'PHPTools\Helpers\Number\Number';
        foreach ([10, 10.5, '10', '10.5'] as $value) {
            $object = new $class($value);
            $this->assertInstanceOf($class, $object);
        }
    }

    public function testNewClassWithLocale()
    {
        $reflOne = new ReflectionClass(NumberDecimalCommaInterface::class);
        $reflTwo = new ReflectionClass(NumberDecimalPointInterface::class);
        $locales = array_merge($reflOne->getConstants(), $reflTwo->getConstants());
        $class = 'PHPTools\Helpers\Number\Number';
        foreach ($locales as $locale) {
            foreach ([10, 10.5, '10', '10.5'] as $value) {
                $object = new $class($value, $locale);
                $this->assertInstanceOf($class, $object);
            }
        }
    }

    public function testReturnedValues()
    {
        $number = new \PHPTools\Helpers\Number\Number('10');
        $this->assertEquals("10.00", $number->format());
        $this->assertEquals(10, $number->integer());
        $this->assertEquals(10.0, $number->float());
        $this->assertInternalType('int', $number->integer());
        $this->assertInternalType('float', $number->float());
        $this->assertInternalType('string', $number->format());
        $this->assertInternalType('string', strval($number));

        $number = new \PHPTools\Helpers\Number\Number(10000);
        $this->assertEquals("10,000.00", strval($number));
        $this->assertEquals("10,000", $number->format(0));
        $this->assertEquals(10000, $number->integer());
        $this->assertEquals(10000.0, $number->float());
        $this->assertInternalType('int', $number->integer());
        $this->assertInternalType('float', $number->float());
        $this->assertInternalType('string', $number->format());
        $this->assertInternalType('string', strval($number));
    }

    public function testSum()
    {
        $number = new \PHPTools\Helpers\Number\Number(10000);
        $number->sum(1000);
        $this->assertEquals(11000, $number->integer());
        $number->sum(500,50,20.0);
        $this->assertEquals(11570, $number->integer());
        $number->sum(-20);
        $this->assertEquals(11550, $number->integer());
        $number->sum(1,1,0.5,0.25,0.05,0.1,0.1,2.0);
        $this->assertEquals(11555, $number->integer());
        $number->sum(10005.5);
        $this->assertEquals(21560, $number->integer());
        $this->assertEquals(21560.5, $number->float());
        $this->assertEquals("21,560.50", $number->format());
        $this->assertEquals("21560.50", $number->format(2, false));
        $this->assertEquals("21,560.50", strval($number));
        $this->assertEquals("21.560,50", $number->convert(NumberDecimalCommaInterface::LOCALE_ES));
        $this->assertInternalType('int', $number->integer());
        $this->assertInternalType('float', $number->float());
        $this->assertInternalType('string', $number->format());
        $this->assertInternalType('string', strval($number));
        $this->assertInternalType('string', $number->convert(NumberDecimalCommaInterface::LOCALE_ES));
    }

    public function testSubtract()
    {
        $number = new \PHPTools\Helpers\Number\Number(10000);
        $number->subtract(1000);
        $this->assertEquals(9000, $number->integer());
        $number->subtract(500,50,20.0);
        $this->assertEquals(8430, $number->integer());
        $number->subtract(-20);
        $this->assertEquals(8450, $number->integer());
        $number->subtract(1,1,0.5,0.25,0.05,0.1,0.1,2.0);
        $this->assertEquals(8445, $number->integer());
        $number->subtract(10005.5);
        $this->assertEquals(-1560, $number->integer());
        $this->assertEquals(-1560.5, $number->float());
        $this->assertEquals("-1,560.50", $number->format());
        $this->assertEquals("-1560.50", $number->format(2, false));
        $this->assertEquals("-1,560.50", strval($number));
        $this->assertEquals("-1.560,50", $number->convert(NumberDecimalCommaInterface::LOCALE_ES));
        $this->assertInternalType('int', $number->integer());
        $this->assertInternalType('float', $number->float());
        $this->assertInternalType('string', $number->format());
        $this->assertInternalType('string', strval($number));
        $this->assertInternalType('string', $number->convert(NumberDecimalCommaInterface::LOCALE_ES));
    }

    public function testMultiply()
    {
        $number = new \PHPTools\Helpers\Number\Number(10000);
        $number->multiply(1000);
        $this->assertEquals(10000000, $number->integer());
        $number->multiply(1,0.5);
        $this->assertEquals(5000000, $number->integer());
        $number->multiply(-20);
        $this->assertEquals(-100000000, $number->integer());
        $number->multiply(1,-0.05,-0.1);
        $this->assertEquals(-500000, $number->integer());
        $number->multiply(25);
        $this->assertEquals(-12500000, $number->integer());
        $this->assertEquals(-12500000.0, $number->float());
        $this->assertEquals("-12,500,000.00", $number->format());
        $this->assertEquals("-12500000.00", $number->format(2, false));
        $this->assertEquals("-12,500,000.00", strval($number));
        $this->assertEquals("-12.500.000,00", $number->convert(NumberDecimalCommaInterface::LOCALE_ES));
        $this->assertInternalType('int', $number->integer());
        $this->assertInternalType('float', $number->float());
        $this->assertInternalType('string', $number->format());
        $this->assertInternalType('string', strval($number));
        $this->assertInternalType('string', $number->convert(NumberDecimalCommaInterface::LOCALE_ES));
    }


    public function testDivide()
    {
        $number = new \PHPTools\Helpers\Number\Number(10000);
        $number->divide(1000);
        $this->assertEquals(10, $number->integer());
        $number->divide(1,0.5);
        $this->assertEquals(20, $number->integer());
        $number->divide(-20);
        $this->assertEquals(-1, $number->integer());
        $number->divide(1,-0.05,-0.1);
        $this->assertEquals(-200, $number->integer());
        $number->divide(0.0025);
        $this->assertEquals(-80000, $number->integer());
        $this->assertEquals(-80000.0, $number->float());
        $this->assertEquals("-80,000.00", $number->format());
        $this->assertEquals("-80000.00", $number->format(2, false));
        $this->assertEquals("-80,000.00", strval($number));
        $this->assertEquals("-80.000,00", $number->convert(NumberDecimalCommaInterface::LOCALE_ES));
        $this->assertInternalType('int', $number->integer());
        $this->assertInternalType('float', $number->float());
        $this->assertInternalType('string', $number->format());
        $this->assertInternalType('string', strval($number));
        $this->assertInternalType('string', $number->convert(NumberDecimalCommaInterface::LOCALE_ES));
    }

    public function testMixed()
    {
        $number = new \PHPTools\Helpers\Number\Number(10000);
        $number->sum(123,45677)->subtract(77)->multiply(2)->divide(2);
        $this->assertEquals("55,723.00", $number->format());
        $this->assertEquals(55723, $number->integer());
        $this->assertEquals(55723.0, $number->float());
        $this->assertEquals("55,723.00", strval($number));
        $this->assertEquals("55.723,00", $number->convert(NumberDecimalCommaInterface::LOCALE_ES));
        $this->assertEquals(2.1999999999981, $number->modulo(21.3));
        $this->assertEquals(10, $number->modulo(21));
        $this->assertEquals(8, $number->modulo(11));
        $this->assertEquals(1, $number->modulo(3));
        $this->assertEquals(5723, $number->modulo(50000));
        $this->assertEquals(23, $number->modulo(-50));
        $this->assertInternalType('int', $number->integer());
        $this->assertInternalType('float', $number->float());
        $this->assertInternalType('string', $number->format());
        $this->assertInternalType('string', strval($number));
        $this->assertInternalType('string', $number->convert(NumberDecimalCommaInterface::LOCALE_ES));
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongRawValueBool()
    {
        new PHPTools\Helpers\Number\Number(true);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongRawValueArray()
    {
        new PHPTools\Helpers\Number\Number([]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongRawValueObject()
    {
        new PHPTools\Helpers\Number\Number(new stdClass);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongRawValueNull()
    {
        new PHPTools\Helpers\Number\Number(null);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongRawValueResource()
    {
        new PHPTools\Helpers\Number\Number(curl_init());
    }
}
