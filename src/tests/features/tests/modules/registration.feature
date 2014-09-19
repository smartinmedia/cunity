@module @registration @js
Feature: Registration
  In order to use cunity
  As a usual user
  I need to register

  @javascript
  Scenario: Register as a web user with no fields filled in
    Given I am on "/"
    When I press "Register"
    Then I should see "This field cannot be blank!"
    And I should not see "Email-Address already in use"
    And I should not see " is too short"
    And I should not see "This is not a valid email-address"
    And I should not see "The password and its confirm are not the same"
    And I should not see "The password is too short"
    And I should not see "That is not a valid date"

  @javascript
  Scenario: Register as a web user with wrong data filled in
    Given I am on "/"
    When I fill in "input-username" with testuserWithSpecialChars
      And I fill in "input-email" with "test"
      And I fill in "input-firstname" with "t1"
      And I fill in "input-lastname" with "t2"
      And I fill in "input-password" with "t3"
      And I fill in "input-password-repeat" with "t4"
      And I select "m" from "sex"
      And I fill in "birthday" with "w"
      And I press "Register"
    Then I should not see "This field cannot be blank!"
      And I should not see "Email-Address already in use"
      And I should see "This is not a valid email-address"
      And I should see "Your firstname is too short (min. 3 chars)"
      And I should see "Your lastname is too short (min. 3 chars)"
      And I should see "The password and its confirm are not the same"
      And I should see "That is not a valid date"

#    @javascript
#    Scenario: Register as a web user
#      Given I am on "/"
#      When I fill in "input-username" with testuserWithSpecialChars
#        And I fill in "input-email" with testuserEmail
#        And I fill in "input-firstname" with "Oliver"
#        And I fill in "input-lastname" with "Monneke"
#        And I fill in "input-password" with "test.123"
#        And I fill in "input-password-repeat" with "test.123"
#        And I select "m" from "sex"
#        And I fill in "birthday" with ""
#        And I press "Register"
#      Then I should not see "This field cannot be blank!"
#        And I should not see "Email-Address already in use"
#        And I should not see "This is not a valid email-address"
#        And I should not see "Your firstname is too short (min. 3 chars)"
#        And I should not see "Your lastname is too short (min. 3 chars)"
#        And I should not see "The password and its confirm are not the same"
#        And I should not see "The password is too short (min. 6 chars)"
#        And I should not see "That is not a valid date"
