<?php
//测试代码
$billNo = $_GET['billNo'];
echo (string)(new billQuery($billNo));

/**
 * 给鸿昌顺写的单号查询类
 * 构造函数传入一个订单号
 * 得到一个可以直接echo到页面上的字符串
 * @author jobsfan
 *
 */
class billQuery
{
    public $feedbackStr = '';
    
    /**
     * 输入单号
     * @param String $billNo
     */
    public function __construct($billNo)
    {
        $this->initDataAndDispatch($billNo);
    }
    
    /**
     * 初始化单号以及分发到对应的网站
     */
    public function initDataAndDispatch($billNo)
    {
        $this->feedbackStr = '<div class="resultErrorHolder">查询有错误</div>';
        
        $billNo = trim((string)($billNo)); //if (!$billNo || !preg_match('%^[a-z0-9A-Z][a-z0-9A-Z-]{8,16}[a-z0-9A-Z]$%i', $billNo)) return $this->feedbackStr = '<div style="">您输入的单号格式不正确！</div>';
        
        if (preg_match('%^1Z[a-z0-9A-Z]{16}$%i', $billNo)) // UPS 1Z开头的数字和字母混合18位
        {
            $this->ups($billNo);
        }
        elseif (preg_match('%^GD\d+WW$%i', $billNo)) // TNT GD开头WW结尾
        {
            $this->tnt($billNo);
        }
        elseif (preg_match('%^(90|51)\d{10}$%', $billNo)) // 优速 90和51开头的12位数字
        {
            $this->uc56($billNo);
        }
        elseif (preg_match('%^333\d{7}$%', $billNo)) // 日本专线逸迅达 333开头的10位数字
        {
            $this->tdlexp($billNo);
        }
        elseif (preg_match('%^299\d{9}$%', $billNo)) //日本专线林道 299开头的10位数字
        {
            $this->kdbund($billNo);
        }
        elseif (preg_match('%^\d{3}\-\d{8}$%', $billNo)) //空运查询 前面3位数开头 - 横线隔开  后面8位数
        {
            $this->tracktrace($billNo);
        }
        elseif (preg_match('%^1[0|1]\d{9}$%', $billNo)) //百世物流 10和11开头的11位数字
        {
            $this->best800($billNo);
        }
        elseif (preg_match('%^\d{10}$%', $billNo)) //DHL快递    10位数字   没有规则
        {
            $this->dhl($billNo);
        }
        elseif (preg_match('%^\d{12}$%', $billNo)) //联邦快递  12位数字   没有规则
        {
            $this->fedex($billNo);
        }
        else 
        {
            return $this->feedbackStr = '<div class="resultErrorHolder">您输入的单号格式不正确！</div>';
        }
    }
    
    //UPS的处理逻辑 https://www.ups.com/cn/zh/Home.page?loc=zh_CN
    public function ups($billNo) //测试单号 1ZAF61240462165724
    {
        $rawHtml = $this->curlRemote('https://wwwapps.ups.com/WebTracking/track?HTMLVersion=5.0&loc=zh_CN&Requester=UPSHome&WBPM_lid=homepage%252Fct1.html_pnl_trk&trackNums='.$billNo.'&track.x=Track');
        preg_match('%(<div class="panel panel-default module3">.*?)<script[^>]*?>%is', $rawHtml, $matches);
        if (isset($matches[1]) && $matches[1])
        {
            $this->feedbackStr = '<div class="uniforResultHolder">'.preg_replace('%<ul class="pull-right">.*?</ul>%is', '', $matches[1]).'</div>';
        }
    }
    
    //TNT的处理逻辑 https://www.tnt.com/express/zh_cn/site/shipping-tools/tracking.html?cons=GD218030238WW&searchType=CON&source=home_widget
    public function tnt($billNo) //测试单号 GD218030238WW
    {
        $rawHtml = $this->curlRemote('https://www.tnt.com/api/v2/shipment?con='.$billNo.'&searchType=CON&locale=zh_CN&channel=OPENTRACK');
        $jsonArr = json_decode($rawHtml, true);
        if (isset($jsonArr['tracker.output']['consignment'][0]['statusData']) && count($jsonArr['tracker.output']['consignment'][0]['statusData']))
        {
            $this->feedbackStr = $this->tableRender($jsonArr['tracker.output']['consignment'][0]['statusData'], array(
                array('th' => '状态', 'usage' => true,'key' => 'statusDescription', 'phpTmpl' => '<?php echo $data; ?>'),
                array('th' => '时间', 'usage' => true,'key' => 'localEventDate', 'phpTmpl' => '<?php echo date("Y-m-d H:i:s",strtotime($data)); ?>'),
                array('th' => '位置', 'usage' => true,'key' => 'depot', 'phpTmpl' => '<?php echo $data; ?>'),
            ));
        }
    }
    
