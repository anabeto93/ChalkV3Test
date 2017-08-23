@folder
Feature: Folder delete
  Scenario: I can delete a folder
    Given the database is purged
    And there is a course with the uuid "123-course" and the title "First course of the year"
    And there is a folder with the uuid "123-folder" and the title "Beginning" for this course
    And I go to "/admin"
    When I go to "/admin/course/1/folder"
    Then I should see "Beginning"
    When I press "admin.folder.action.delete"
    Then I should be on "/admin/course/1/folder"
    And I should see "flash.admin.folder.delete.success"
    And I should not see "Beginning"
