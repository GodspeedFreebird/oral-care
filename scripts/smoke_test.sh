#!/bin/bash
set -e

BASE_URL="${1:-http://127.0.0.1:8080}"
FAILED=0

echo "========================================"
echo "Starting Smoke Test Suite for Oral Care"
echo "Target Base URL: $BASE_URL"
echo "========================================"

# Helper function to test URL
check_endpoint() {
    local endpoint="$1"
    local expected_status="$2"
    local match_text="$3"
    local full_url="${BASE_URL}${endpoint}"

    echo -n "Testing [GET] $endpoint ... "
    
    response=$(curl -s -w "\n%{http_code}" "$full_url")
    http_code=$(echo "$response" | tail -n1)
    body=$(echo "$response" | sed '$d')

    if [ "$http_code" != "$expected_status" ]; then
        echo "FAILED (Expected HTTP $expected_status, got $http_code)"
        FAILED=1
        return
    fi

    if [ -n "$match_text" ]; then
        if echo "$body" | grep -qi "$match_text"; then
            echo "PASSED (HTTP $http_code, found '$match_text')"
        else
            echo "FAILED (HTTP $http_code, but missing '$match_text' in response)"
            echo "--- Snippet of response ---"
            echo "$body" | head -n 15
            echo "---------------------------"
            FAILED=1
        fi
    else
        echo "PASSED (HTTP $http_code)"
    fi
}

# 1. Test Homepage
check_endpoint "/" "200" "Dental"

# 2. Test Login page
check_endpoint "/login" "200" "Login"

# 3. Test Register page
check_endpoint "/register" "200" "Register"

# 4. Check for PHP Fatal Errors in Docker container if docker is available
if command -v docker &> /dev/null && docker ps | grep -q "oralcare"; then
    echo -n "Checking Docker container logs for Fatal PHP errors ... "
    if docker logs oralcare_container 2>&1 | grep -i "Fatal error"; then
        echo "FAILED (Fatal PHP error detected in logs!)"
        FAILED=1
    else
        echo "PASSED (No fatal errors detected)"
    fi
fi

echo "========================================"
if [ $FAILED -eq 0 ]; then
    echo "All smoke tests PASSED successfully! Ready for deployment."
    exit 0
else
    echo "Smoke tests FAILED. Please review the errors above."
    exit 1
fi
