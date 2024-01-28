<h1><a href="https://cheri.build" target="about:_blank"><img src="https://github.com/cocoa-xu/cheribuild/raw/main/assets/repository-open-graph.png" alt="Logo"></a></h1>

Unofficial builds of [CheriBSD](https://www.cheribsd.org), a system that support ARM Morello in emulation and on hardware. 

> CheriBSD is a Capability Enabled, Unix-like Operating System which takes advantage of Capability Hardware on Arm's Morello and CHERI-RISC-V platforms.

This repo currently uses [CTSRD-CHERI/cheribuild](https://github.com/CTSRD-CHERI/cheribuild) to build CheriBSD purecap and hybrid system images for ARM Morello biweekly.

Prebuilt CheriBSD system images can be found at [https://cheri.build](https://cheri.build).

## Docker Image
This repo also builds Docker images that can be run prebuilt CheriBSD system images. Prebuilt Docker images includes:

- [`cocoaxu/cheribsd-purecap`](https://hub.docker.com/r/cocoaxu/cheribsd-purecap): CheriBSD purecap system image
- [`cocoaxu/cheribsd-hybrid`](https://hub.docker.com/r/cocoaxu/cheribsd-hybrid): CheriBSD hybrid system image
- [`cocoaxu/cheribsd-minimal-purecap`](https://hub.docker.com/r/cocoaxu/cheribsd-minimal-purecap): The minimal CheriBSD purecap system image
- [`cocoaxu/cheribsd-minimal-hybrid`](https://hub.docker.com/r/cocoaxu/cheribsd-minimal-hybrid): The minimal CheriBSD hybrid system image

The tag of the Docker image is the same as the tag of the releases in this repo. For example, [`v2024.01.05-f8b62f01`](https://github.com/cocoa-xu/cheribuild/releases/tag/v2024.01.05-f8b62f01) is the Docker image built based on [CTSRD-CHERI/cheribuild@f8b62f01](https://github.com/CTSRD-CHERI/cheribuild/tree/f8b62f01), which was commited on 05 Jan 2024.

Also, these Docker images are built on both `linux/amd64` and `linux/arm64` platforms. Therefore, you can run these Docker images on either x86_64 and ARM64 machines without performing cost by emulation.

### Usage
For example, to run the Docker image, you can use the following command:

```bash
docker run --rm -it cocoaxu/cheribsd-purecap
```

And it will automatically start the CheriBSD purecap system image in QEMU. 

You can also run the Docker image with the following command to start the CheriBSD purecap system image in QEMU with a shell:

```bash
# on host machine
docker run --rm -it --entrypoint /bin/bash cocoaxu/cheribsd-purecap

# and inside the container
#
# using the following command to start 
# the CheriBSD system image in QEMU with default settings
/usr/bin/start_cheribsd

# alternatively, you can use the following command inside the container
# to start the CheriBSD system image in QEMU with custom settings
#
# set number of CPUs
NCPUS=4
# set memory size
MEMORY=4096
# start QEMU
/usr/local/bin/qemu-system-morello \
    -M virt,gic-version=3 \
    -cpu morello \
    -smp "${NCPUS}" \
    -bios edk2-aarch64-code.fd \
    -m "${MEMORY}" \
    -nographic \
    -drive if=none,file="/cheribsd.img",id=drv,format=raw \
    -device virtio-blk-pci,drive=drv \
    -device virtio-net-pci,netdev=net0 \
    -netdev 'user,id=net0,hostfwd=tcp::2222-:22' \
    -device virtio-rng-pci
```
