@module @memberlist @js
Feature: Interact with other members
  In order to interact with other members
  As a cunity user
  I need to be able to interact with them

  Scenario: View member list
    Given I am oliver
    When I follow "Memberlist"
    Then I should see "Memberlist"

  Scenario: See friend's iamges

  Scenario: Select friend

  Scenario: Send message

  Scenario: Block person

  Scenario: Remove pending request