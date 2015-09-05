<?php
function buildFileList($dir, $extensions) {
	if (!is_dir($dir) || !is_readable($dir)) {
	return false;
	} else {
		if (is_array($extensions)) {
			$extensions = implode('|', $extensions);
			}
			$pattern = "/\.(?:{$extensions})$/i";
			$folder = new DirectoryIterator($dir);
			$files = new RegexIterator($folder, $pattern);
			$filenames = array();
			foreach ($files as $file) {
				if(strstr($file, 'x-') || strstr($file, '_') ) {
							//don't show these files...
						}
				$filenames[] = $file->getFilename();
				}
			rsort($filenames);
			return $filenames;
	}
}

function blogsort($a, $b) {
	if ( strtotime($a) ) {
		$a = strtotime($a);
		$b = strtotime($b);
	}
	if ($a == $b) {
		return 0;
	}
	return ($a < $b) ? 1 : -1;
}
?>