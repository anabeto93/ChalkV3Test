Feature: Assign Users to Course
  Scenario: I can assign users to a course
    Given the database is purged
    And there is a course with the following info
      | teacherName | Adrien                        |
      | university  | University of Chalkboard      |
      | title       | Implementation of course list |
    And there is following users
      | uuid      | firstName | lastName | phoneNumber | locale | multiLogin |
      | 1234-uuid | John      | Cenna    | +000000000  | en     | 0      |
    And I go to "/admin"
    When I go to "/admin/course"
    Then I should see "admin.course.column.students"
    And I go to "/admin/course/1/student"
    Then I should see "admin.course.assign_users.title"
    And I should see "Cenna John (+000000000)"
    And I check "assign_user_users_0"
    And I press "assign_user_submit"
    Then I should be on "/admin/course"
    And I should see "flash.admin.course.assign_user.success"

