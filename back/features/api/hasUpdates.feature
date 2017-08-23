Feature: hasUpdates api
  Scenario: test
    Given the database is purged
    And there is a course with the following info
      | title       | GRAPHQL Implementation |
      | updatedAt   | 2017-07-20 10:00       |
      | size        | 889                    |
    And there is following users
      | uuid       | firstName | lastName | phoneNumber    | locale |
      | "123-user" | "jean"    | "paul"   | "+33123213123" | "en"   |
    And the api token for this user is "api-token-user"
    And this user is assigned to this course
    And I add "Authorization" header equal to "Bearer api-token-user"
    When I add "Content-Type" header equal to "application/json"
    And I send a POST request to "/api/graphql/" with body:
      """
      {"query": "query { hasUpdates(dateLastUpdate: \"2017-07-21 12:00\") { size, hasUpdates }}", "variables": null}
      """
    Then the response status code should be 200
    And the JSON should be equal to:
    """
      {
        "data": {
          "hasUpdates": {
            "size": 0,
            "hasUpdates": false
          }
        }
      }
    """
    Then I add "Authorization" header equal to "Bearer api-token-user"
    When I add "Content-Type" header equal to "application/json"
    And I send a POST request to "/api/graphql/" with body:
      """
      {"query": "query { hasUpdates(dateLastUpdate: \"2017-07-12 12:00\") { size, hasUpdates }}", "variables": null}
      """
    Then the response status code should be 200
    And the JSON should be equal to:
    """
      {
        "data": {
          "hasUpdates": {
            "size": 889,
            "hasUpdates": true
          }
        }
      }
    """
