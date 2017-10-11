<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Pavlakis\Slim\Behat\Context\KernelAwareContext;
use Pavlakis\Slim\Behat\Context\App;
use Psr\Container\ContainerInterface;

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
     * @var ContainerInterface
     */
    private $diContainer;

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
        $this->diContainer = $this->app->getContainer();
        $this->authentication = $this->diContainer->get('PHPMinds\Model\Auth');
        $this->authentication->registerUser(self::TEMP_EMAIL, self::TEMP_PASSWORD);
    }

    /**
     * @AfterScenario
     */
    public function removeOrganiser()
    {
        $this->authentication = $this->diContainer->get('PHPMinds\Model\Auth');
        $this->authentication->removeUser(self::TEMP_EMAIL);
    }

    /**
     * @Given I am an organiser
     */
    public function iAmAnOrganiser()
    {
        $this->authentication->isValid(self::TEMP_EMAIL, self::TEMP_PASSWORD);
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

    /**
     * @Given There is a new meetup event
     */
    public function thereIsANewMeetupEvent()
    {
        throw new PendingException();
        // go to /admin
        // load the last event in: "section table tr"
        // visit that url
    }

    /**
     * @Then I can sync the meetup event with an existing speaker
     */
    public function iCanSyncTheMeetupEventWithAnExistingSpeaker()
    {
        // fill the form
        // select defaults for dropdowns
        // submit form
        // check page for created test
        // check DB for created talk
        throw new PendingException();
    }

    /**
     * @Then I can sync the meetup event with a new existing speaker
     */
    public function iCanSyncTheMeetupEventWithANewExistingSpeaker()
    {
        // fill the form
        // create speaker
            // fill details
            // submit form
        // submit form
        // check DB for created talk
            // referencing new speaker
        // check DB for new speaker
        throw new PendingException();
    }
}
