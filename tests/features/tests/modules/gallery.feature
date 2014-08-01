@module @gallery @js
Feature: Gallery
  In order view fotos in the gallery
  As a cunity user
  I need to upload fotos into a gallery

  @javascript
  Scenario: View galleries
    When I am oliver
      And I follow "gallery"
    Then I should see "New Album"
      And I should see "sdf"

  @javascript
  Scenario: View fotos in a gallery
    When I am oliver
      And I follow "gallery"
      And I follow "sdf"
    Then I should see "sdf"
      And I should see "fsdfdfdsfsd"
