#!/bin/sh

set -x

GIT_COMMIT=$1
ARCHITECTURE=$2

sudo docker run --privileged --network=host --rm -v $(pwd):/work "ubuntu:latest" \
    sh -c "chmod a+x /work/stage2.sh && /work/stage2.sh ${GIT_COMMIT} ${ARCHITECTURE}"
