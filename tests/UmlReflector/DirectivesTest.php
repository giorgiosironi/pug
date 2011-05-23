<?php
namespace UmlReflector;

class DirectivesTest extends \PHPUnit_Framework_TestCase
{
    public function testAcceptsIsolatedClassesAsDirectives()
    {
        $directives = new Directives();
        $directives->addClass("User");
        $this->assertDirectivesEqualTo("[User]", $directives);
    }

    public function testAcceptsCompositionsAsDirectives()
    {
        $directives = new Directives();
        $directives->addComposition("User", "UserCollaborator");
        $this->assertDirectivesEqualTo("[User]->[UserCollaborator]", $directives);
    }

    public function testAcceptsInheritanceAsADirective()
    {
        $directives = new Directives();
        $directives->addInheritance("Animal", "Dog");
        $this->assertDirectivesEqualTo("[Animal]^-[Dog]", $directives);
    }

    public function testConsidersClassesIdempotentWhenAlreadyPresentInRelationships()
    {
        $directives = new Directives();
        $directives->addComposition("User", "UserCollaborator");
        $directives->addInheritance("Animal", "Dog");
        $directives->addClass("User");
        $directives->addClass("Animal");
        $this->assertDirectivesEqualTo("[User]->[UserCollaborator]\n[Animal]^-[Dog]", $directives);
    }

    public function testAcceptsInterfaceImplementationsAsDirectives()
    {
        $directives = new Directives();
        $directives->addInterface("Windows", "Wreck");
        $this->assertDirectivesEqualTo("[Windows]-.-^[Wreck]", $directives);
    }


    private function assertDirectivesEqualTo($content, Directives $directives)
    {
        $this->assertEquals($content, $directives->toString());
    }
}
