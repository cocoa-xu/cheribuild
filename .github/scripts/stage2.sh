#!/bin/sh

set -xe

GIT_COMMIT=$1
ARCHITECTURE=$2
TARGETS=$3
HOST_TRIPLET=$4

export DEBIAN_FRONTEND=noninteractive
apt-get update
apt-get install -y sudo git autoconf automake libtool pkg-config clang bison cmake flex bc \
  mercurial ninja-build samba texinfo time libglib2.0-dev libpixman-1-dev libgmp-dev \
  libarchive-dev libarchive-tools libbz2-dev libattr1-dev libcap-ng-dev libexpat1-dev \
  python3-full python3-pip python3-setuptools python3-wheel python3-dev xz-utils

mkdir -p /work/build
chmod a+rw /work/build

adduser --disabled-password --gecos "" cheribuild
cp /work/stage3.sh /home/cheribuild/stage3.sh

mkdir -p /home/cheribuild/.ssh
cp /work/id_rsa /work/id_rsa.pub /home/cheribuild/.ssh
chown -R cheribuild:cheribuild /home/cheribuild
chmod 700 /home/cheribuild/.ssh
chmod 600 /home/cheribuild/.ssh/id_rsa
chmod 644 /home/cheribuild/.ssh/id_rsa.pub

chmod a+x /home/cheribuild/stage3.sh
sudo -u cheribuild bash -c "cd /home/cheribuild && ./stage3.sh ${GIT_COMMIT} ${ARCHITECTURE} ${TARGETS} ${HOST_TRIPLET}"
