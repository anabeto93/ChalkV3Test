Feature: Session
  Scenario: I can go to the list of the sessions
    Given the database is purged
    And there is a course with the uuid "123-course" and the title "First course of the year"
    And there is a session with the uuid "123-folder" and the title "Beginning" for this course
    And I go to "/admin"
    When I go to "/admin/course"
    Then I should see "admin.course.column.sessions"
    And I should see "First course of the year"
    And I should see "Sessions"
    When I follow "Sessions"
    Then I should be on "/admin/course/1/session"
    And I should see "admin.session.title"
    And I should see "Beginning"
