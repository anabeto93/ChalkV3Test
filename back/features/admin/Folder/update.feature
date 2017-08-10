@folder
Feature: Folder update
  Scenario: I can update a folder
    Given the database is purged
    And there is a course with the uuid "123-course" and the title "First course of the year"
    And there is a folder with the uuid "123-folder" and the title "Beginning" for this course
    And I go to "/admin"
    When I go to "/admin/course/1/folder"
    Then I should see "Beginning"
    When I follow "admin.folder.action.update"
    And I should be on "/admin/course/1/folder/update/1"
    And I fill in the following:
      | title | Other title |
    When I press "form.folder_update.children.submit.label"
    Then I should be on "/admin/course/1/folder"
    And I should see "Other title"
    And I should not see "Beginning"
