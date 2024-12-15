#!/bin/bash

if [ -z "$MAJOR_VERSION" ]; then
    echo "Error: MAJOR_VERSION environment variable is not set"
    exit 1
fi

# Get all versions and store them in an array
versions=($(npm view @mtrdesign/krait-ui versions | tr -d "'[]," | tr " " "\n"))

# Loop through each version
for version in "${versions[@]}"; do
    # Check if version starts with provided major version and contains "beta"
    if [[ $version =~ ^${MAJOR_VERSION}\. && $version =~ beta ]]; then
        echo "Deleting version: $version"
        npm unpublish -f "@mtrdesign/krait-ui@$version"
    fi
done
