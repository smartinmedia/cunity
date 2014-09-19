@module @login @nojs
Feature: Login
  In order to use cunity
  As a usual user
  I need to be loggedin

  Scenario: Login with wrong data
    When I am on "/"
      And I fill in "email" with "asdf@asdf.com"
      And I fill in "password" with "asdfasdf"
      And I press "Log in"
    Then I should see "Sorry"
      And I should see "The entered data is not correct!"
      And I should see "I forgot my password"

  Scenario: Login with correct data
    When I am oliver
    Then I should see "Oliver Monneke"
      And I should see "( oliver )"
      And I should see "Edit profile"