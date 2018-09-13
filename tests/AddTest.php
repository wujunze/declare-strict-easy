<?php

namespace Tests;

use Dypa\DeclareStrictTypes\Strategy\Add;

class AddTest extends TestCase
{
    private $strategy;

    public function setUp()
    {
        $this->strategy = new Add();
    }

    /**
     * @covers Dypa\DeclareStrictTypes\Strategy\Add::__invoke
     */
    public function testNonPhp()
    {
        $this->assertEquals(
            'some text',
            ($this->strategy)('some text')
        );
    }

    /**
     * @covers Dypa\DeclareStrictTypes\Strategy\Add::__invoke
     */
    public function testEmptyPhp()
    {
        $this->assertEquals(
            "<?php\n\ndeclare(strict_types=1);\n",
            ($this->strategy)("<?php\n\n")
        );
    }

    /**
     * @covers Dypa\DeclareStrictTypes\Strategy\Add::__invoke
     */
    public function testClass()
    {
        $this->assertEquals(
            "<?php\ndeclare(strict_types=1);\nnamespace Foo;\nclass Foo{}\n",
            ($this->strategy)("<?php\nnamespace Foo;\nclass Foo{}\n")
        );
    }

    /**
     * @covers Dypa\DeclareStrictTypes\Strategy\Add::__invoke
     */
    public function testPhpPhp()
    {
        $this->assertEquals(
            "<?php\ndeclare(strict_types=1);\necho 1;\n?>\n2\n<?php\necho 3;\n",
            ($this->strategy)("<?php\necho 1;\n?>\n2\n<?php\necho 3;\n")
        );
    }

    /**
     * @covers Dypa\DeclareStrictTypes\Strategy\Add::__invoke
     */
    public function testPhpDoc()
    {
        $this->assertEquals(
            "<?php\ndeclare(strict_types=1);\n/**\n*/",
            ($this->strategy)("<?php\n/**\n*/")
        );
    }

    /**
     * @covers Dypa\DeclareStrictTypes\Strategy\Add::__invoke
     */
    public function testWhenDeclareDoNothing()
    {
        $this->assertEquals(
            "<?php\n\n\ndeclare(strict_types=1);\n",
            ($this->strategy)("<?php\n\n\ndeclare(strict_types=1);\n")
        );
    }

    /**
     * @covers Dypa\DeclareStrictTypes\Strategy\Add::__invoke
     */
    public function testWindowsRn()
    {
        $this->assertEquals(
            "<?php\r\ndeclare(strict_types=1);\n",
            ($this->strategy)("<?php\r\n")
        );
    }
}
