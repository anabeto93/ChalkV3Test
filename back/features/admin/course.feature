Feature: Course
  Scenario: I can go to the list of the course
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
