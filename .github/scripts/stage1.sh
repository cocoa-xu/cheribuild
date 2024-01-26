#!/bin/sh

set -xe

GIT_COMMIT=$1
ARCHITECTURE=$2
TARGETS=$3
HOST_TRIPLET=$4

sudo docker run --privileged --network=host --rm --platform="${DOCKER_PLATFORM}" -v $(pwd):/work "ubuntu:latest" \
    sh -c "chmod a+x /work/stage2.sh && /work/stage2.sh ${GIT_COMMIT} ${ARCHITECTURE} ${TARGETS} ${HOST_TRIPLET}"
