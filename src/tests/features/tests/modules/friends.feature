@module @friends @js
Feature: Friends
  In order to interact with friends
  As a usual user
  I need to have friends

  @javascript
  Scenario: See friends
    Given I am oliver
    When I follow "Friends"
    Then I should see "Julian Seibert"

  @javascript
  Scenario: View friend's profile
    Given I am oliver
    When I follow "Friends"
      And I follow "Julian Seibert"
    Then I should see "Julian Seibert"
      And I should see "( julian )"
