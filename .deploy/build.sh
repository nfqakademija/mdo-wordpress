#!/usr/bin/env bash

# This script prepares all dependent files to be deployed to server as a single .tar file
# This script intended to be executed in Jenkins CI, but likelly to be extended by you.

set -e # Stop on error
set -x # Show commands being executed

tar czf project.tar.gz --owner 0 --group 0 --anchored $( \
    ls -a | tail -n +3 \
    | grep -v ".deploy" \
    | grep -v ".idea" \
)
