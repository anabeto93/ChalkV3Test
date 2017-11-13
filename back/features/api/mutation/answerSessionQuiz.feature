Feature: Answer session quiz api
  Scenario: I can answer to a quiz session
    Given the database is purged
    And there is a course with the uuid "30575fe6-0bb6-4dfc-a38a-899e39bdf911" and the title "First course"
    And there is a session with the uuid "abcdef12345-ab123-ab123" and the title "Session title" for this course
    And there is a question with the title "What is the color of Henri IV white horse?" for this session
    And there is a correct answer with the title "white" for this question
    And there is an incorrect answer with the title "black" for this question
    And there is an incorrect answer with the title "brown" for this question
    And there is a question with the title "What is the capital city of Iceland?" for this session
    And there is an incorrect answer with the title "Paris" for this question
    And there is a correct answer with the title "Reykjavik" for this question
    And there is an incorrect answer with the title "Accra" for this question
    And there is an incorrect answer with the title "Berlin" for this question
    And there is a question with the title "What are the frameworks used in ChalkBoard Education?" for this session
    And there is a correct answer with the title "React" for this question
    And there is an incorrect answer with the title "Angular" for this question
    And there is an incorrect answer with the title "Laravel" for this question
    And there is a correct answer with the title "Symfony" for this question
    And there is following users
      | uuid     | firstName | lastName | phoneNumber  | locale | token     |
      | 123-user | jean      | paul     | +33123213123 | en     | tokenUser |
    And this user is assigned to this course
    Then I add "Authorization" header equal to "Bearer tokenUser"
    And I add "Content-Type" header equal to "application/json"
    When I send a POST request to "/api/graphql/" with body:
      """
      {
        "query": "mutation answerSessionQuiz($answerSessionQuizInput: answerSessionQuizInput!) {answerSessionQuiz(input: $answerSessionQuizInput)}",
        "variables": {"answerSessionQuizInput": {"uuid": "abcdef12345-ab123-ab123", "answers": "djhsjkhjkds"}},
        "operationName": "answerSessionQuiz"
      }
      """
    Then the response status code should be 200
    And the JSON should be equal to:
    """
      {
          "data": {
              "answerSessionQuiz": true
          }
      }
    """
