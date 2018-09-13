<?php

namespace Tests;

use Dypa\DeclareStrictTypes\Strategy\Remove;

class RemoveTest extends TestCase
{
    private $strategy;

    public function setUp()
    {
        $this->strategy = new Remove();
    }

    /**
     * @covers Dypa\DeclareStrictTypes\Strategy\Remove::__invoke
     */
    public function testNonPhp()
    {
        $this->assertEquals(
            'some text',
            ($this->strategy)('some text')
        );
    }

    /**
     * @covers Dypa\DeclareStrictTypes\Strategy\Remove::__invoke
     */
    public function testEmptyPhp()
    {
        $this->assertEquals(
            "<?php\n",
            ($this->strategy)("<?php\ndeclare(strict_types=1);\n\n")
        );
    }

    /**
     * @covers Dypa\DeclareStrictTypes\Strategy\Remove::__invoke
     */
    public function testClass()
    {
        $this->assertEquals(
            "<?php\nnamespace Foo;\nclass Foo{}\n",
            ($this->strategy)("<?php\ndeclare(strict_types=1);\nnamespace Foo;\nclass Foo{}\n")
        );
    }

    /**
     * @covers Dypa\DeclareStrictTypes\Strategy\Remove::__invoke
     */
    public function testPhpPhp()
    {
        $this->assertEquals(
            "<?php\necho 1;\n?>\n2\n<?php\necho 3;\n",
            ($this->strategy)("<?php\ndeclare(strict_types=1);\necho 1;\n?>\n2\n<?php\necho 3;\n")
        );
    }

    /**
     * @covers Dypa\DeclareStrictTypes\Strategy\Remove::__invoke
     */
    public function testPhpDoc()
    {
        $this->assertEquals(
            "<?php\n/**\n*/",
            ($this->strategy)("<?php\ndeclare(strict_types=1);\n/**\n*/")
        );
    }
}
