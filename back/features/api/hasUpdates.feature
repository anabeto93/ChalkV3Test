Feature: hasUpdates api
  Scenario: test
    Given the database is purged
    And there is a course with the following info
      | title       | GRAPHQL Implementation |
      | updatedAt   | 2017-07-20 10:00       |
      | size        | 889                    |
    And there is a user called "jean" "paul" with the uuid "123-user" and the phone number "+33123213123"
    And the api token for this user is "api-token-user"
    And I add "key" header equal to "api-token-user"
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
    Then I add "key" header equal to "api-token-user"
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