    //优速的处理逻辑 http://www.uc56.com/
    public function uc56($billNo) //测试单号 900043100394       518452465794
    {
        $rawHtml = $this->curlRemote('http://www.kuaidi100.com/query?type=youshuwuliu&postid='.$billNo.'&temp=0.99173'.rand(1000000000,9999999999));
        $jsonArr  = json_decode($rawHtml,true);
        if ($jsonArr['message'] == 'ok' && isset($jsonArr['data'])) //计算前端可以显示的一个table
        {
            $this->feedbackStr = $this->tableRender($jsonArr['data'], array(
                array('th' => '时间', 'usage' => true,'key' => 'time', 'phpTmpl' => '<?php echo $data; ?>'),
                array('th' => '', 'usage' => false,'key' => '', 'phpTmpl' => ''),
                array('th' => '地点和跟踪进度', 'usage' => true,'key' => 'context', 'phpTmpl' => '<?php echo $data; ?>'),
                array('th' => '', 'usage' => false,'key' => '', 'phpTmpl' => ''),
            ));
        }
    }
    
    //日本专线逸迅达的处理逻辑 http://www.tdlexp.com/cgi-bin/GInfo.dll?EmmisTrack
    public function tdlexp($billNo) //测试单号 3334747124
    {
        $rawHtml = $this->curlRemote('http://www.tdlexp.com/cgi-bin/GInfo.dll?EmmisTrack', 'post', 'w=tdlexp&ntype=1000&cno='.$billNo);
        preg_match("%(<table\s.*?\sclass='trackListTable'>.*?</table>)<br>%is", iconv('gb2312', 'utf-8//IGNORE', $rawHtml), $matches);
        if (isset($matches[1]) && $matches[1])
        {
            $this->feedbackStr = '<div class="uniforResultHolder">'.$matches[1].'</div>';
        }
    }
    
    //日本专线林道的处理逻辑 http://cx.kdbund.com/
    public function kdbund($billNo) //测试单号 299111278104
    {
        $rawHtml = $this->curlRemote('http://cx.kdbund.com/ldxQueryTrack/getExpressDelivery', 'post', array('detailCompanyId' => 'ldx', 'detailTicketNo' => $billNo));
        $jsonArr = json_decode($rawHtml, true);
        if ($jsonArr['resultCode'] == 0 && isset($jsonArr['deliverInfo']))
        {
            $this->feedbackStr = $this->tableRender($jsonArr['deliverInfo'], array(
                array('th' => '时间', 'usage' => true,'key' => 'date', 'phpTmpl' => '<?php echo $data; ?>'),
                array('th' => '地点', 'usage' => true,'key' => 'place', 'phpTmpl' => '<?php echo str_replace("林道","鸿昌顺",$data); ?>'),
                array('th' => '操作记录', 'usage' => true,'key' => 'info', 'phpTmpl' => '<?php echo $data; ?>'),
            ));
        }
    }
    
    //空运查询的逻辑处理 https://www.track-trace.com/aircargo
    public function tracktrace($billNo) //测试单号 403-46581636          176-56859456   跳转！！！！
    {
        $billArr = explode('-', $billNo);
        $this->feedbackStr = '<script>location.href="http://www.polaraircargo.com/TrackAndTraceUI/WebForm1.aspx?pe='.$billArr[0].'&se='.$billArr[1].'";</script>';
    }
    
