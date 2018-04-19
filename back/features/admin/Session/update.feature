Feature: Session update
  Scenario: I can update a session
    Given the database is purged
    And there is a course with the uuid "123-course" and the title "First course of the year"
    And there is a folder with the uuid "321-folder" and the title "Geographie" for this course
    And there is a session with the uuid "456-session" and the title "Topologie" for this course
    And I go to "/admin"
    When I go to "/admin/course"
    Then I should see "admin.course.column.sessions"
    And I should see "First course of the year"
    And I should see "Sessions"
    When I follow "admin.course.viewSessions"
    Then I should be on "/admin/course/1/session"
    And I should see "Topologie"
    And I should not see "Geographie"
    And I should see "admin.session.action.update"
    When I follow "admin.session.action.update"
    Then I should be on "/admin/course/1/session/1/update"
    And I fill in the following:
      | title          | Decouverte de la topologie du Ghana |
      | rank           | 12                                  |
      | needValidation | 1                                   |
      | folder         | 0                                   |
    And I attach the file "dummy-archive.zip" to "content"
    Then I press "form.session_update.children.submit.label"
    And I should be on "/admin/course/1/session"
    And I should see "Decouverte de la topologie du Ghana"
    And I should see "Geographie"
    Then I follow "admin.session.action.preview"
    And I should see "This is a second paragraph:"

