@module @wall @js
Feature: My wall
  In order see and write on my wall
  As a usual user
  I need to be loggedin

  @javascript
  Scenario: Write on my wall
    Given I am on wall
    When I click id "postmsg"
      And I fill in "postmsg" with testStringWithSpecialCharacters
      And I press "newsfeed-post-button"
      And I wait
    Then I should see testStringWithSpecialCharacters

#    @javascript
#    Scenario: Post picture on my wall
#      Given I am oliver
#      When I follow "Wall"
#        And I click ""
#        And I attach the file "C:\xampp\htdocs\cunity\trunk\style\CunityRefreshed\screenshot.jpg" to ""

  @javascript
  Scenario: Post YouTube video on my wall
    Given I am on wall
    When I click id "postmsg"
      And I fill in "postmsg" with "https://www.youtube.com/watch?v=escFywxs5oA"
      And I press "newsfeed-post-button"
      And I wait
    Then I should see "TeamCity: Beyond Continuous Integration"

    @javascript
    Scenario: I remove old posts
      Given I am on wall
      When I remove wall entries
      Then I should see "There are no posts to show"