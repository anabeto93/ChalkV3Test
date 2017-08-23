Feature: Course
  Scenario: I can update a course
    Given the database is purged
    And there is a course with the following info
      | teacherName | Adrien                        |
      | university  | University of Chalkboard      |
      | title       | Implementation of course list |
    And I go to "/admin"
    When I go to "/admin/course"
    Then I should see "admin.course.title"
    And I should see "Implementation of course list"
    And I should see "Adrien"
    And I should see "University of Chalkboard"
    And I should see "admin.course.action.update"
    Then I follow "admin.course.action.update"
    And I should be on "/admin/course/update/1"
    And I fill in the following:
      | title       | Computer Science    |
      | description | Initiation          |
      | teacherName | Test Teacher        |
      | university  | University of tests |
      | enabled     | 1                   |
    When I press "form.course_update.children.submit.label"
    Then I should be on "/admin/course"
    And I should see "Computer Science"
    And I should see "Test Teacher"
    And I should see "University of tests"
    And I should see "✔︎"
    And I should not see "Implementation of course list"
    And I should not see "Adrien"
    And I should not see "University of Chalkboard"
