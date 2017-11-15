Feature: Session api
  Scenario: I can get session info
    Given the database is purged
    And there is a course with the uuid "30575fe6-0bb6-4dfc-a38a-899e39bdf911" and the title "First course"
    And there is a session with the uuid "1231-123-123" and the title "Session title" for this course
    And there is following users
      | uuid       | firstName | lastName | phoneNumber    | locale  |
      | "123-user" | "jean"    | "paul"   | "+33123213123" | "en"    |
    And the api token for this user is "api-token-user"
    And this user is assigned to this course
    And I add "Authorization" header equal to "Bearer api-token-user"
    And I add "Content-Type" header equal to "application/json"
    When I send a POST request to "/api/graphql/" with body:
      """
      {
          "query": "query test($uuid: String!) { session(uuid: $uuid) { title } }",
          "variables":{"uuid":"not-found"}
      }
      """
    Then the response status code should be 200
    And the JSON should be equal to:
    """
      {
          "data": {
              "session": null
          },
          "errors": [
              {
                  "message": "Session not found",
                  "locations": [
                      {
                          "line": 1,
                          "column": 30
                      }
                  ],
                  "path": [
                      "session"
                  ]
              }
          ]
      }
    """
    And I add "Authorization" header equal to "Bearer api-token-user"
    And I add "Content-Type" header equal to "application/json"
    When I send a POST request to "/api/graphql/" with body:
      """
      {"query": "query session($uuid: String!) { session (uuid: $uuid) {title}}","variables":{"uuid":"1231-123-123"}}
      """
    Then the response status code should be 200
    And the JSON should be equal to:
    """
      {
          "data": {
              "session": {
                  "title": "Session title"
              }
          }
      }
    """
