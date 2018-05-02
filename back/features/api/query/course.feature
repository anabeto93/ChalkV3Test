Feature: Course api
  Scenario: I can not get info without api token
    Given the database is purged
    And there is a course with the uuid "30575fe6-0bb6-4dfc-a38a-899e39bdf911" and the title "First course"
    And I add "Content-Type" header equal to "application/json"
    And I send a POST request to "/api/graphql/" with body:
      """
      {"query": "query { courses { title }}", "variables": null}
      """
    Then the response status code should be 401

  Scenario: I can get all info of a course without folder
    Given the database is purged
    And there is a course with the uuid "30575fe6-0bb6" and the title "First course"
    And there is a session with the uuid "998812-123123" and the title "First session" for this course
    And there is following users
      | uuid   | firstName | lastName | phoneNumber | locale | multiLogin |
      | uuid-1 | jean      | paul     | +123123123  | en     | 0          |
    And the api token for this user is "token1"
    And this user is assigned to this course
    And I add "Authorization" header equal to "Bearer token1"
    When I add "Content-Type" header equal to "application/json"
    And I send a POST request to "/api/graphql/" with body:
      """
      {"query": "query { courses { title, folders { uuid, sessions { uuid, title} }}}", "variables": null}
      """
    Then the response status code should be 200
    And the JSON should be equal to:
    """
      {
          "data": {
              "courses": [
                  {
                      "title": "First course",
                      "folders": [
                          {
                              "uuid": "default",
                              "sessions": [
                                  {
                                      "uuid": "998812-123123",
                                      "title": "First session"
                                  }
                              ]
                          }
                      ]
                  }
              ]
          }
      }
    """

  Scenario: I can get all info of a course with folder
    Given the database is purged
    And there is a course with the uuid "30575fe6-0bb6" and the title "First course"
    And there is a folder with the uuid "3456723-2313" and the title "Folder title" for this course
    And there is a session with the uuid "998812-123123" and the title "First session" for this course and folder
    And there is following users
      | uuid     | firstName | lastName | phoneNumber  | locale | multiLogin |
      | 123-user | jean      | paul     | +33123213123 | en     | 0          |
    And the api token for this user is "token1"
    And this user is assigned to this course
    And I add "Authorization" header equal to "Bearer token1"
    When I add "Content-Type" header equal to "application/json"
    And I send a POST request to "/api/graphql/" with body:
      """
      {"query": "query { courses { title, folders { uuid, title, sessions { uuid, title} }}}", "variables": null}
      """
    Then the response status code should be 200
    And the JSON should be equal to:
    """
      {
          "data": {
              "courses": [
                  {
                      "title": "First course",
                      "folders": [
                          {
                              "uuid": "3456723-2313",
                              "title": "Folder title",
                              "sessions": [
                                  {
                                      "uuid": "998812-123123",
                                      "title": "First session"
                                  }
                              ]
                          }
                      ]
                  }
              ]
          }
      }
    """
