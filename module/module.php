<?php
  
  error_reporting(0);

  date_default_timezone_set("Asia/Bangkok");

  session_start();

  // $version_php = '7.4'; // 5.6 atau 7.4
  
  $main_url = "http://localhost/applications/bases/codeigniter/ecommerce/cstore/";
  $main_imgurl = "http://localhost/applications/bases/codeigniter/ecommerce/cstore/assets/uploaded/";

  function loadData($url,$data) {

    $for = "http://localhost/applications/bases/codeigniter/ecommerce/cstore/adminpage/";

    // API URL
    $url = $for.$url;
    // Create a new cURL resource
    $ch = curl_init($url);
    // Setup request to send json via POST
    $payload = json_encode($data);
    // Attach encoded JSON string to the POST fields
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // Set the content type to application/json or application/x-www-form-urlencoded
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // JIKA HTTPS = true, JIKA HTTP ATAU NON SSL = false
    // Execute the POST request
    $result = curl_exec($ch);
    $err = curl_error($ch);
    // Close cURL resource
    curl_close($ch);
    if ($err) {
      return false;
    } else {
      $hasil = json_decode($result, true);
      return $hasil;
    }
  }

  $arr = array('opsi' => 'i', 'lang' => 'en');
  $rest_sistem = loadData('rest_load/load_pengaturan/',$arr);

  if (isset($_SESSION['XID_ARRAY'])) {
    $arr = array('idcust' => $_SESSION['XID_ARRAY']['cust_id'], 'lang' => 'en');
    $rest_cust = loadData('rest_load/load_customer/',$arr);
  }

  function formatRupiah($jumlah){
    $conv = "Rp ".number_format($jumlah,0,',','.');
    return($conv);
  }

  function formatRupiahnorp($jumlah,$kutip = 0){
    $conv = number_format($jumlah,$kutip,',','.');
    return($conv);
  }

  function indo($tgl = null){
    if ($tgl!=null) {
        $date = substr($tgl,0,10);
        $BulanIndo = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        $pecahkan = explode('-', $date);
        $tgl = isset($pecahkan[2]) ? $pecahkan[2] : '';
        $bln = isset($pecahkan[1]) ? $pecahkan[1] : '';
        $thn = isset($pecahkan[0]) ? $pecahkan[0] : '';
        return $tgl . ' ' . $BulanIndo[ (int)$bln-1] . ' ' . $thn;
    }else{
        return '';
    }
}

function indolengkap($tgl = null){
    if ($tgl!=null) {
        $date = substr($tgl,0,10);
        $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        $pecahkan = explode('-', $date);
        $tgl = isset($pecahkan[2]) ? $pecahkan[2] : '';
        $bln = isset($pecahkan[1]) ? $pecahkan[1] : '';
        $thn = isset($pecahkan[0]) ? $pecahkan[0] : '';
        return $tgl . ' ' . $BulanIndo[ (int)$bln-1] . ' ' . $thn;
    }else{
        return '';
    }
}

  define('CLIENT_ID', $rest_sistem['result']['google_client']);
  define('CLIENT_SECRET', $rest_sistem['result']['google_secret']);
  define('CLIENT_REDIRECT_URL', $rest_sistem['result']['google_redirect']);

  class GoogleLoginApi {
    public function GetAccessToken($client_id, $redirect_uri, $client_secret, $code) {  
      $url = 'https://www.googleapis.com/oauth2/v4/token';      
      $curlPost = 'client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code='. $code . '&grant_type=authorization_code';
      $ch = curl_init();    
      curl_setopt($ch, CURLOPT_URL, $url);    
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
      curl_setopt($ch, CURLOPT_POST, 1);    
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);  
      $data = json_decode(curl_exec($ch), true);
      $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);    
      if($http_code != 200) 
        throw new Exception('Error : Failed to receieve access token');
        
      return $data;
    }

    public function GetUserProfileInfo($access_token) { 
      $url = 'https://www.googleapis.com/oauth2/v2/userinfo?fields=name,email,gender,id,picture,verified_email';      
      
      $ch = curl_init();    
      curl_setopt($ch, CURLOPT_URL, $url);    
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
      $data = json_decode(curl_exec($ch), true);
      $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   
      if($http_code != 200) 
        throw new Exception('Error : Failed to get user information');
        
      return $data;
    }
  }

?>