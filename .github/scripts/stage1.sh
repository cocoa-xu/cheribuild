#!/bin/sh

set -xe

GIT_COMMIT=$1
ARCHITECTURE=$2
TARGETS=$3
HOST_TRIPLET=$4

if [ "${DOCKER_PLATFORM}" = "linux/arm64" ]; then
    sudo docker run --privileged --network=host --rm --platform="linux/arm64" -v $(pwd):/work "ubuntu:latest" \
        sh -c "chmod a+x /work/stage2.sh && /work/stage2.sh ${GIT_COMMIT} ${ARCHITECTURE} ${TARGETS} ${HOST_TRIPLET}"
else
    sudo docker run --privileged --network=host --rm -v $(pwd):/work "ubuntu:latest" \
        sh -c "chmod a+x /work/stage2.sh && /work/stage2.sh ${GIT_COMMIT} ${ARCHITECTURE} ${TARGETS} ${HOST_TRIPLET}"
fi
