#!/bin/sh

set -x

GIT_COMMIT=$1
ARCHITECTURE=$2

git clone --depth=1 https://github.com/CTSRD-CHERI/cheribuild.git "$HOME/cheribuild"
cd "$HOME/cheribuild"
git checkout "$GIT_COMMIT"

python3 ./cheribuild.py "cheribsd-sdk-morello-${ARCHITECTURE}" -f
python3 ./cheribuild.py "disk-image-minimal-morello-${ARCHITECTURE}" -f

xz -z -e -T0 "${HOME}/cheri/output/cheribsd-minimal-morello-${ARCHITECTURE}.img"
rm -f "${HOME}/cheri/output/cheribsd-minimal-morello-${ARCHITECTURE}.img"
mv "${HOME}/cheri/output/cheribsd-minimal-morello-${ARCHITECTURE}.img.xz" /work/build/

tar -C "${HOME}/cheri/output" -cJf "$(pwd)/sdk-${ARCHITECTURE}.tar.xz" --options='-e -T0' sdk
mv "$(pwd)/sdk-${ARCHITECTURE}.tar.xz" /work/build/

tar -C "${HOME}/cheri/output" -cJf "$(pwd)/morello-sdk-${ARCHITECTURE}.tar.xz" --options='-e -T0' morello-sdk
mv "$(pwd)/morello-sdk-${ARCHITECTURE}.tar.xz" /work/build/

cd /work/build
sha256sum cheribsd-minimal-morello-${ARCHITECTURE}.img.xz | tee cheribsd-minimal-morello-${ARCHITECTURE}.img.xz.sha256
sha256sum sdk-${ARCHITECTURE}.tar.xz | tee sdk-${ARCHITECTURE}.tar.xz.sha256
sha256sum morello-sdk-${ARCHITECTURE}.tar.xz | tee morello-sdk-${ARCHITECTURE}.tar.xz.sha256

rm -rf "${HOME}/cheri/"
