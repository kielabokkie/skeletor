Feature: Example Feature
  In order to see if Behat is working correctly
  I need to see that I can successfully access the application

    Scenario: Check if root domain is accessible
        Given I request "GET /"
        Then I get a 200 response
