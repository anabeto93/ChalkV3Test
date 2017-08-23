@folder
Feature: Folder creation
  Scenario: I can create a folder in a course
    Given the database is purged
    And there is a course with the uuid "123-course" and the title "First course of the year"
    And there is a folder with the uuid "123-folder" and the title "Beginning" for this course
    And I go to "/admin"
    When I go to "/admin/course/1/folder"
    And I should see "admin.folder.column.title"
    And I should see "Beginning"
    When I follow "admin.folder.create.link"
    Then I should be on "/admin/course/1/folder/create"
    And I should see "admin.folder.create.title"
    And I fill in the following:
      | title | Second part of the course |
    When I press "form.folder_create.children.submit.label"
    Then I should see "flash.admin.folder.create.success"
    And I should be on "/admin/course/1/folder"
    And I should see "Second part of the course"
