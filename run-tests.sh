#!/bin/bash
set -e

# Choose the appropriate Docker Compose command for your environment
# Uncomment ONE of these lines:
#DOCKER_COMPOSE="docker-compose"  # For Docker Compose V1
DOCKER_COMPOSE="docker compose"  # For Docker Compose V2

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}====================================================================${NC}"
echo -e "${BLUE}                  AstroAPI Test Runner v2.0                     ${NC}"
echo -e "${BLUE}====================================================================${NC}"

# Parse command line arguments
COVERAGE=0
SPECIFIC_TEST=""
TESTSUITE=""
DEBUG=0

while [[ $# -gt 0 ]]; do
  case $1 in
    --coverage)
      COVERAGE=1
      shift
      ;;
    --unit)
      TESTSUITE="--testsuite=Unit"
      shift
      ;;
    --functional)
      TESTSUITE="--testsuite=Functional"
      shift
      ;;
    --filter=*)
      SPECIFIC_TEST="--filter=${1#*=}"
      shift
      ;;
    --debug)
      DEBUG=1
      shift
      ;;
    *)
      echo -e "${RED}Unknown option: $1${NC}"
      echo "Usage: $0 [--coverage] [--unit] [--functional] [--filter=TestName] [--debug]"
      exit 1
      ;;
  esac
done

echo -e "${YELLOW}Setting permissions for bin/console and bin/phpunit${NC}"
${DOCKER_COMPOSE} exec php chmod +x bin/console bin/phpunit || {
  echo -e "${RED}Failed to set permissions. Check if containers are running.${NC}"
  echo -e "${YELLOW}Try running: ${DOCKER_COMPOSE} up -d${NC}"
  exit 1
}

echo -e "${GREEN}Preparing test environment...${NC}"

echo -e "${BLUE}Dropping test database if exists...${NC}"
${DOCKER_COMPOSE} exec php php bin/console doctrine:database:drop --env=test --force --if-exists

echo -e "${BLUE}Creating test database...${NC}"
${DOCKER_COMPOSE} exec php php bin/console doctrine:database:create --env=test

echo -e "${BLUE}Creating database schema...${NC}"
${DOCKER_COMPOSE} exec php php bin/console doctrine:schema:create --env=test

echo -e "${BLUE}Loading test fixtures...${NC}"
${DOCKER_COMPOSE} exec php php bin/console doctrine:fixtures:load --env=test --no-interaction

# Check if specific test was requested
if [ -n "$SPECIFIC_TEST" ]; then
  echo -e "${GREEN}Running specific test with filter: ${SPECIFIC_TEST#--filter=}${NC}"
elif [ -n "$TESTSUITE" ]; then
  echo -e "${GREEN}Running test suite: ${TESTSUITE#--testsuite=}${NC}"
else
  echo -e "${GREEN}Running all tests...${NC}"
fi

# Clear cache before tests
echo -e "${BLUE}Clearing test cache...${NC}"
${DOCKER_COMPOSE} exec php php bin/console cache:clear --env=test

# Run the tests
if [ $COVERAGE -eq 1 ]; then
  echo -e "${YELLOW}Running tests with coverage report...${NC}"
  ${DOCKER_COMPOSE} exec -e XDEBUG_MODE=coverage php php bin/phpunit --coverage-html var/coverage $TESTSUITE $SPECIFIC_TEST
  
  echo -e "${GREEN}Coverage report generated in var/coverage directory${NC}"
else
  echo -e "${YELLOW}Running tests...${NC}"
  if [ $DEBUG -eq 1 ]; then
    ${DOCKER_COMPOSE} exec php php bin/phpunit $TESTSUITE $SPECIFIC_TEST --debug
  else
    ${DOCKER_COMPOSE} exec php php bin/phpunit $TESTSUITE $SPECIFIC_TEST
  fi
fi

echo -e "${BLUE}====================================================================${NC}"
echo -e "${GREEN}Test execution completed!${NC}"
echo -e "${BLUE}====================================================================${NC}"