Feature: Homepage sanity checklist
  In order to know a deployment has been successful
  As a web user
  I need to be able to check the homepage still works

  Scenario: The homepage is loading
    Given I am on the homepage
    Then the response status code should be 200

  Scenario: I can confirm I am on the homepage
    Given I am on the homepage
    Then I should see "meeting every 2nd Thursday of the month at 7pm in Nottingham"