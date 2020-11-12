<?php
namespace frontend\controllers;

use yii\httpclient\client;
use yii\web\Controller;

class ParserController extends Controller
{
    public $client;

    /**
     * Получает HTML страницы с покупками инсайдеров с FINWIZ.COM
     * 
     */
    private function GetHtmlContentByLink ($link) {
        $client = new client (['baseUrl' => $link]);
        $response = $client->createRequest()->send();
        return $response->content;
    }

    /**
     * Вывод View экшна test
     * 
     */
    public function actionTest () {
        $InsiderBuyDeals = $this->GetInsiderBuyHistoryArray();
        $MarginTickers = $this->GetMarginSharesArray();
        return $this->render('test', [
            'Deals' => $InsiderBuyDeals,
            'MarginTickers' => $MarginTickers,
        ]);
    }


    
    /**
     * Получает двухуровневый массив с покупками инсайдеров (костыльненько)
     * 
     * array (size=***)
     *  =>
     * array (size=5)
     * 'ticker' => string
     * 'date' => string
     * 'value' => int
     * 'link' => string
     * 'secDate' => string
     * 
     */
    public function GetInsiderBuyHistoryArray () {
        $deals = $this->GetHtmlContentByLink('https://finviz.com/insidertrading.ashx?tv=100000&tc=1&o=Ticker');
        $cd = 'UTF-8';
        $ndl1 = 'onclick="if(ignoreOnClick==false)window.location=';
        $ndl2 = 'class="tab-link">';
        $ndl3 = 'style="white-space:nowrap"';
        $ndl4 = 'align="right"';
        $ndl5 = 'onclick = "ignoreOnClick=true;"';
        $dealsArray = array();
        $dealsSubArray = array();
        // Проверка на пустую таблицу
        if (mb_stripos($deals, $ndl1, 0, $cd)) {
            // Позиция строки
            $pos = mb_stripos($deals, $ndl1, 0, $cd);
            $sub_deals = $deals;
            while ($pos > 0) {
                // Ticker
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl1, $cd), NULL, $cd);
                $pos = mb_stripos($sub_deals, $ndl2, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl2, $cd), NULL, $cd);
                $pos = mb_stripos($sub_deals, '</a>', 0, $cd);
                $ticker = mb_substr($sub_deals, 0, $pos, $cd);
                $dealsSubArray['ticker'] = $ticker;
                // Owner
                $pos = mb_stripos($sub_deals, $ndl3, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl3, $cd), NULL, $cd);
                // Relationship
                $pos = mb_stripos($sub_deals, $ndl3, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl3, $cd), NULL, $cd);
                // Date
                $pos = mb_stripos($sub_deals, $ndl3, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl3, $cd), NULL, $cd);
                $pos = mb_stripos($sub_deals, '</td>', 0, $cd);
                $date = mb_substr($sub_deals, 1, $pos - 1, $cd);
                $dealsSubArray['date'] = $date;
                // Translation
                $pos = mb_stripos($sub_deals, $ndl3, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl3, $cd), NULL, $cd);
                // Cost
                $pos = mb_stripos($sub_deals, $ndl4, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl4, $cd), NULL, $cd);
                // Shares
                $pos = mb_stripos($sub_deals, $ndl4, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl4, $cd), NULL, $cd);
                // Value
                $pos = mb_stripos($sub_deals, $ndl4, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl4, $cd), NULL, $cd);
                $pos = mb_stripos($sub_deals, '</td>', 0, $cd);
                $value = mb_substr($sub_deals, 1, $pos - 1, $cd);
                $value = intval(str_replace(',', '', $value));
                $dealsSubArray['value'] = $value;
                // Shares total
                $pos = mb_stripos($sub_deals, $ndl4, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl4, $cd), NULL, $cd);
                // SEC form 4: link & date
                $pos = mb_stripos($sub_deals, $ndl5, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl5, $cd), NULL, $cd);
                $pos = mb_stripos($sub_deals, '<a href="', 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen('<a href="', $cd), NULL, $cd);
                $pos = mb_stripos($sub_deals, '" class="tab-link"', 0, $cd);
                $link = mb_substr($sub_deals, 0, $pos, $cd);
                $dealsSubArray['link'] = $link;
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen('" class="tab-link"', $cd), NULL, $cd);
                $pos = mb_stripos($sub_deals, '</a>', 0, $cd);
                $secDate = mb_substr($sub_deals, 1, $pos - 1, $cd);
                $dealsSubArray['secDate'] = $secDate;
                // Следующая строка таблицы
                $pos = mb_stripos($sub_deals, $ndl1, 0, $cd);
                array_push($dealsArray, $dealsSubArray);
                $dealsSubArray = array();
            }
        }
        return ($dealsArray);
    }

    public function GetMarginSharesArray () {
        $html = $this->GetHtmlContentByLink('https://www.tinkoff.ru/invest/margin/equities/');
        $cd = 'UTF-8';
        $node = 'uikit/table.tableRow';
        $tickersArray = array();
        $tickersSubArray = array();
        $pos = mb_strpos($html, $node, 0, $cd);
        if($pos) {
            $node = 'tbody';
            $pos = mb_strpos($html, $node, 0, $cd);
            $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
            while ($pos>0) {
                // ticker
                $node = 'uikit/table.tableRow';
                $pos = mb_strpos($html, $node, 0, $cd);
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = 'class="LiquidPapersPure';
                $pos = mb_strpos($html, $node, 0, $cd);
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = 'data-qa-file="LiquidPapersPure">';
                $pos = mb_strpos($html, $node, 0, $cd);
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = '<!--';
                $pos = mb_strpos($html, $node, 0, $cd);
                $value = mb_substr($html, 0, $pos, $cd);
                $tickersSubArray['ticker'] = $value;
                // Тип тикера
                $node = '<!-- -->';
                $pos = mb_strpos($html, $node, 0, $cd);
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = '<!-- -->';
                $pos = mb_strpos($html, $node, 0, $cd);
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = '</div></div></span></a></td><';
                $pos = mb_strpos($html, $node, 0, $cd);
                $value = mb_substr($html, 0, $pos, $cd);
                $tickersSubArray['type'] = $value;
                // ISIN
                $node = 'class="LiquidPapersPure__isin';
                $pos = mb_strpos($html, $node, 0, $cd);
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = 'data-qa-file="LiquidPapersPure">';
                $pos = mb_strpos($html, $node, 0, $cd);
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = '</div></div></span></a></td><';
                $pos = mb_strpos($html, $node, 0, $cd);
                $value = mb_substr($html, 0, $pos, $cd);
                $tickersSubArray['isin'] = $value;
                // Доступен шорт
                $node = 'data-qa-file="Table">';
                $pos = mb_strpos($html, $node, 0, $cd);
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = '</div></span></a></td><';
                $pos = mb_strpos($html, $node, 0, $cd);
                $value = mb_substr($html, 0, $pos, $cd);
                $tickersSubArray['short'] = $value;
                // Ставка риска в лонг
                $node = 'data-qa-file="Table">';
                $pos = mb_strpos($html, $node, 0, $cd);
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = '<!-- -->';
                $pos = mb_strpos($html, $node, 0, $cd);
                $value = mb_substr($html, 0, $pos, $cd);
                $tickersSubArray['longRisk'] = $value;
                // Ставка риска в шорт
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = '<!-- -->';
                $pos = mb_strpos($html, $node, 0, $cd);
                if ($pos<10) {
                    $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                    $node = '</div></span></a></td></tr>';
                    $pos = mb_strpos($html, $node, 0, $cd);
                    $value = mb_substr($html, 0, $pos, $cd);
                } else {
                    $value = NULL;
                }
                $tickersSubArray['shortRisk'] = $value;

                // Следующая строка таблицы
                $node = 'data-qa-type="uikit/table.tableRow"';
                $pos = mb_strpos($html, $node, 0, $cd);
                if ($tickersSubArray['type']==='Акции' AND $tickersSubArray['short']==='Доступен') {
                    array_push($tickersArray, $tickersSubArray);
                }
                $tickersSubArray = array();
            }
        }
        return ($tickersArray);
    }
}