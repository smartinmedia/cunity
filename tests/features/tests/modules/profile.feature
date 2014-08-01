@module @profile @js
Feature: My profile
  In order edit my profile
  As a usual user
  I need to be loggedin

  Scenario: See profile edit button
    Given I am oliver
    Then I should see "Edit profile"

  @javascript
  Scenario: Change gender
    Given I am oliver
    When I follow " Edit profile"
      And I select "f" from "sex"
      And I press "Save"
    Then I should see "success"