<?php
class DirectivesTest extends PHPUnit_Framework_TestCase
{
    public function testAcceptsIsolatedClassesAsDirectives()
    {
        $directives = new Directives();
        $directives->addClass("User");
        $this->assertDirectivesEqualTo("[User]", $directives);
    }

    private function assertDirectivesEqualTo($content, Directives $directives)
    {
        $this->assertEquals($content, $directives->toString());
    }
}
