#!/bin/sh

set -xe

GIT_COMMIT=$1
ARCHITECTURE=$2
TARGETS=$3
HOST_TRIPLET=$4

git clone --depth=1 https://github.com/CTSRD-CHERI/cheribuild.git "$HOME/cheribuild"
cd "$HOME/cheribuild"
git checkout "$GIT_COMMIT"

if [ "${ARCHITECTURE}" = "hybrid" ]; then
  python3 ./cheribuild.py --enable-hybrid-targets "cheribsd-sdk-morello-${ARCHITECTURE}" -f 
  if [ "${TARGETS}" = "image+sdk" ]; then
    python3 ./cheribuild.py --enable-hybrid-targets "disk-image-morello-${ARCHITECTURE}" -f
  fi
else
  python3 ./cheribuild.py "cheribsd-sdk-morello-${ARCHITECTURE}" -f 
  if [ "${TARGETS}" = "image+sdk" ]; then
    python3 ./cheribuild.py "disk-image-morello-${ARCHITECTURE}" -f
  fi
fi

export XZ_OPT="-e -T0 -9"

if [ "${TARGETS}" = "image+sdk" ]; then
  xz -z -e -T0 -9 "${HOME}/cheri/output/cheribsd-morello-${ARCHITECTURE}.img"
  rm -f "${HOME}/cheri/output/cheribsd-morello-${ARCHITECTURE}.img"
  mv "${HOME}/cheri/output/cheribsd-morello-${ARCHITECTURE}.img.xz" /work/build/
fi

tar -C "${HOME}/cheri/output" -cJf "$(pwd)/qemu-${ARCHITECTURE}-${HOST_TRIPLET}.tar.xz" sdk
mv "$(pwd)/qemu-${ARCHITECTURE}-${HOST_TRIPLET}.tar.xz" /work/build/

tar -C "${HOME}/cheri/output" -cJf "$(pwd)/morello-sdk-${ARCHITECTURE}-${HOST_TRIPLET}.tar.xz" morello-sdk
mv "$(pwd)/morello-sdk-${ARCHITECTURE}-${HOST_TRIPLET}.tar.xz" /work/build/

cd /work/build
if [ "${TARGETS}" = "image+sdk" ]; then
  sha256sum cheribsd-morello-${ARCHITECTURE}.img.xz | tee cheribsd-morello-${ARCHITECTURE}.img.xz.sha256
fi
sha256sum qemu-${ARCHITECTURE}-${HOST_TRIPLET}.tar.xz | tee qemu-${ARCHITECTURE}-${HOST_TRIPLET}.tar.xz.sha256
sha256sum morello-sdk-${ARCHITECTURE}-${HOST_TRIPLET}.tar.xz | tee morello-sdk-${ARCHITECTURE}-${HOST_TRIPLET}.tar.xz.sha256

ls -lah /work/build/

rm -rf "${HOME}/cheri/"
