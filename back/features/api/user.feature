Feature: User api
  Scenario: I can get all info of the logged user
    Given the database is purged
    And there is a user called "jean" "paul" with the uuid "123-user" and the phone number "+33123213123"
    And the api token for this user is "api-token-user"
    And I add "key" header equal to "api-token-user"
    When I add "Content-Type" header equal to "application/json"
    And I send a POST request to "/api/graphql/" with body:
      """
      {"query": "query { user { uuid, firstName, lastName, country, countryCode, phoneNumber }}", "variables": null}
      """
    Then the response status code should be 200
    And the JSON should be equal to:
    """
      {
          "data": {
              "user": {
                  "uuid": "123-user",
                  "firstName": "jean",
                  "lastName": "paul",
                  "country": "Ghana",
                  "countryCode": "GH",
                  "phoneNumber": "+33123213123"
              }
          }
      }
    """
