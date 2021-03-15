<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

function enc($id, $status = 0)
{
  $secret_key = '017321';
  $secret_iv = 'bangsaku';

  $output = false;
  $encrypt_method = "AES-256-CBC";
  $key = hash( 'sha256', $secret_key );
  $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

  $xstring = time() . $id;

  if( $status == '0' ) {
      $output = base64_encode( openssl_encrypt( $xstring, $encrypt_method, $key, 0, $iv ) );
  }
  else if( $status == '1' ){
      $data = openssl_decrypt( base64_decode( $id ), $encrypt_method, $key, 0, $iv );
      $output = substr($data, 10, 7);
      // $output = substr($data, 10, 4); // Sebelumnya 4 digit, hanya bisa menampung 9_999 id
  }

  return $output;
}

function substrwords($text, $maxchar) {
  return mb_strimwidth($text, 0, $maxchar, "...");
}
