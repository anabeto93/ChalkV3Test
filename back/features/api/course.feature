Feature: Course api
  Scenario: I can get course info
    Given the database is purged
    And there is a course with the uuid "30575fe6-0bb6-4dfc-a38a-899e39bdf911" and the title "First course"
    And I add "Content-Type" header equal to "application/json"
    And I send a POST request to "/api/graphql/" with body:
      """
      {"query": "query { course(uuid: \"not-found\") { title }}", "variables": null}
      """
    Then the response status code should be 200
    And the JSON should be equal to:
    """
      {
          "data": {
              "course": null
          },
          "errors": [
              {
                  "message": "Course not found",
                  "locations": [
                      {
                          "line": 1,
                          "column": 9
                      }
                  ],
                  "path": [
                      "course"
                  ]
              }
          ]
      }
    """
    And I add "Content-Type" header equal to "application/json"
    And I send a POST request to "/api/graphql/" with body:
      """
      {"query": "query { course(uuid: \"30575fe6-0bb6-4dfc-a38a-899e39bdf911\") { title }}", "variables": null}
      """
    Then the response status code should be 200
    And the JSON should be equal to:
    """
      {
          "data": {
              "course": {
                  "title": "First course"
              }
          }
      }
    """
