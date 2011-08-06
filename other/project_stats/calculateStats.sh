#!/bin/bash

#we dont want to count the binary files (images etc.), so we add "sed" rules to ignore them
ignore="sed"
ignore="${ignore} -e '/^.*png/ d' " #exclude *.png
ignore="${ignore} -e '/^API\/paypal\/lib.*/ d'" #exclude the paypal lib (just copied code)
ignore="${ignore} -e '/^website\/include\/jquery.*/ d'" #exclude jquery

echo $ignore

#moving to project root
cd ../..
#calculate & output
git ls-files | eval $ignore | xargs -n1 -d'\n' -i git blame {} | perl -n -e '/\s\((.*?)\s[0-9]{4}/ && print "$1\n"' | sort -f | uniq -c -w3 | sort -r
