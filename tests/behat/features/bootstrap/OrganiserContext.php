<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Pavlakis\Slim\Behat\Context\KernelAwareContext;
use Pavlakis\Slim\Behat\Context\App;

use Behat\Behat\Hook\Scope\BeforeFeatureScope;

/**
 * Defines application features from the specific context.
 */
class OrganiserContext extends MinkContext implements Context, KernelAwareContext
{
    use App;

    const TEMP_EMAIL = 'organiser1@phpminds.org';

    const TEMP_PASSWORD = 'organiser123';

    /**
     * @var \PHPMinds\Model\Auth $authentication
     */
    private $authentication;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @BeforeScenario
     */
    public function createOrganiser()
    {
        $this->authentication = $this->app->getContainer()->get('PHPMinds\Model\Auth');
    }

    /**
     * @AfterScenario
     */
    public function removeOrganiser()
    {
        $this->authentication = $this->app->getContainer()->get('PHPMinds\Model\Auth');
        $this->authentication->removeUser(self::TEMP_EMAIL);
    }

    /**
     * @Given I am an organiser
     */
    public function iAmAnOrganiser()
    {
        $this->authentication->registerUser(self::TEMP_EMAIL, self::TEMP_PASSWORD);
    }

    /**
     * @Then I can login to the organiser panel
     */
    public function iCanLoginToTheOrganiserPanel()
    {
        $this->visit('/login');
        $this->fillField('email', self::TEMP_EMAIL);
        $this->fillField('password', self::TEMP_PASSWORD);
        $this->pressButton('Login');

        $this->assertPageContainsText('Dashboard');
    }
}
