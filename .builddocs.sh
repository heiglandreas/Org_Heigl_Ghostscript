#!/bin/bash
set -o errexit -o nounset

echo "Preparing to build and deploy documentation"

if [[ -z ${GH_USER_NAME} || -z ${GH_USER_EMAIL} || -z ${GH_REF} ]]; then
    echo "Missing environment variables. Aborting"
    exit 1
fi;

SCRIPT_PATH="$(cd "$(dirname "$0")" && pwd -P)"

# Get the deploy key
ENCRYPTED_KEY_VAR="encrypted_${ENCRYPTION_LABEL}_key"
ENCRYPTED_IV_VAR="encrypted_${ENCRYPTION_LABEL}_iv"
ENCRYPTED_KEY=${!ENCRYPTED_KEY_VAR}
ENCRYPTED_IV=${!ENCRYPTED_IV_VAR}
openssl aes-256-cbc -K $ENCRYPTED_KEY -iv $ENCRYPTED_IV -in deploy_key.enc -out deploy_key -d
chmod 600 deploy_key
eval `ssh-agent -s`
ssh-add deploy_key


# Get curent commit revision
rev=$(git rev-parse --short HEAD)

# Initialize gh-pages checkout
mkdir -p build/html
(
    cd build/html
    git init
    git config user.name "${GH_USER_NAME}"
    git config user.email "${GH_USER_EMAIL}"
    git remote add upstream "git@${GH_REF}"
    git fetch upstream
    git reset --hard upstream/gh-pages
)

# Build the documentation

mkdocs build --clean
cp -r build/phpunit/coverage build/html/
cp -r build/api build/html/

# Commit and push the documentation to gh-pages
(
    cd build/html
    touch .
    git add -A .
    git commit -m "Rebuild pages at ${rev}"
    git push -q upstream HEAD:gh-pages
)

echo "Completed deploying documentation"