Feature: Session delete
  Scenario: I can delete a session
    Given the database is purged
    And there is a course with the uuid "123-course" and the title "First course of the year"
    And there is a session with the uuid "123-folder" and the title "Beginning" for this course
    And I go to "/admin"
    When I go to "/admin/course/1/session"
    Then I should see "Beginning"
    When I press "admin.session.action.delete"
    Then I should see "flash.admin.session.delete.success"
    And I should not see "Beginning"
