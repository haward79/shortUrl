<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>再短一點 - Taiwancan短網址生成服務</title>
        <link rel="icon" href="images/icon.webp" />
        <link rel="stylesheet" href="css/index.css" />
    </head>
    <body>

        <header>
            <img src="images/title.webp" alt="再短一點" />
        </header>

        <section id="noJS_block">
            <p>您的瀏覽器不支援 JavaScript 或 JavaScript 已禁用，請更換瀏覽器或檢查瀏覽器設定後再訪問本站。</p>
        </section>

        <script>
            // JavaScript is available. Hide no-javascript message.
            document.getElementById("noJS_block").style.display = "none";
        </script>

        <section id="redirectUrl_block" style="display:none;">
            <label for="redirectUrl_text">您確定要前往：</label>
            <input id="redirectUrl_text" type="text" value="" readonly />
            <input type="button" value="立即前往" onclick="window.location.href=document.getElementById('redirectUrl_text').value;" />
            <input type="button" value="回首頁" onclick="window.location.href='index.php';" />
        </section>

        <section id="inputUrl_block" style="display:none;">
            <form action="." method="post">
                <input name="url_text" type="text" value="" placeholder="請輸入原網址（僅支援 http 與 https）" required />
                <input type="submit" value="縮短吧網址！" />
            </form>
        </section>

        <section id="outputUrl_block" style="display:none;">
            <label for="shortUrl_text">已為您縮短成：</label>
            <input id="shortUrl_text" type="text" value="" readonly />
            <input type="button" value="複製短網址" onclick="document.getElementById('shortUrl_text').select(); document.execCommand('copy');" />
            <input type="button" value="返回" onclick="window.location.href='index.php';" />
        </section>

        <footer>
            <p>問題及建議請洽Taiwancan　|　<a href="https://www.taiwancan.tw/" target="_blank">Taiwancan</a> 2015-<script>document.write((new Date()).getFullYear());</script> 版權所有　|　Build v1.0</p>
        </footer>

        <?php

            ini_set('display_errors', true);
        
            // Redirect to mapped url.
            if(isset($_GET['id']) && is_numeric($_GET['id']) && !strpos($_GET['id'], '.') && $_GET['id'] > 0)
            {
                require_once 'php/stamp.php';

                $mappedUrl = getMappedUrl($_GET['id']);

                if($mappedUrl === '')
                    $mappedUrl = '您輸入的短網址不存在喔！';
                else
                    $mappedUrl = urldecode($mappedUrl);

                echo '
                <script>
                    document.getElementById("redirectUrl_block").style.display = "block";
                    document.getElementById("redirectUrl_text").value = "' . $mappedUrl . '";
                </script>
                ';
            }
            // Shorten url.
            else if(isset($_POST['url_text']) && $_POST['url_text'] !== '')
            {
                // Show url output block.
                echo '<script>document.getElementById("outputUrl_block").style.display = "block";</script>';

                $oriUrl = $_POST['url_text'];
                $shortUrl = '';
                $isValidOriUrl = false;

                // For url starts with https.
                if(substr($oriUrl, 0, 8) === 'https://' && strlen($oriUrl) > 8)
                {
                    $isValidOriUrl = true;

                    // Url hasn't been shortened.
                    if(strtolower(substr($oriUrl, 8, 21)) !== 'shorturl.taiwancan.tw')
                    {
                        require_once 'php/stamp.php';
                        $shortUrl = 'https://shorturl.taiwancan.tw/?id=' . generateId($oriUrl);
                    }
                    else
                        $shortUrl = $oriUrl;
                }
                // For url starts with http.
                else if(substr($oriUrl, 0, 7) === 'http://' && strlen($oriUrl) > 7)
                {
                    $isValidOriUrl = true;

                    // Url hasn't been shortened.
                    if(strtolower(substr($oriUrl, 7, 21)) !== 'shorturl.taiwancan.tw')
                    {
                        require_once 'php/stamp.php';
                        $shortUrl = 'https://shorturl.taiwancan.tw/?id=' . generateId($oriUrl);
                    }
                    else
                        $shortUrl = $oriUrl;
                }
                
                // Fill url shorten result into text field.
                if($isValidOriUrl)
                    echo '<script>document.getElementById("shortUrl_text").value="' . $shortUrl . '";</script>';
                else
                    echo '<script>document.getElementById("shortUrl_text").value="您輸入了無效的網址！";</script>';
            }
            // No url to shorten.
            else
                // Show url input block.
                echo '<script>document.getElementById("inputUrl_block").style.display = "block";</script>';

        ?>

    </body>
</html>

