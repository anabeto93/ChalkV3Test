Feature: Session create
  Scenario: I can create a session
    Given the database is purged
    And there is a course with the uuid "123-course" and the title "First course of the year"
    And there is a folder with the uuid "321-folder" and the title "Geographie" for this course
    And I go to "/admin"
    When I go to "/admin/course"
    Then I should see "admin.course.column.sessions"
    And I should see "First course of the year"
    And I should see "1"
    When I follow "admin.course.viewSessions"
    Then I should be on "/admin/course/1/session"
    And I should see "admin.session.no-result"
    And I should see "admin.session.create.link"
    When I follow "admin.session.create.link"
    Then I should be on "/admin/course/1/session/create"
    And I fill in the following:
      | title          | Decouverte de la topologie du Ghana |
      | rank           | 12                                  |
      | needValidation | 1                                   |
      | folder         | 0                                   |
    And I attach the file "dummy-archive.zip" to "content"
    Then I press "form.session_create.children.submit.label"
    And I should be on "/admin/course/1/session"
    And I should see "Decouverte de la topologie du Ghana"
    And I should see "Geographie"
    Then I follow "admin.session.action.preview"
    And I should see "This is a second paragraph:"

