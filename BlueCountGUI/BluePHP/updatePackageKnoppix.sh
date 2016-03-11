#!/bin/bash
# Copy all necessary files to the specified package
# knoppix directory given by $1
# PackageKnoppix directory should contain source and master subdirectories such
# as the common knoppix source tree

usage()
{
    echo "updatePackageKnoppix.sh <package knoppix path>"
}

if [ $# -lt 1 ]; then
    usage
    exit 1
fi

PKPATH=$1

if [ ! -d $PKPATH ] ; then
    echo "Error: $PKPATH not found"
    exit 1
fi

echo "Clean ~ files"

for i in `find . -name "*~"` ; do
    rm -f $i
done

echo "Copy files"

RSYNC='rsync -r -p --links --exclude=".svn"'

$RSYNC . $PKPATH/source/KNOPPIX/var/www/BluePHP/
