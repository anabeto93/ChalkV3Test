Feature: Session progression
  Scenario: I can see the progression of a session
    Given the database is purged
    And there is a course with the uuid "123-course" and the title "First course of the year"
    And there is a session with the uuid "123-folder" and the title "Beginning" for this course
    And there is following users
      | uuid     | firstName | lastName | phoneNumber  | locale | multiLogin |
      | 123-user | jean      | paul     | +33123213123 | en     | 0          |
    And this user is assigned to this course
    And I go to "/admin"
    When I go to "/admin/course"
    Then I should see "admin.course.column.sessions"
    And I should see "First course of the year"
    And I should see "Sessions"
    When I follow "admin.course.viewSessions"
    Then I should be on "/admin/course/1/session"
    And I should see "admin.session.title"
    And I should see "Beginning"
    And I should see "0/1"
    When I follow "admin.session.viewProgression"
    Then I should be on "/admin/course/1/session/1/progression"
    And I should see "paul"
    And I should see "jean"
    And I should see "+33123213123"
