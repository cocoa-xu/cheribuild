#!/bin/bash

set -e

export DOCKER_CLI_EXPERIMENTAL=enabled
export VERSION=${VERSION:-"v2024.01.05-f8b62f01"}

docker buildx create --use

export PLATFORM="linux/amd64,linux/arm64"

export ARCHIECTURE=purecap
export DISK_IMAGE=normal
docker buildx build --push --platform "${PLATFORM}" . \
    -t cocoaxu/cheribsd-"${ARCHIECTURE}":latest \
    -t cocoaxu/cheribsd-"${ARCHIECTURE}":"${VERSION}" \
    --build-arg architecture="${ARCHIECTURE}" \
    --build-arg version="${VERSION}" \
    --build-arg disk_image="${DISK_IMAGE}"

export ARCHIECTURE=purecap
export DISK_IMAGE=minimal
docker buildx build --push --platform "${PLATFORM}" . \
    -t cocoaxu/cheribsd-minimal-"${ARCHIECTURE}":latest \
    -t cocoaxu/cheribsd-minimal-"${ARCHIECTURE}":"${VERSION}" \
    --build-arg architecture="${ARCHIECTURE}" \
    --build-arg version="${VERSION}" \
    --build-arg disk_image="${DISK_IMAGE}"

export ARCHIECTURE=hybrid
export DISK_IMAGE=normal
docker buildx build --push --platform "${PLATFORM}" . \
    -t cocoaxu/cheribsd-"${ARCHIECTURE}":latest \
    -t cocoaxu/cheribsd-"${ARCHIECTURE}":"${VERSION}" \
    --build-arg architecture="${ARCHIECTURE}" \
    --build-arg version="${VERSION}" \
    --build-arg disk_image="${DISK_IMAGE}"

export ARCHIECTURE=hybrid
export DISK_IMAGE=minimal
docker buildx build --push --platform "${PLATFORM}" . \
    -t cocoaxu/cheribsd-minimal-"${ARCHIECTURE}":latest \
    -t cocoaxu/cheribsd-minimal-"${ARCHIECTURE}":"${VERSION}" \
    --build-arg architecture="${ARCHIECTURE}" \
    --build-arg version="${VERSION}" \
    --build-arg disk_image="${DISK_IMAGE}"