    //百世物流的逻辑处理 http://www.800best.com/freight/track.asp
    public function best800($billNo) //测试单号 10951627046      11063972356
    {
        $rawHtml = $this->curlRemote('http://www.kuaidi100.com/query?type=baishiwuliu&postid='.$billNo.'&temp=0.651817'.rand(1000000000,9999999999));
        $jsonArr  = json_decode($rawHtml,true);
        if ($jsonArr['message'] == 'ok' && isset($jsonArr['data'])) //计算前端可以显示的一个table
        {
            $this->feedbackStr = $this->tableRender($jsonArr['data'], array(
                array('th' => '时间', 'usage' => true,'key' => 'time', 'phpTmpl' => '<?php echo $data; ?>'),
                array('th' => '', 'usage' => false,'key' => '', 'phpTmpl' => ''),
                array('th' => '地点和跟踪进度', 'usage' => true,'key' => 'context', 'phpTmpl' => '<?php echo $data; ?>'),
                array('th' => '', 'usage' => false,'key' => '', 'phpTmpl' => ''),
            ));
        }
    }
    
    //DHL的逻辑处理 http://www.cn.dhl.com/zh/express/tracking.html?brand=DHL&AWB=4159475471%0D%0A
    //http://www.cn.dhl.com/shipmentTracking?AWB=4159475471&countryCode=cn&languageCode=zh&_=1535209424457
    public function dhl($billNo) //测试单号 4159475471
    {
        $rawHtml = $this->curlRemote('http://www.cn.dhl.com/shipmentTracking?AWB='.$billNo.'&countryCode=cn&languageCode=zh&_='.time());
        $jsonArr = json_decode($rawHtml, true);
        if (isset($jsonArr['results'][0]['checkpoints']) && count($jsonArr['results'][0]['checkpoints']))
        {
            $this->feedbackStr = $this->tableRender($jsonArr['results'][0]['checkpoints'], array(
                array('th' => '跟踪进度', 'usage' => true,'key' => 'description', 'phpTmpl' => '<?php echo $data; ?>'),
                array('th' => '时刻', 'usage' => true,'key' => 'time', 'phpTmpl' => '<?php echo $data; ?>'),
                array('th' => '日期', 'usage' => true,'key' => 'date', 'phpTmpl' => '<?php echo $data; ?>'),
                array('th' => '地点', 'usage' => true,'key' => 'location', 'phpTmpl' => '<?php echo $data; ?>'),
            ));
        }
    }
    
    //联邦快递 https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber=447058121430&cntry_code=cn&locale=zh_CN
    public function fedex($billNo) //测试单号 447058121430
    {
        $rawHtml = $this->curlRemote('https://www.fedex.com/trackingCal/track', 'post', 'data=%7B%22TrackPackagesRequest%22%3A%7B%22appType%22%3A%22WTRK%22%2C%22appDeviceType%22%3A%22DESKTOP%22%2C%22supportHTML%22%3Atrue%2C%22supportCurrentLocation%22%3Atrue%2C%22uniqueKey%22%3A%22%22%2C%22processingParameters%22%3A%7B%7D%2C%22trackingInfoList%22%3A%5B%7B%22trackNumberInfo%22%3A%7B%22trackingNumber%22%3A%22'.$billNo.'%22%2C%22trackingQualifier%22%3A%22%22%2C%22trackingCarrier%22%3A%22%22%7D%7D%5D%7D%7D&action=trackpackages&locale=zh_CN&version=1&format=json');
        $jsonArr = json_decode($rawHtml, true);
        
        if (isset($jsonArr['TrackPackagesResponse']['packageList'][0]['scanEventList']) && count($jsonArr['TrackPackagesResponse']['packageList'][0]['scanEventList']))
        {
            $this->feedbackStr = $this->tableRender($jsonArr['TrackPackagesResponse']['packageList'][0]['scanEventList'], array(
                array('th' => '日期', 'usage' => true,'key' => 'date', 'phpTmpl' => '<?php echo $data; ?>'),
                array('th' => '时间', 'usage' => true,'key' => 'time', 'phpTmpl' => '<?php echo $data; ?>'),
                array('th' => '状态地点', 'usage' => true,'key' => 'status', 'phpTmpl' => '<?php echo $data; ?>'),
                array('th' => 'statusCD', 'usage' => true,'key' => 'statusCD', 'phpTmpl' => '<?php echo $data; ?>'),
                array('th' => 'scanLocation', 'usage' => true,'key' => 'scanLocation', 'phpTmpl' => '<?php echo $data; ?>'),
            ));
        }
        
        /* $rawHtml = $this->curlRemote('http://www.kuaidi100.com/query?type=fedexcn&postid='.$billNo.'&temp=0.785204'.rand(1000000000,9999999999));
        $jsonArr  = json_decode($rawHtml,true);
        if ($jsonArr['message'] == 'ok' && isset($jsonArr['data'])) //计算前端可以显示的一个table
        {
            $this->feedbackStr = $this->tableRender($jsonArr['data'], array(
                array('th' => '时间', 'usage' => true,'key' => 'time', 'phpTmpl' => '<?php echo $data; ?>'),
                array('th' => '地点和跟踪进度', 'usage' => true,'key' => 'context', 'phpTmpl' => '<?php echo $data; ?>'),
            ));
        } */
    }
    
