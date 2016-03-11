#!/bin/sh

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

echo "Search for BlueCountGUI Extension"

if [ ! -d $PKPATH/Ext/BlueCountGUI ] ; then
    echo "Error: BlueCountGUI extension not found"
    exit 1
fi

echo "Clean ~ files"

for i in `find . -name "*~"` ; do
    rm -f $i
done

echo "Copy files"

RSYNC='rsync -r -p --links --exclude=".svn"'

$RSYNC . $PKPATH/Ext/BlueCountGUI/var/www/BlueCountGUI/
