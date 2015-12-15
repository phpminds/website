#!/bin/sh
# GIT deploy script

git remote add production ssh://travis@phpminds.org/home/travis/phpminds.git
git push production develop

return 0
