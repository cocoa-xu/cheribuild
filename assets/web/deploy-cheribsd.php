<?php
  $auth = $_GET['auth'];
  if (!hash_equals(getenv('DEPLOY_CHERIBUILD_AUTH'), $auth)) {
    header('HTTP/1.0 403 Forbidden');
    echo "[!] Invalid auth\n";
    die;
  }

  header('Content-Type: text/plain');

  $repo = 'cheribuild';
  $name = 'cheribsd';
  $arch = $_GET['arch'];
  $version = $_GET['version'];
  $platform = $_GET['platform'];

  function fetch_builds($repo, $name, $arch, $version, $platform) {
    $root_dir = "builds/$name/$version";
    mkdir($root_dir, 0755, true);
    mkdir("$root_dir/images", 0755, true);
    mkdir("$root_dir/morello-sdk", 0755, true);
    mkdir("$root_dir/qemu", 0755, true);

    $files = ["morello-sdk-$arch-$platform.tar.xz" => "morello-sdk"];
    if ($arch === 'purecap') {
      $files["qemu-$platform.tar.xz"] = "qemu";
    }
    if ($platform === 'x86_64-linux-gnu') {
      $files["cheribsd-morello-$arch.img.xz"] = "images";
      $files["cheribsd-minimal-morello-$arch.img.xz"] = "images";
    }

    foreach ($files as $filename => $subdir) {
	    $url = "https://github.com/cocoa-xu/$repo/releases/download/$version/$filename";
      $sha256_url = "$url.sha256";
      $sha256_filename = "$filename.sha256";
      
      echo "[+] Fetching $sha256_url\n";
	    $ch = curl_init($sha256_url);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    $save_file_loc = "$root_dir/$subdir/$sha256_filename";
	    $fp = fopen("$save_file_loc.tmp", 'wb');
	    curl_setopt($ch, CURLOPT_FILE, $fp);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_exec($ch);
	    curl_close($ch);
	    fclose($fp);

      if (file_exists($save_file_loc)) {
        $old_checksum = hash_file('sha256', "$save_file_loc");
        $new_checksum = hash_file('sha256', "$save_file_loc.tmp");
        if ($old_checksum === $new_checksum) {
          echo "[-] $filename already up to date\n";
          continue;
        }
      }

      echo "[+] Fetching $url\n";
      $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    $save_file_loc = "$root_dir/$subdir/$filename";
	    $fp = fopen($save_file_loc, 'wb');
	    curl_setopt($ch, CURLOPT_FILE, $fp);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_exec($ch);
	    curl_close($ch);
	    fclose($fp);

      $file_checksum = hash_file('sha256', $save_file_loc);
      $expected_checksum = file_get_contents("$root_dir/$subdir/$sha256_filename", false, null, 0, 64);
      if ($file_checksum !== $expected_checksum) {
        echo "[!] Checksum mismatch for $filename\n";
        echo "    Expected: $expected_checksum\n";
        echo "    Got:      $file_checksum\n";
        unlink($save_file_loc);
        unlink("$root_dir/$subdir/$sha256_filename.tmp");
        continue;
      } else {
        echo "[-] Checksum matched for $filename\n";
        rename("$root_dir/$subdir/$sha256_filename.tmp", "$root_dir/$subdir/$sha256_filename");
      }
    }
  }

  fetch_builds($repo, $name, $arch, $version, $platform);
?>
