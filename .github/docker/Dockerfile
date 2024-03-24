FROM ubuntu:latest
LABEL maintainer="Cocoa Xu <i@uwucocoa.moe>"

ARG architecture
ARG version
ARG disk_image

WORKDIR /
ENV DEBIAN_FRONTEND=noninteractive \
    LANG=en_US.UTF-8 \
    LANGUAGE=en_US:en \
    LC_ALL=en_US.UTF-8 \
    PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
RUN apt-get update -q=2 && \
    apt-get install -q=2 --yes \
        --no-install-recommends --no-install-suggests \
        curl locales ca-certificates xz-utils bc \
        libncursesw6 libpixman-1-0 libglib2.0-0 libtinfo6 libbz2-1.0 \
        libmount1 libselinux1 libffi8 libpcre2-dev libblkid1 \
        libffi8 libpcre2-dev libblkid1 && \
    dpkg-reconfigure --frontend=noninteractive locales && \
    update-locale && \
    echo "en_US.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen && \    
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    export HOST_ARCHIECTURE="$(uname -p)-linux-gnu" && \
    curl -fSL "https://github.com/cocoa-xu/cheribuild/releases/download/${version}/qemu-${HOST_ARCHIECTURE}.tar.xz" -o "qemu-${HOST_ARCHIECTURE}.tar.xz" && \
    tar -xf "qemu-${HOST_ARCHIECTURE}.tar.xz" -C /usr/local --strip-components=1 && \
    rm -f "qemu-${HOST_ARCHIECTURE}.tar.xz" && \
    cd /usr/local && \
    mv ./bin/qemu-system-morello ./ && \
    rm -f ./bin/qemu-system-* && \
    mv ./qemu-system-morello ./bin/ && \
    rm -f ./share/qemu/edk2-arm* && \
    rm -f ./share/qemu/edk2-i386* && \
    rm -f ./share/qemu/edk2-x86_64* && \
    rm -f ./share/qemu/bios* && \
    rm -f ./share/qemu/openbios* && \
    rm -f ./share/qemu/opensbi* && \
    rm -f ./share/qemu/s390* && \
    cd / && \
    if [ "${disk_image}" = "minimal" ]; then \
        export disk_image="cheribsd-minimal-morello-${architecture}.img" ; \
    else \
        export disk_image="cheribsd-morello-${architecture}.img" ; \
    fi && \
    curl -fSL "https://github.com/cocoa-xu/cheribuild/releases/download/${version}/${disk_image}.xz" -o "${disk_image}.xz" ; \
    xz -d "${disk_image}.xz" && \
    /usr/local/bin/qemu-img resize -f raw "${disk_image}" +2G && \
    mv "${disk_image}" /cheribsd.img && \
    A=$(echo "export LANG=en_US.UTF-8\nexport LANGUAGE=en_US.UTF-8\nexport LC_ALL=en_US.UTF-8\n" >> /root/.bashrc) && \
    echo "root:root" | chpasswd
COPY ./start_cheribsd /usr/bin/start_cheribsd
RUN chmod +x /usr/bin/start_cheribsd
ENTRYPOINT ["/usr/bin/start_cheribsd"]
