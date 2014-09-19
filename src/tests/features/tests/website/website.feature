@website @anonymous @nojs
Feature: website
  In order to see the webste
  As an anonymous user
  I need to be able to see information

  Scenario: See startpage
    Given I am on "/"
    Then I should see "Welcome!"
      And I should see "This is the design for the new Cunity! :)"
      And I should see "Test-Login: tester@cunity.net with password: cunityisgreat"
      And I should see "I forgot my password"
      And I should see "Remember Me"
      And I should see "Register"

  Scenario: See legal notice
    Given I am on "/"
    When I follow "Legal-Notice"
    Then I should see "Imprint"
      And I should not see "Lorem ipsum"

  Scenario: See privacy policy
    Given I am on "/"
    When I follow "Privacy"
    Then I should see "Privacy Policy"
      And I should not see "Lorem ipsum"

  Scenario: See terms & conditions
    Given I am on "/"
    When I follow "Terms and Conditions"
    Then I should see "Terms & Conditions"
      And I should not see "Lorem ipsum"

  Scenario: See contacts
    Given I am on "/"
    When I follow "Contact"
    Then I should see "ContactForm"
      And I should not see "Lorem ipsum"
