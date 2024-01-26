<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cheri Build</title>
    <style>
        /* Box sizing rules */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            background-color: aliceblue;
        }

        .tooltip {
            visibility: hidden;
            position: absolute;
        }

        .has-tooltip:hover .tooltip {
            visibility: visible;
            z-index: 100;
        }

        /* Remove default margin */
        body,
        h1,
        h2,
        h3,
        h4,
        p,
        figure,
        blockquote,
        dl,
        dd {
            margin: 0;
        }

        /* Remove list styles on ul, ol elements with a list role, which suggests default styling will be removed */
        ul[role='list'],
        ol[role='list'] {
            list-style: none;
        }

        /* Set core root defaults */
        html:focus-within {
            scroll-behavior: smooth;
        }

        /* Set core body defaults */
        body {
            min-height: 100vh;
            text-rendering: optimizeSpeed;
            line-height: 1.5;
        }

        /* A elements that don't have a class get default styles */
        a:not([class]) {
            text-decoration-skip-ink: auto;
        }

        /* Make images easier to work with */
        img,
        picture {
            max-width: 100%;
            display: block;
        }

        /* Inherit fonts for inputs and buttons */
        input,
        button,
        textarea,
        select {
            font: inherit;
        }

        /* Remove all animations, transitions and smooth scroll for people that prefer not to see them */
        @media (prefers-reduced-motion: reduce) {
            html:focus-within {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        input[type="radio"] {
            margin-left: 0.5px;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zepto/1.2.0/zepto.min.js"
        integrity="sha512-BrvVYNhKh6yST24E5DY/LopLO5d+8KYmIXyrpBIJ2PK+CyyJw/cLSG/BfJomWLC1IblNrmiJWGlrGueKLd/Ekw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    <noscript>Please enable JavaScript</noscript>
    <h3 class="text-xl my-9 text-center">Cheri Build</h3>
    <div class="flex flex-row flex-wrap justify-center mt-8 items-center mb-10 space-x-4 space-y-4 items-baseline">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg max-w-xl">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">CheriBSD Images</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Select the architecture you want for your CheriBSD
                    image.
                </p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-5 grid grid-cols-3 gap-4 px-6">
                        <dt class="text-sm font-medium text-gray-500 ">Architecture</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div>
                                <fieldset>
                                    <div class="space-y-4 flex items-center sm:space-y-0 sm:space-x-10">
                                        <div class="flex items-center">
                                            <input id="arch-purecap" name="arch" type="radio" value="purecap" checked
                                                class="focus:ring-indigo-500 h-4 w-5 text-indigo-600 border-gray-300">
                                            <label for="arch-purecap"
                                                class="ml-3 block text-sm font-medium text-gray-700">purecap</label>
                                        </div>

                                        <div class="flex items-center">
                                            <input id="arch-hybrid" name="arch" type="radio" value="hybrid"
                                                class="focus:ring-indigo-500 h-4 w-5 text-indigo-600 border-gray-300">
                                            <label for="arch-hybrid"
                                                class="ml-3 block text-sm font-medium text-gray-700">hybrid</label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 my-auto">
                            Release
                        </dt>
                        <dd class="mt-1 h-0 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div>
                                <select
                                    class="form-select px-3 text-base font-normal text-gray-700 bg-white border border-solid border-gray-300 rounded focus:text-gray-700 focus:bg-white focus:border-blue-600"
                                    name="release" id="cheribsd-release">
                                </select>
                            </div>
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:gap-4 sm:px-6">
                        <dd
                            class="mt-1 flex text-sm text-gray-900 sm:mt-0 sm:col-span-2 space-x-4 items-center justify-center ">
                            <button
                                class="px-4 py-2 font-semibold text-sm bg-cyan-500 text-white rounded-full shadow-sm"
                                onclick="onDownloadImageClicked();">Download Image</button>
                            <button
                                class="px-4 py-2 font-semibold text-sm bg-green-500 text-white rounded-full shadow-sm"
                                onclick="onRunLatestImageClicked();">Run Lastest Image</button>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg max-w-xl">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">QEMU + Morello SDK</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Select configuration you want for your QEMU</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 ">
                        <dt class="text-sm font-medium text-gray-500">Host Architecture</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <fieldset>
                                <div class="space-y-4 flex items-center sm:space-y-0 sm:space-x-10">
                                    <div class="flex items-center">
                                        <input id="cpu-x86_64" name="os-arch" type="radio" checked value="x86_64"
                                            class="focus:ring-indigo-500 h-4 w-5 text-indigo-600 border-gray-300">
                                        <label for="cpu-x86_64"
                                            class="ml-3 block text-sm font-medium text-gray-700">x86_64</label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="cpu-aarch64" name="os-arch" type="radio" value="aarch64"
                                            class="focus:ring-indigo-500 h-4 w-5 text-indigo-600 border-gray-300">
                                        <label for="cpu-aarch64"
                                            class="ml-3 block text-sm font-medium text-gray-700">aarch64</label>
                                    </div>
                                </div>
                            </fieldset>
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 ">
                        <dt class="text-sm font-medium text-gray-500">Host Operating System</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <fieldset>
                                <div class="space-y-4 flex items-center sm:space-y-0 sm:space-x-10">
                                    <div class="flex items-center">
                                        <input id="os-mac" name="os" type="radio" checked value="apple-darwin"
                                            class="focus:ring-indigo-500 h-4 w-5 text-indigo-600 border-gray-300">
                                        <label for="os-mac"
                                            class="ml-3 block text-sm font-medium text-gray-700">macOS</label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="os-linux" name="os" type="radio" value="linux-gnu"
                                            class="focus:ring-indigo-500 h-4 w-5 text-indigo-600 border-gray-300">
                                        <label for="os-linux"
                                            class="ml-3 block text-sm font-medium text-gray-700">Linux</label>
                                    </div>
                                </div>
                            </fieldset>
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:gap-4 sm:px-6">
                        <dd
                            class="mt-1 flex text-sm text-gray-900 sm:mt-0 sm:col-span-2 space-x-4 items-center justify-center ">
                            <button
                                class="px-4 py-2 font-semibold text-sm bg-blue-500 text-white rounded-full shadow-sm"
                                onclick="onDownloadQEMUClicked();">Download QEMU</button>
                            <button
                                class="px-4 py-2 font-semibold text-sm bg-blue-500 text-white rounded-full shadow-sm"
                                onclick="onDownloadSDKClicked();">Download Morello SDK</button>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

</body>

<script>
    function onDownloadImageClicked() {
        const downloadUrl = (`/cheribsd/${$("#cheribsd-release").val()}/images/cheribsd-morello-${$("input[name='arch']:checked").val()}.img.xz`)
        console.log(downloadUrl)
        window.open(downloadUrl)
    }

    function onRunLatestImageClicked() {
        const redirectUrl = (`https://cheri.run/?architecture=${$("input[name='arch']:checked").val()}`)
        window.open(redirectUrl)
    }

    function onDownloadQEMUClicked() {
        const downloadUrl = `/cheribsd/${$('#cheribsd-release').val()}/qemu/qemu-${$("input[name='os-arch']:checked").val()}-${$("input[name='os']:checked").val()}.tar.xz`
        console.log(downloadUrl)
        window.open(downloadUrl)
    }

    function onDownloadSDKClicked() {
        const downloadUrl = `/cheribsd/${$('#cheribsd-release').val()}/morello-sdk/morello-sdk-${$("input[name='arch']:checked").val()}-${$("input[name='os-arch']:checked").val()}-${$("input[name='os']:checked").val()}.tar.xz`
        console.log(downloadUrl)
        window.open(downloadUrl)
    }

    function getVersionList() {
        <?php
        $versions = array();
        $root_dir = './builds/cheribsd';
        if (is_dir($root_dir)) {
            if ($dh = opendir($root_dir)) {
                while (($file = readdir($dh)) !== false) {
                    if (is_dir("$root_dir/$file") && $file != "." && $file != "..") {
                        array_push($versions, $file);
                    }
                }
                closedir($dh);
            }
        }
        foreach ($versions as $version) {
            echo '$("#cheribsd-release").append($("<option/>", { value: "'.$version.'", text: "'.$version.'" }));';
        }
        ?>
    }

    $(() => {
        getVersionList();
    });
</script>
</html>
