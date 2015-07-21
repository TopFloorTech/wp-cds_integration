<?php
/*
do all encoding inline, do not pre-encode anything
encode in the following order:
  1. encode url query string items with urlencode()
  2. encode all javascript/json variables with json_encode()
  3. encode all HTML attributes with htmlspecialchars()
  4. HTML inner text should be encoded with htmlspecialchars() if coming from page URL
HTML inner text should not be encoded if coming from database
*/
// host should normally be www.product-config.net, do NOT prefix with http://
$host = 'www.product-config.net';
// domain is your specific company identifier within the CDS application
$domain = 'gilman';

class CDSWebService {
    private $host;
    private $unitSystem;

    // uncomment one of the sock_library lines to use that library for
    // communication with the CDS web services

    // private $sock_library = 'socket_create';
    private $sock_library = 'fsockopen';
    // private $sock_library = 'curl';

    public function __construct($host, $unitSystem = 'english') {
        $this->host = $host;
        $this->unitSystem = $unitSystem;
    }

    public function getJSON($out) {
        $json = '';
        $in_content = false;
        $is_chunked = false;
        $is_ok = false;
        $chunked_odd_line = false;
        foreach (explode("\r\n", $out) as $line) {
            if (!$in_content) {
                if (strpos($line, '200 OK') !== false) {
                    $is_ok = true;
                } elseif ($line === 'Transfer-Encoding: chunked') {
                    $is_chunked = true;
                } elseif ($line === '') {
                    $in_content = true;
                }

            } else {
                if (!$is_ok) {
                    return array('error' =>
                            'Could not connect to the catalog server');

                } elseif ($is_chunked) {
                    if ($line === '0') {
                        break;
                    }
                    $chunked_odd_line = !$chunked_odd_line;
                    if ($chunked_odd_line) {
                        continue;
                    }
                    $json .= $line;
                } else {
                    $json .= $line;
                }
            }
        }

        return json_decode($json, true);
    }

    public function sendRequestSocketCreate($resource) {
        $ip = gethostbyname($this->host);
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if ($socket === false) {
            return array('error' => 'Could not connect to the catalog server');
        }
        $result = socket_connect($socket, $ip, 80);
        if ($result === false) {
            return array('error' => 'Could not connect to the catalog server');
        }

        $in = "GET $resource HTTP/1.1\r\n";
        $in .= "Host: $this->host\r\n";
        $in .= "User-Agent: PHP CDS reference web service implementation\r\n";
        $in .= "Cache-Control: no-cache\r\n";
        $in .= "Pragma: no-cache\r\n";
        $in .= "Connection: Close\r\n\r\n";
        socket_write($socket, $in, strlen($in));

        $out = '';
        while ($buf = socket_read($socket, 2048)) {
            $out .= $buf;
        }
        socket_close($socket);

        return $out;
    }

    public function sendRequestFsockopen($resource) {
        $fp = fsockopen($this->host, 80);
        if (!$fp) {
            return array('error' => 'Could not connect to the catalog server');
        }

        $in = "GET $resource HTTP/1.1\r\n";
        $in .= "Host: $this->host\r\n";
        $in .= "User-Agent: PHP CDS reference web service implementation\r\n";
        $in .= "Cache-Control: no-cache\r\n";
        $in .= "Pragma: no-cache\r\n";
        $in .= "Connection: Close\r\n\r\n";
        fwrite($fp, $in);

        $out = '';
        while (!feof($fp)) {
            $out .= fgets($fp, 2048);
        }
        fclose($fp);

        return $out;
    }

    public function sendRequestCurl($resource) {
        $ch = curl_init("http://$this->host$resource");
        if (!$ch) {
            return array('error' => 'Could not connect to the catalog server');
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'GET $resource HTTP/1.1',
            'User-Agent: PHP CDS reference web service implementation',
            'Cache-Control: no-cache',
            'Pragma: no-cache',
            'Connection: Close'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $out = curl_exec($ch);
        if (!$out) {
            return array('error' => 'Could not connect to the catalog server');
        }

        curl_close($ch);

        return json_decode($out, true);
    }

    public function sendRequest($resource) {
        if ($this->sock_library === 'socket_create') {
            return $this->getJSON($this->sendRequestSocketCreate($resource));
        } elseif ($this->sock_library === 'fsockopen') {
            return $this->getJSON($this->sendRequestFsockopen($resource));
        } elseif ($this->sock_library === 'curl') {
            return $this->sendRequestCurl($resource);
        }

        return null;
    }

    public function sendProductRequest($domain, $id, &$error, $category = null) {
        $resource = "/catalog3/service?o=product&d=$domain&id=$id&unit=$this->unitSystem";
        if ($category != null) {
            $resource .= "&cid=$category";
        }

        $product_info = $this->sendRequest($resource);

        if (array_key_exists('error', $product_info)) {
            $error = $product_info['error'];
        } else {
            $error = false;
        }

        return $product_info;
    }

    public function sendCategoryRequest($domain, $category, &$error) {
        $resource = "/catalog3/service?o=category&d=$domain&cid=$category&unit=$this->unitSystem";
        $category_info = $this->sendRequest($resource);

        if (array_key_exists('error', $category_info)) {
            $error = $category_info['error'];
        } else {
            $error = false;
        }

        return $category_info;
    }
}
