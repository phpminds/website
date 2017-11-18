Feature: Managing events
  In order run the user group
  As an organiser
  I need to be able to organise events

  @javascript
  Scenario: Sync event with meetup
    Given I am an organiser
    And There is a new meetup event
    Then I can sync the meetup event with an existing speaker

  @javascript
  Scenario: Sync event with meetup and new speaker
    Given I am an organiser
    And There is a new meetup event
    Then I can sync the meetup event with a new existing speaker
