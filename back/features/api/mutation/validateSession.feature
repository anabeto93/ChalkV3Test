Feature: Validate session api
  Scenario: I can validate a user progression for a session
    Given the database is purged
    And there is a course with the uuid "30575fe6-0bb6-4dfc-a38a-899e39bdf911" and the title "First course"
    And there is a session with the uuid "1231-123-123" and the title "Session title" for this course
    And there is following users
      | uuid     | firstName | lastName | phoneNumber  | locale |
      | 123-user | jean      | paul     | +33123213123 | en     |
    And the api token for this user is "api-token-user"
    And this user is assigned to this course
    And I add "Authorization" header equal to "Bearer api-token-user"
    And I add "Content-Type" header equal to "application/json"
    When I send a POST request to "/api/graphql/" with body:
      """
      {"query": "query { session(uuid: \"1231-123-123\") { title, validated }}", "variables": null}
      """
    Then the response status code should be 200
    And the JSON should be equal to:
    """
      {
          "data": {
              "session": {
                  "title": "Session title",
                  "validated": false
              }
          }
      }
    """
    Then I add "Authorization" header equal to "Bearer api-token-user"
    And I add "Content-Type" header equal to "application/json"
    When I send a POST request to "/api/graphql/" with body:
      """
      {"query": "mutation Validate($sessionUuid: String!) {\n  validateSessionMutation(uuid: $sessionUuid)\n}\n\n","variables":{"sessionUuid":"1231-123-123"},"operationName":"Validate"}
      """
    Then the response status code should be 200
    And the JSON should be equal to:
    """
      {
          "data": {
              "validateSessionMutation": true
          }
      }
    """
    Then I add "Authorization" header equal to "Bearer api-token-user"
    And I add "Content-Type" header equal to "application/json"
    When I send a POST request to "/api/graphql/" with body:
      """
      {"query": "query { session(uuid: \"1231-123-123\") { title, validated }}", "variables": null}
      """
    Then the response status code should be 200
    And the JSON should be equal to:
    """
      {
          "data": {
              "session": {
                  "title": "Session title",
                  "validated": true
              }
          }
      }
    """
