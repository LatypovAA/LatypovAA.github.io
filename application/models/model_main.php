<?php
class Model_main extends Model 
{
    public function get_data()
    {
        //$code = $code_vk;
        $code = '2ddddc25b1bbe7f5cf';
        $redirect_url = "&redirect_uri=http://localhost/main/auth";
        $url_access_token = ('https://oauth.vk.com/access_token?client_id=4285522&client_secret=lo4wIAawqK1Hs0vS4eXo&code=');
        $url_access_token = $url_access_token . $code;
        $url_access_token = $url_access_token . $redirect_url;
               //  echo $url_access_token;
        $ch = curl_init($url_access_token); 
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $result = curl_exec($ch);
        if(curl_errno($ch))
        {
            echo 'curl error: ' . curl_error($ch);die;
        }
        curl_close($ch);
        return $result;
    }
}