# Compile and Install the traduction file
# to $1/usr/share/locale/fr/LC_MESSAGES/
#!/bin/bash

prefix=""

if [ -n "$1" ] ; then
    prefix=$1
fi

msgfmt -o $prefix/usr/share/locale/fr/LC_MESSAGES/BlueCountGUI.mo BlueCountGUI_fr.po
msgfmt -o $prefix/usr/share/locale/en/LC_MESSAGES/BlueCountGUI.mo BlueCountGUI_en.po
msgfmt -o $prefix/usr/share/locale/es/LC_MESSAGES/BlueCountGUI.mo BlueCountGUI_es.po

#msgfmt -o $prefix/usr/share/locale/ja/LC_MESSAGES/BlueCountGUI.mo BlueCountGUI_ja.po


