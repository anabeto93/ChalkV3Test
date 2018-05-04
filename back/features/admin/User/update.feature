Feature: User update
  Scenario: I can update a User
    Given the database is purged
    And there is following users
      | uuid   | firstName | lastName | phoneNumber | locale | multiLogin |
      | uuid-1 | jean      | paul     | +123123123  | en     | 0          |
    And I go to "/admin"
    When I go to "/admin/user"
    Then I should see "admin.user.list.title"
    And I should see "jean"
    And I should see "paul"
    And I should see "+123123123"
    When I follow "admin.user.action.update"
    Then I should be on "/admin/user/1/update"
    And I should see "admin.user.update.title"
    And I fill in the following:
      | firstName   | Truc         |
      | lastName    | Muche        |
      | phoneNumber | +33789789789 |
      | country     | FR           |
      | locale      | fr           |
      | multiLogin  | 1            |
    When I press "form.user_update.children.submit.label"
    Then I should be on "/admin/user"
    And I should see "Truc"
    And I should see "Muche"
    And I should see "France"
    And I should see "+33789789789"
    And I should see "fr"
    And I should see "✔"
    And I should not see "jean"
    And I should not see "paul"
    And I should not see "+123123123"
