Feature: Course creation
  Scenario: I can create a course
    Given the database is purged
    When I am on "/admin/course"
    Then I should see "admin.course.create.title"
    When I follow "admin.course.create.title"
    Then I should be on "/admin/course/create"
    And I should see "admin.course.create.title"
    And I fill in the following:
      | title       | Computer Science    |
      | description | Initiation          |
      | teacherName | Test Teacher        |
      | university  | University of tests |
      | enabled     | 1                   |
    And I press "form.course_create.children.submit.label"
    Then I should be on "/admin/course"
    And I should see "Computer Science"
    And I should see "Test Teacher"
    And I should see "University of tests"
    And I should see "✔︎"
