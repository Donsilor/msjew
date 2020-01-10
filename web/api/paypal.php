<?php

//获取文件列表
function getFile($dir)
{
    $fileArray = [];
    if (false != ($handle = opendir($dir))) {
        while (false !== ($file = readdir($handle))) {
            //去掉"“.”、“..”以及带“.xxx”后缀的文件
            if ($file != "." && $file != ".." && strpos($file, ".")) {
                $fileArray[] = [
                    'path' => $file,
                    'createTime' => filectime($dir . $file)
                ];
            }
        }
        //关闭句柄
        closedir($handle);
    }
    return $fileArray;
}

$basePath = './paypal/';
$limit = $_GET['limit'] ?: 100;

if (!empty($_GET['view'])) {
    if (!empty($_GET['file'])) {
        $filePath = $basePath . $_GET['file'];
        if (file_exists($filePath)) {
            echo "<pre>\r\n";
            echo file_get_contents($filePath);
            echo "\r\n</pre>";
        }
    } else {
        if ($fileList = getFile($basePath)) {
            //排序
            array_multisort(array_column($fileList, 'createTime'), SORT_DESC, $fileList);

            foreach ($fileList as $key => $item) {
                if ($key == $limit) {
                    break;
                }
                $url = sprintf('?view=true&file=%s', $item['path']);
                echo sprintf("<a href='%s'>%s &nbsp; %s</a></br>", $url, $item['path'], date('Y-m-d H:i:s', $item['createTime']));
            }
        }
    }
    exit();
}

function em_getallheaders()
{
    foreach ($_SERVER as $name => $value)
    {
        if (substr($name, 0, 5) == 'HTTP_')
        {
            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
        }
    }
    return $headers;
}

$data = [];
$data['headers'] = em_getallheaders();
$data['get'] = $_GET;
$data['post'] = $_POST;
$data['body'] = @file_get_contents('php://input');

$str = @var_export($data, true);
$filePath = $basePath . uniqid() . '.txt';
file_put_contents($filePath, $str);

$json = <<<JSON
{
  "verification_status": "SUCCESS"
}
JSON;

echo $json;
