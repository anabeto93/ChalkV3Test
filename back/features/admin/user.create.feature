Feature: User creation
  Scenario: I can create a user
    Given the database is purged
    And there is a user called "jean" "paul" with the uuid "uuid-1" and the phone number "+123123123"
    And I go to "/admin"
    When I go to "/admin/user"
    Then I should see "admin.user.create.title"
    When I follow "admin.user.create.title"
    Then I should be on "/admin/user/create"
    And I should see "admin.user.create.title"
    And I fill in the following:
      | firstName   | Paul       |
      | lastName    | Elon       |
      | phoneNumber | +123123123 |
      | country     | GH         |
    When I press "form.user_create.children.submit.label"
    Then I should be on "/admin/user/create"
    And I should see "validator.phoneNumber.alreadyUsed"
    Then I fill in the following:
      | phoneNumber | +321321321 |
    When I press "form.user_create.children.submit.label"
    Then I should be on "/admin/user"
    And I should see "Paul"
    And I should see "Elon"
    And I should see "Ghana"
    And I should see "+321321321"
