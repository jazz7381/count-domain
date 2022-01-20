#!/bin/bash

_FILEPATH=$1
_CHUNKS=$2

FILENAME=${_FILEPATH}
CHUNK=${_CHUNKS}

HDR=$(head -1 $FILENAME)   # Pick up CSV header line to apply to each file
split -l $CHUNK $FILENAME xyz # Split the file into chunks of 20 lines each
n=1
for f in xyz*              # Go through all newly created chunks
do
    EXTENSION="${FILENAME##*.}"
    NEWFILENAME="${FILENAME%.*}"
    echo $HDR > $NEWFILENAME-${n}.${EXTENSION}    # Write out header to new file called "Part(n)"
    cat $f >> $NEWFILENAME-${n}.${EXTENSION}      # Add in the 20 lines from the "split" command
    rm $f                   # Remove temporary file
    ((n++))                 # Increment name of output part
done
