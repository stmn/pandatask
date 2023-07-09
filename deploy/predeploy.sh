#!/bin/bash
set -eo pipefail;

#git config --global --add safe.directory /app
## Get last 7 symbols of commit
#git rev-parse @ | cut -c1-7  > ./version
#
## Remove old symlink in public/storage if exists
#[[ -r public/storage ]] && rm public/storage
#
## Create storage paths if missing in container.
## NOTE: Persistent storage still not mounted in predeploy step.
#mkdir -p storage/app/public
#mkdir -p storage/framework/{cache,sessions,testing,views}
#mkdir -p storage/logs

# Create storage symlink in predeploy linking to container, not persistent storage.
# Link target will be substituted when persistent storage is mounted.
#php artisan storage:link
