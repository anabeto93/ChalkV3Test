Feature: User
  Scenario: I can go to the list of the user
    Given the database is purged
    And there is following users
      | uuid   | firstName | lastName | phoneNumber | locale |
      | uuid-1 | jean      | paul     | +123123123  | en     |
    And I go to "/admin"
    When I go to "/admin/user"
    Then I should see "admin.user.list.title"
    And I should see "jean"
    And I should see "paul"
    And I should see "+123123123"
