#!/bin/sh
git remote add production ssh://travis@phpminds.org/home/travis/phpminds.git
GIT_SSH_COMMAND="ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no" git push production develop

return 0
