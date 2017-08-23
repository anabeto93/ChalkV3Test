@folder
Feature: Folder
  Scenario: I can go to the list of the folders
    Given the database is purged
    And there is a course with the uuid "123-course" and the title "First course of the year"
    And there is a folder with the uuid "123-folder" and the title "Beginning" for this course
    And I go to "/admin"
    When I go to "/admin/course"
    Then I should see "admin.course.column.folders"
    And I should see "First course of the year"
    And I should see "1"
    When I follow "1"
    Then I should be on "/admin/course/1/folder"
    And I should see "admin.folder.title"
    And I should see "Beginning"

