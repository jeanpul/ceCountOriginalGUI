#!/bin/bash

# config
VERSION=$(node --eval "console.log(require('./package.json').version);")
NAME=$(node --eval "console.log(require('./package.json').name);")

# checkout temp branch for release
git checkout -b gh-release

# commit changes with a versioned commit message
git commit -m "build $VERSION"

# push commit so it exists on GitHub when we run gh-release
git push origin gh-release

# create a ZIP archive of the dist files
mkdir -p dist
# clean previous archives
(cd dist ; rm -f *.zip )
zip -r dist/$NAME-v$VERSION.zip BlueCountGUI BluePortail welcome--exclude '*.git*' --exclude '*~'

# run gh-release to create the tag and push release to github
./node_modules/.bin/gh-release --assets dist/$NAME-v$VERSION.zip

# checkout master and delete release branch locally and on GitHub
git checkout master
git branch -D gh-release
git push origin :gh-release