    public function __toString()
    {
        return $this->feedbackStr;
    }
    
    public function curlRemote($url,$method = 'get', $data = null, $headerArr = null)
    {
        if (!$headerArr) $headerArr = array(
            'CLIENT_IP: '.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255),
            'X-FORWARDED-FOR: '.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255),
            'REMOTE_ADDR: '.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255),
        );
        
        $uaArr = array(
            'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36',
            'Opera/9.80 (Windows NT 5.1; Edition IBIS) Presto/2.12.388 Version/12.17',
            'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36',
            'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36',
            'Mozilla/5.0 (Windows NT 5.1; rv:30.0) Gecko/20100101 Firefox/30.0'
        );
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, $uaArr[array_rand($uaArr)]);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArr);
        
        if ($method == 'post' && $data)
        {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        
        if (substr($url, 0,5) == 'https')
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        //curl_setopt($curl, CURLOPT_HEADER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
    
    //用于渲染table的一个函数
    public function tableRender($sortData, $thAndFilter) //th => ‘时间’, usage => true, phpTmpl => '<?php echo date(); ...>'
    {
        $tableStr = '<div class="uniforResultHolder"><table>';
        
        $thArr = array();
        $tdArr = array();
        
        foreach ($sortData as $tdkey => $tdvalue)
        {
            $tdTmp = array();
            foreach ($thAndFilter as $key => $value)
            {
                if (!$tdkey && $value['usage']) $thArr[$key] = '<th>'.$value['th'].'</th>';
                if ($value['usage']) $tdTmp[$key] = '<td>'.phpTmplRenderModel::nodeRender($value['phpTmpl'], array('data' => ($value['key'] ? $tdvalue[$value['key']] : $tdvalue[$key]))).'</td>';
            }
            $tdArr[] = '<tr>'.implode('', $tdTmp).'</tr>';
        }
            
        $tableStr .= '<tr>'.implode('', $thArr).'</tr>';
        $tableStr .= implode('', $tdArr);
        
        $tableStr .= '</table></div>';
        return $tableStr;
    }
}


/** php模板渲染程序
 * phpTmplRender.php Created by Jobs Fan on 2017年5月10日
 * ==============================================
 * CopyRight 2012-2017 All Right Reserved
 * ----------------------------------------------
 * No spread No copy No print
 * ==============================================
 * @date: 2017年5月10日
 * @author: Jobs Fan 289047960@qq.com
 */
class phpTmplRenderModel
{
    static public $tmpl;
    static public $attr;
    
    static public function rendTmpl()
    {
        $tmpl = self::$tmpl;
        $attr = self::$attr;
        return '<?php foreach ($attr as $key => $value) $$key = $value; ?>'.$tmpl;
    }
    
    static public function nodeRender($tmpl,$attr) //最末端一级
    {
        stream_wrapper_register("var", "VariableStream");
        
        self::$tmpl = $tmpl;
        self::$attr = $attr;
        
        ob_start();
        include("var://good");
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
}


class VariableStream
{
    private $string;
    private $position;
    
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        //$url = parse_url($path);
        //$treeid = $url["host"];
        
        //根据ID到数据库中取出php字符串代码
        $this->string = phpTmplRenderModel::rendTmpl();
        $this->position = 0;
        return true;
    }
    
    public function stream_read($count)
    {
        $ret = substr($this->string, $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }
    
    public function stream_eof() {}
    public function stream_stat() {}
}
?>