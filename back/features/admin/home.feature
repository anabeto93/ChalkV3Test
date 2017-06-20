Feature: Home admin
  Scenario: I can go to the home of the admin
    When I go to "/admin/"
    Then I should see "Welcome"
