@module @conversation @js
Feature: Conversation with others
  In order communicate with others
  As a cunity user
  I need to be write messages

  Scenario: See Conversations
    Given I am oliver
    When I follow "Messages"
    Then I should see "Conversations"
      And I should see "Start Conversation"

  @javascript
  Scenario: Start a conversation
    Given I am oliver
    When I follow "Messages"
      And I follow "Start conversation"
    Then I should see "something"
