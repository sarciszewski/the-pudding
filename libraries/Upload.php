<?php
/*
 * File Upload Handler
 * Upload outside of webroot
 * 
 * Use (LAZY):
 * $u = new Upload();
 * $results = $u->handle();
 * 
 * Use (meticulous):
 * $results = new Upload();
 * $Upload->upload('avatar', 'jpe?g,gif,png', ['image/.*'], 'avatars');
 */
class UploadException extends Exception {}

class Upload {
  public function __construct($base = null) {
    if(empty($base)) {
      $base = $GLOBALS['settings']['upload_dir'];
    }
  }
  public function handle($extensions='*', $MIME_WhiteList = [], $subdirectory = '') {
    $results = [];
    foreach(array_keys($_FILES) as $file) {
      $results[] = $this->handle($file, $extensions, $MIME_WhiteList, $subdirectory);
    }
    return $results;
  }
  public function upload($fileIndex, $extensions='*', $MIME_WhiteList = [], $subdirectory = '') {
    if(!isset($_FILES[$fileIndex])) {
      if(isset($fileIndex['name']) && isset($fileIndex['type']) &&
         isset($fileIndex['tmp_name']) && isset($fileIndex['error']) &&
         isset($fileIndex['size'])) {
         throw new Exception("You tried to pass a \$_FILES array, not the index. Don't do that!");
      }
      throw new Exception("No \$_FILES value found for index: '".safeOut($fileIndex)."'");
    }
    $f =& $_FILES[$fileIndex];
    switch($f['error']) {
      case UPLOAD_ERR_NO_FILE:
        return null; // No exception needed
      case UPLOAD_ERR_FORM_SIZE:
      case UPLOAD_ERR_INI_SIZE:
        throw new UploadException("File <u>".safeOut($fileIndex)."</u> is to large to be uploaded through our form.");
      case UPLOAD_ERR_PARTIAL:
        throw new UploadException("File <u>".safeOut($fileIndex)."</u> was only partially received. Please check your network and try again.");
      case UPLOAD_ERR_NO_TMP_DIR:
        throw new UploadException("File <u>".safeOut($fileIndex)."</u> could not be uploaded because the temporary file directory does not exist.");
      case UPLOAD_ERR_EXTENSION:
      case UPLOAD_ERR_CANT_WRITE:
        throw new UploadException("File <u>".safeOut($fileIndex)."</u> could not be uploaded.");
    }
    // It should be UPLOAD_ERR_OK :)
    if(!empty($MIME_WhiteList)) {
      if(!$this->type_whitelist($f, $MIME_WhiteList)) {
        throw new UploadException("Illegal file type");
      }
    }
    if(!$this->ext_test($f['name'], $extensions)) {
      throw new UploadException("Illegal file extension");
    }
  }
  
  public function ext_test($filename, $extensions) {
    if($extensions == '*') {
      return true; // Why do I even bother?
    }
    $extensions = str_replace('*', '.*',
      preg_replace('/[\^\(\)\{\}\[\]\+\-\\\\\$]/', '', $extensions)
    ); // Make it suitable for regular expressions
    foreach(explode(',', $extensions) as $ext) {
      if(preg_match("/\.{$ext}$/", $filename)) { return true; }
    }
    return false;
  }
  public function type_whitelist($f, $whitelist) {
    //Okay, we have a whitelist. Let's look at the file and check its MIME type:
    //Do not trust $_FILES[$fileIndex]['type'], let's actually examine the damn
    //thing!
    $finfo = finfo_open(FILEINFO_MIME, "/usr/share/misc/magic");
    $our_type = finfo_file($finfo, $f['tmp_name']);
    foreach($whitelist as $pattern) {
      if(preg_match("/^".$pattern."$/", $our_type)) {
        return true;
      }
    }
    return false;
  }
  public function retrieve($path) {
    // Do a DB lookup
  }
}